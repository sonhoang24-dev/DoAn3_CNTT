<?php

class KetQuaModel extends DB
{
    public function start($made, $manguoidung)
    {
        $valid = true;
        $sql = "INSERT INTO `ketqua`(`made`, `manguoidung`) VALUES ('$made','$manguoidung')";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
        return $valid;
    }
    // update số lần chuyển tab lên server với thông tin tài khoảng và đề thi
    public function updateChangeTab($made, $manguoidung)
    {
        $solanchuyentab = $this->getChangeTab($made, $manguoidung)['solanchuyentab'];
        $sql = "UPDATE `ketqua` SET `solanchuyentab`='$solanchuyentab' WHERE `made`='$made' AND `manguoidung`='$manguoidung'";
        $valid = true;
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
        return $valid;
    }

    public function getChangeTab($made, $manguoidung)
    {
        $sql = "SELECT `solanchuyentab` FROM `ketqua` WHERE `made`='$made' AND `manguoidung`='$manguoidung'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function getMaKQ($made, $manguoidung)
    {
        $sql = "SELECT * FROM `ketqua` WHERE `made` = '$made' AND `manguoidung` = '$manguoidung'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function socaudung($listCauTraLoi)
    {
        $socaudung = 0;
        foreach ($listCauTraLoi as $tl) {
            $macauhoi = $tl['macauhoi'];
            $cautraloi = $tl['cautraloi'];
            $sql = "SELECT * FROM cautraloi ctl WHERE ctl.macauhoi = '$macauhoi' AND ctl.macautl = '$cautraloi' AND ctl.ladapan = 1";
            $result = mysqli_query($this->con, $sql);
            if (mysqli_num_rows($result) > 0) {
                $socaudung++;
            }
        }
        return $socaudung;
    }

   public function submit($made, $nguoidung, $thoigian)
{
    error_log("POST data: " . print_r($_POST, true));
error_log("FILES data: " . print_r($_FILES, true));
    // 1. Lấy makq và thời gian vào thi
    $stmt = $this->con->prepare("SELECT makq, thoigianvaothi FROM ketqua WHERE made = ? AND manguoidung = ? AND diemthi IS NULL");
    $stmt->bind_param("is", $made, $nguoidung);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) return false;

    $makq = $row['makq'];
    $thoigianvaothi = strtotime($row['thoigianvaothi']);
    $thoigian_str = is_array($thoigian) ? ($_POST['thoigian'] ?? date('Y-m-d H:i:s')) : $thoigian;
$thoigianlambai = strtotime($thoigian_str) - $thoigianvaothi;
if ($thoigianlambai < 0) $thoigianlambai = 0;
    if ($thoigianlambai < 0) $thoigianlambai = 0;

    // 2. Xử lý trắc nghiệm
    $listCauTraLoi = json_decode($_POST['listCauTraLoi'] ?? '[]', true);
    $socaudung_tn = 0;
    $tongcau_tn = 0;

    foreach ($listCauTraLoi as $ans) {
        $macauhoi = $ans['macauhoi'];
        $cautraloi = $ans['cautraloi'] ?? 0;

        if ($cautraloi != 0) {
            $tongcau_tn++;

            // Kiểm tra đúng/sai
            $check = $this->con->prepare("SELECT 1 FROM cauhoi WHERE macauhoi = ? AND macautl_dung = ?");
            $check->bind_param("ii", $macauhoi, $cautraloi);
            $check->execute();
            if ($check->get_result()->num_rows > 0) $socaudung_tn++;

            // Lưu đáp án
            $this->luuDapAnTracNghiem($makq, $macauhoi, $cautraloi);
        }
    }

    $diem_tracnghiem = $tongcau_tn > 0 ? round(10 * $socaudung_tn / $tongcau_tn, 2) : 0;

    // 3. Xử lý tự luận (có ảnh)
    $this->xuLyTuLuan($makq);

    // 4. Cập nhật ketqua (chỉ điểm trắc nghiệm, tổng điểm sẽ cập nhật sau khi chấm tự luận)
    $stmt = $this->con->prepare("
        UPDATE ketqua 
        SET diemthi = ?, 
            thoigianlambai = ?, 
            socaudung = ?,
            trangthai = 'Đã nộp',
            thoigiannop = NOW()
        WHERE makq = ?
    ");
    $stmt->bind_param("diii", $diem_tracnghiem, $thoigianlambai, $socaudung_tn, $makq);
    $stmt->execute();

    return true;
}

// Hàm phụ: lưu đáp án trắc nghiệm (upsert)
private function luuDapAnTracNghiem($makq, $macauhoi, $dapanchon)
{
    $stmt = $this->con->prepare("
        INSERT INTO chitietketqua (makq, macauhoi, dapanchon) 
        VALUES (?, ?, ?) 
        ON DUPLICATE KEY UPDATE dapanchon = VALUES(dapanchon)
    ");
    $stmt->bind_param("iii", $makq, $macauhoi, $dapanchon);
    $stmt->execute();
}

// Hàm phụ: xử lý tất cả câu tự luận từ FormData
private function xuLyTuLuan($makq)
{
    $index = 0;
    while (true) {
        $keyMacauhoi = "essay_{$index}_macauhoi";
        $keyNoidung  = "essay_{$index}_noidung";

        if (!isset($_POST[$keyMacauhoi])) break;

        $macauhoi = $_POST[$keyMacauhoi];
        $noidung  = $_POST[$keyNoidung] ?? '';

        // Lưu nội dung tự luận
        $stmt = $this->con->prepare("INSERT INTO traloi_tuluan (makq, macauhoi, noidung) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $makq, $macauhoi, $noidung);
        if (!$stmt->execute()) {
            error_log("Lỗi INSERT traloi_tuluan: " . $stmt->error);
            $index++;
            continue;
        }
        $traloi_id = $this->con->insert_id;

        // === LẤY TẤT CẢ ẢNH CÓ KEY: essay_{$index}_image_ ===
        foreach ($_POST as $postKey => $base64String) {
            if (strpos($postKey, "essay_{$index}_image_") === 0 && !empty($base64String)) {
                $imgBinary = base64_decode($base64String);
                if ($imgBinary === false) continue;

                $stmt2 = $this->con->prepare("INSERT INTO hinhanh_traloi_tuluan (traloi_id, hinhanh) VALUES (?, ?)");
                $null = NULL;
                $stmt2->bind_param("ib", $traloi_id, $null);
                $stmt2->send_long_data(1, $imgBinary);
                $stmt2->execute();
            }
        }

        $index++;
    }
}
    public function tookTheExam($made)
    {
        $sql = "select * from ketqua kq join nguoidung nd on kq.manguoidung = nd.id where kq.made = '$made'";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getExamineeByGroup($made, $manhom)
    {
        $sql = "SELECT KQ.*, email, hoten, avatar FROM ketqua KQ, nguoidung ND, chitietnhom CTN WHERE KQ.manguoidung = ND.id AND CTN.manguoidung = ND.id AND KQ.made = $made AND CTN.manhom = $manhom";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    // Lấy ra điểm tất cả đề thi của nhóm học phần để xuất file Excel
    public function getMarkOfAllTest($manhom)
    {
        // Lấy danh sách đề thi
        $sql_giaodethi = "SELECT dethi.made,tende FROM giaodethi,dethi WHERE manhom = $manhom AND giaodethi.made = dethi.made";
        $result_giaodethi = mysqli_query($this->con, $sql_giaodethi);
        $arr_dethi = array();
        while ($row = mysqli_fetch_assoc($result_giaodethi)) {
            $arr_dethi[] = $row;
        }

        // Lấy danh sách sinh viên
        $sql_sinhvien = "SELECT id,hoten FROM nguoidung, chitietnhom WHERE nguoidung.id = chitietnhom.manguoidung AND chitietnhom.manhom = $manhom";
        $result_sinhvien = mysqli_query($this->con, $sql_sinhvien);
        $arr_sinhvien = array();
        while ($row = mysqli_fetch_assoc($result_sinhvien)) {
            $arr_sinhvien[] = $row;
        }

        // Lấy bảng điểm
        $arr_ketqua = array();
        foreach ($arr_dethi as $dethi) {
            $arr_ketqua[$dethi['made']] = $this->getMarkOfOneTest($manhom, $dethi['made']);
        }

        // Xử lý header
        $header = array("Mã sinh viên", "Tên sinh viên");
        foreach ($arr_dethi as $dethi) {
            $header[] = $dethi['tende'];
        }

        // Xử lý mảng
        $arr_result = array($header);
        for ($i = 0;$i < count($arr_sinhvien);$i++) {
            $row = array($arr_sinhvien[$i]['id'],$arr_sinhvien[$i]['hoten']);
            for ($j = 0; $j < count($arr_dethi); $j++) {
                array_push($row, $arr_ketqua[$arr_dethi[$j]['made']][$i]['diemthi']);
            }
            $arr_result[] = $row;
        }

        return $arr_result;
    }

    public function getMarkOfOneTest($manhom, $made)
    {
        $sql = "SELECT DISTINCT giaodethi.made,chitietnhom.manguoidung,ketqua.diemthi
        FROM giaodethi, chitietnhom LEFT JOIN ketqua ON chitietnhom.manguoidung = ketqua.manguoidung AND ketqua.made = $made 
        WHERE giaodethi.manhom = chitietnhom.manhom AND giaodethi.manhom = $manhom AND giaodethi.made = $made";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    // Lấy thông tin đề thi, kết quả của sinh viên để xuất file PDF
    public function getInfoPrintPdf($makq)
    {
        $sql = "SELECT DISTINCT ketqua.made, tende, tenmonhoc, mamonhoc, thoigianthi, manguoidung, hoten, socaudung,(socaude + socautb + socaukho) AS tongsocauhoi , diemthi
        FROM chitietketqua, ketqua, dethi, monhoc, nguoidung
        WHERE chitietketqua.makq = '$makq' AND chitietketqua.makq = ketqua.makq AND ketqua.manguoidung = nguoidung.id AND ketqua.made = dethi.made AND dethi.monthi = monhoc.mamonhoc";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }
    public function countQuestionsByMakq($makq)
    {
        $makq = intval($makq);
        if ($makq <= 0) {
            return 0;
        }
        $sql = "SELECT COUNT(*) as cnt FROM chitietketqua WHERE makq = '$makq'";
        $res = mysqli_query($this->con, $sql);
        if (!$res) {
            error_log("KetQuaModel::countQuestionsByMakq SQL error: " . mysqli_error($this->con));
            return 0;
        }
        $row = mysqli_fetch_assoc($res);
        return isset($row['cnt']) ? (int)$row['cnt'] : 0;
    }

    // Lấy điểm để thống kê
    public function getStatictical($made, $manhom)
    {
        $nhomm = $manhom != 0 ? "AND chitietnhom.manhom = $manhom" : "";
        $sql = "SELECT chitietnhom.manguoidung, ketqua.manguoidung AS mandkq, makq, ketqua.made, diemthi
        FROM chitietnhom
        JOIN giaodethi ON chitietnhom.manhom = giaodethi.manhom
        LEFT JOIN ketqua ON ketqua.manguoidung = chitietnhom.manguoidung AND ketqua.made = '$made'
        WHERE giaodethi.made = '$made' $nhomm";
        $result = mysqli_query($this->con, $sql);
        $diemthi = array_fill(0, 10, 0);
        $tongdiem = 0;
        $soluong = 0;
        $max = 0;
        $chuanop = 0;
        $khongthi = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['diemthi'] != null) {
                $tongdiem += $row['diemthi'];
                $soluong++;
                $index = ceil($row['diemthi']) > 0 ? ceil($row['diemthi']) - 1 : 0;
                $diemthi[$index]++;
                if ($row['diemthi'] > $max) {
                    $max = $row['diemthi'];
                }
            } else {
                if ($row['mandkq'] != null) {
                    $chuanop++;
                } else {
                    $khongthi++;
                }
            }
        }
        $rs = array(
            "diem_trung_binh" => $soluong != 0 ? round($tongdiem / $soluong, 2) : 0,
            "da_nop_bai" => $soluong,
            "chua_nop_bai" => $chuanop,
            "khong_thi" => $khongthi,
            "diem_cao_nhat" => $max,
            "thong_ke_diem" => $diemthi
        );
        return $rs;
    }

    public function getQueryAddColumnFirstname($original_query, $filter, $input, $args, $order)
    {
        $from_index = strpos($original_query, "FROM");
        $select_string = substr($original_query, 0, $from_index) . ", SUBSTRING_INDEX(hoten, ' ', -1) AS firstname";
        $from_string = substr($original_query, $from_index);
        $query = "$select_string $from_string";
        return $query;
    }

    public function getListAbsentFromTest($filter, $input, $args)
    {
        if (is_array($args['manhom'])) {
            $listGroup = implode(", ", $args['manhom']);
        } else {
            $listGroup = $args['manhom'];
        }
        $query = "SELECT KQ.makq, KQ.made, CTN.manguoidung, KQ.diemthi, KQ.thoigianvaothi, KQ.thoigianlambai, KQ.socaudung, KQ.solanchuyentab, email, hoten, avatar FROM chitietnhom CTN JOIN nguoidung ND ON ND.id = CTN.manguoidung LEFT JOIN ketqua KQ ON CTN.manguoidung = KQ.manguoidung AND KQ.made = ".$args['made']." WHERE KQ.made IS NULL AND CTN.manhom IN ($listGroup)";
        return $query;
    }

    public function getQueryAll($filter, $input, $args)
    {
        $count_only = $args['count_only'] ?? false;

        $absent_query = $this->getListAbsentFromTest($filter, $input, $args);
        $query = "SELECT DISTINCT KQ.*, email, hoten, avatar FROM ketqua KQ, nguoidung ND, chitietnhom CTN WHERE KQ.manguoidung = ND.id AND CTN.manguoidung = ND.id AND KQ.made = ".$args['made'];
        if (is_array($args['manhom'])) {
            $list = implode(", ", $args['manhom']);
            $query .= " AND CTN.manhom IN ($list)";
        } else {
            $query .= " AND CTN.manhom = ".$args['manhom'];
        }
        $present_query = $query;
        $query = "($present_query) UNION ($absent_query)";

        // Bỏ ORDER nếu đang đếm
        $order_by = "";
        if (!$count_only) {
            $order_by = "ORDER BY manguoidung ASC";
            if (isset($args["custom"]["function"])) {
                $function = $args["custom"]["function"];
                switch ($function) {
                    case "sort":
                        $column = $args["custom"]["column"];
                        $order = $args["custom"]["order"];
                        switch ($column) {
                            case "manguoidung":
                            case "diemthi":
                            case "thoigianvaothi":
                            case "thoigianlambai":
                            case "solanchuyentab":
                                $order_by = "ORDER BY $column $order";
                                break;
                            case "hoten":
                                $present_query = $this->getQueryAddColumnFirstname($present_query, $filter, $input, $args, $order);
                                $absent_query = $this->getQueryAddColumnFirstname($absent_query, $filter, $input, $args, $order);
                                $query = "($present_query) UNION ($absent_query)";
                                $order_by = "ORDER BY firstname $order";
                                break;
                            default:
                        }
                        break;
                    default:
                }
            }
        }

        if ($input) {
            $query = "SELECT * FROM ($query) AS combined_results WHERE (hoten LIKE N'%{$input}%' OR manguoidung LIKE '%{$input}%')";
        }

        $query .= " $order_by";

        return $query;
    }

    // Tìm kiếm & phân trang & sắp xếp
    public function getQuery($filter, $input, $args)
    {
        if ($filter == "all") {
            return $this->getQueryAll($filter, $input, $args);
        }
        if ($filter == "absent") {
            $query = $this->getListAbsentFromTest($filter, $input, $args);
        } else {
            $query = "SELECT DISTINCT KQ.*, email, hoten, avatar FROM ketqua KQ, nguoidung ND, chitietnhom CTN WHERE KQ.manguoidung = ND.id AND CTN.manguoidung = ND.id AND KQ.made = ".$args['made'];
            switch ($filter) {
                case "present":
                    $query .= " AND diemthi IS NOT NULL";
                    break;
                case "interrupted":
                    $query .= " AND ISNULL(diemthi)";
                    break;
                default:
            }
            if (is_array($args['manhom'])) {
                $list = implode(", ", $args['manhom']);
                $query .= " AND CTN.manhom IN ($list)";
            } else {
                $query .= " AND CTN.manhom = ".$args['manhom'];
            }
        }
        if ($input) {
            $query .= " AND (hoten LIKE N'%{$input}%' OR CTN.manguoidung LIKE '%{$input}%')";
        }
        if (isset($args["custom"]["function"])) {
            $function = $args["custom"]["function"];
            switch ($function) {
                case "sort":
                    $column = $args["custom"]["column"];
                    $order = $args["custom"]["order"];
                    switch ($column) {
                        case "manguoidung":
                        case "diemthi":
                        case "thoigianvaothi":
                        case "thoigianlambai":
                        case "solanchuyentab":
                            $query .= " ORDER BY $column $order";
                            break;
                        case "hoten":
                            $query = $this->getQueryAddColumnFirstname($query, $filter, $input, $args, $order);
                            $query .= " ORDER BY firstname $order";
                            break;
                        default:
                    }
                    break;
                default:
            }
        } else {
            $query .= " ORDER BY KQ.manguoidung ASC";
        }
        return $query;
    }

    public function getTestScoreGroup($made, $manhom)
    {
        $sql = "SELECT ds.manguoidung,ds.hoten,kqt.diemthi,kqt.thoigianvaothi,kqt.thoigianlambai,kqt.socaudung,kqt.solanchuyentab FROM (SELECT ctn.manguoidung,nd.hoten FROM chitietnhom ctn JOIN nguoidung nd ON ctn.manguoidung=nd.id WHERE ctn.manhom=$manhom) ds LEFT JOIN 
        (SELECT kq.manguoidung,kq.diemthi,kq.thoigianvaothi,kq.thoigianlambai,kq.socaudung,kq.solanchuyentab FROM ketqua kq JOIN giaodethi gdt ON kq.made=gdt.made WHERE gdt.made=$made AND gdt.manhom=$manhom) kqt ON ds.manguoidung=kqt.manguoidung";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getTestAll($made, $ds)
    {
        $list = implode(", ", $ds);
        $sql = "(SELECT KQ.makq, KQ.made, CTN.manguoidung, KQ.diemthi, KQ.thoigianvaothi, KQ.thoigianlambai, KQ.socaudung, KQ.solanchuyentab, email, hoten, avatar FROM chitietnhom CTN JOIN nguoidung ND ON ND.id = CTN.manguoidung LEFT JOIN ketqua KQ ON CTN.manguoidung = KQ.manguoidung AND KQ.made = $made WHERE KQ.made IS NULL AND CTN.manhom IN ($list))
        UNION
        (SELECT DISTINCT KQ.*, email, hoten, avatar FROM ketqua KQ, nguoidung ND, chitietnhom CTN WHERE KQ.manguoidung = ND.id AND CTN.manguoidung = ND.id AND KQ.made = $made  AND CTN.manhom IN ($list))
        ORDER BY manguoidung ASC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function chuyentab($made, $id) //hàm check xem đề thi đó có quy định rằng nếu có chuyển tab thì nộp bài ngay lập tức
    {
        // 1. Lấy kết quả làm bài của người dùng với mã đề tương ứng
        $sql_dethi = "SELECT * FROM ketqua WHERE made='$made' AND manguoidung='$id'";
        $result = mysqli_query($this->con, $sql_dethi);
        $data_dethi = mysqli_fetch_assoc($result);

        // 2. Tăng số lần chuyển tab lên 1
        $solan = $data_dethi['solanchuyentab'];
        $solan++;

        // 3. Cập nhật lại số lần chuyển tab vào CSDL
        $sql_update = "UPDATE ketqua SET solanchuyentab = '$solan' WHERE made='$made' AND manguoidung='$id'";
        $result_update = mysqli_query($this->con, $sql_update);

        // 4. Lấy quy định của đề thi về việc có cho nộp bài khi chuyển tab hay không
        $sql_check = "SELECT * FROM dethi where made = '$made'";
        $result_check = mysqli_query($this->con, $sql_check);
        $data_check = mysqli_fetch_assoc($result_check);
        // 5. Trả về cờ "nopbaichuyentab" (1: nộp bài khi chuyển tab, 0: không) rồi chuyền ngược về hàm gọi ở đây là test.php trong controller
        return $data_check['nopbaichuyentab'];
    }
}
