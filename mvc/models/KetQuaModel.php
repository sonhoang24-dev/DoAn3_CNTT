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
        error_log("Test::submit called with made=$made, user=$nguoidung");
        error_log("Raw POST: " . print_r($_POST, true));
        error_log("FILES: " . print_r($_FILES, true));

        // 1. Lấy makq và thời gian vào thi
        $stmt = $this->con->prepare("SELECT makq, thoigianvaothi FROM ketqua WHERE made = ? AND manguoidung = ? AND diemthi IS NULL");
        if (!$stmt) {
            error_log("Prepare SELECT ketqua failed: " . $this->con->error);
            return false;
        }
        $stmt->bind_param("is", $made, $nguoidung);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if (!$row) {
            error_log("No ketqua found or already submitted.");
            return false;
        }

        $makq = $row['makq'];
        $thoigianvaothi = strtotime($row['thoigianvaothi']);
        $thoigian_str = is_array($thoigian) ? ($_POST['thoigian'] ?? date('Y-m-d H:i:s')) : $thoigian;
        $thoigianlambai = max(strtotime($thoigian_str) - $thoigianvaothi, 0);

        // 2. Xử lý trắc nghiệm
        $listCauTraLoi = json_decode($_POST['listCauTraLoi'] ?? '[]', true);
        $socaudung_tn = 0;

        // Tính tổng số câu trắc nghiệm trong đề (lấy từ chitietdethi JOIN cauhoi)
        $total_mcq = 0;
        $stmtTotal = $this->con->prepare("SELECT SUM(CASE WHEN ch.loai = 'mcq' THEN 1 ELSE 0 END) AS total_mcq FROM chitietdethi ctd JOIN cauhoi ch ON ctd.macauhoi = ch.macauhoi WHERE ctd.made = ?");
        if ($stmtTotal) {
            $stmtTotal->bind_param('i', $made);
            $stmtTotal->execute();
            $resTotal = $stmtTotal->get_result()->fetch_assoc();
            $stmtTotal->close();
            $total_mcq = isset($resTotal['total_mcq']) ? (int)$resTotal['total_mcq'] : 0;
        }

        // Nếu không tìm được số lượng câu trắc nghiệm, fallback về 0
        $tongcau_tn = max(0, $total_mcq);

        // Tạo map loại câu (loai) cho các câu trong listCauTraLoi để phân biệt reading vs mcq
        $questionTypes = [];
        $macList = array_column($listCauTraLoi, 'macauhoi');
        $macList = array_map('intval', $macList);
        if (!empty($macList)) {
            $in = implode(',', $macList);
            $sqlTypes = "SELECT macauhoi, loai FROM cauhoi WHERE macauhoi IN ($in)";
            $resTypes = mysqli_query($this->con, $sqlTypes);
            if ($resTypes) {
                while ($r = mysqli_fetch_assoc($resTypes)) {
                    $questionTypes[(int)$r['macauhoi']] = $r['loai'];
                }
            }
        }

        $socaudung_reading = 0;

        foreach ($listCauTraLoi as $ans) {
            $macauhoi = intval($ans['macauhoi']);
            $cautraloi = intval($ans['cautraloi'] ?? 0);

            // Lưu đáp án (kể cả khi không chọn -> lưu 0) để ghi đè đáp án cũ
            // Kiểm tra đúng chỉ khi có chọn (không kiểm khi = 0)
            if ($cautraloi != 0) {
                $check = $this->con->prepare("SELECT 1 FROM cautraloi WHERE macauhoi = ? AND macautl = ? AND ladapan = 1");
                if ($check) {
                    $check->bind_param("ii", $macauhoi, $cautraloi);
                    $check->execute();
                    $resCheck = $check->get_result();
                    if ($resCheck->num_rows > 0) {
                        // Phân loại: nếu câu là reading thì cộng vào bộ đếm reading, ngược lại là mcq
                        if (isset($questionTypes[$macauhoi]) && $questionTypes[$macauhoi] === 'reading') {
                            $socaudung_reading++;
                        } else {
                            $socaudung_tn++;
                        }
                    }
                    $check->close();
                }
            }

            // Ghi đáp án vào chitietketqua luôn, dùng ON DUPLICATE KEY UPDATE để ghi đè
            $this->luuDapAnTracNghiem($makq, $macauhoi, $cautraloi);
        }

        // Lấy điểm tối đa cho phần trắc nghiệm và đọc hiểu từ dethi
        $mcq_total_points = 0.0;
        $reading_total_points = 0.0;
        $stmtMcq = $this->con->prepare("SELECT diem_tracnghiem, diem_dochieu FROM dethi WHERE made = ? LIMIT 1");
        if ($stmtMcq) {
            $stmtMcq->bind_param('i', $made);
            $stmtMcq->execute();
            $resMcq = $stmtMcq->get_result()->fetch_assoc();
            $stmtMcq->close();
            if (isset($resMcq['diem_tracnghiem'])) {
                $mcq_total_points = floatval($resMcq['diem_tracnghiem']);
            }
            if (isset($resMcq['diem_dochieu'])) {
                $reading_total_points = floatval($resMcq['diem_dochieu']);
            }
        }

        // Nếu không có cấu hình (<=0) thì fallback về 10 cho trắc nghiệm để giữ tương thích cũ
        if ($mcq_total_points <= 0) {
            $mcq_total_points = 10.0;
        }

        // Điểm mỗi câu trắc nghiệm = tổng điểm trắc nghiệm / tổng số câu trắc nghiệm
        $per_mcq_point = ($tongcau_tn > 0) ? ($mcq_total_points / $tongcau_tn) : 0;

        // Tổng điểm trắc nghiệm = số câu đúng * điểm mỗi câu
        $diem_tracnghiem = round($per_mcq_point * $socaudung_tn, 2);

        // 3. Xử lý tự luận
        $this->xuLyTuLuan($makq);

        // 4. Kiểm tra đề có câu tự luận
        $stmtCheckTL = $this->con->prepare("
        SELECT COUNT(*) AS total_tl,
               SUM(CASE WHEN ch.loai = 'reading' THEN 1 ELSE 0 END) AS total_reading
        FROM chitietdethi ctd
        JOIN cauhoi ch ON ctd.macauhoi = ch.macauhoi
        WHERE ctd.made = ?
    ");
        $total_reading = 0;
        if ($stmtCheckTL) {
            $stmtCheckTL->bind_param("i", $made);
            $stmtCheckTL->execute();
            $resTL = $stmtCheckTL->get_result()->fetch_assoc();
            $stmtCheckTL->close();

            $trangthai_tuluan = ($resTL['total_tl'] - $resTL['total_reading'] == 0) ? 'Đã chấm' : 'Chưa chấm';
            $total_reading = (int)$resTL['total_reading'];
        } else {
            $trangthai_tuluan = 'Chưa chấm';
        }

        // 5. Lấy/ Tính điểm đọc hiểu nếu có
        $diem_dochieu = 0;
        if ($total_reading > 0) {
            // Nếu frontend gửi điểm đọc hiểu (gv chấm thủ công), ưu tiên dùng nó
            if (isset($_POST['diem_dochieu']) && is_numeric($_POST['diem_dochieu'])) {
                $diem_dochieu = floatval($_POST['diem_dochieu']);
            } else {
                // Tính tự động: chia đều reading_total_points cho tổng câu reading
                if (!isset($reading_total_points) || $reading_total_points <= 0) {
                    $reading_total_points = 0.0;
                }
                $per_reading_point = ($total_reading > 0) ? ($reading_total_points / $total_reading) : 0;
                $diem_dochieu = round($per_reading_point * ($socaudung_reading ?? 0), 2);
            }
        }

        // 6. Tính tổng điểm
        $diemthi = $diem_tracnghiem + $diem_dochieu;

        // 7. Cập nhật ketqua
        $stmt = $this->con->prepare("
        UPDATE ketqua 
        SET diemthi = ?, thoigianlambai = ?, socaudung = ?, trangthai = 'Đã nộp', trangthai_tuluan = ?
        WHERE makq = ?
    ");
        if (!$stmt) {
            error_log("Prepare UPDATE ketqua failed: " . $this->con->error);
            return false;
        }
        $stmt->bind_param("diisi", $diemthi, $thoigianlambai, $socaudung_tn, $trangthai_tuluan, $makq);
        $stmt->execute();
        $stmt->close();

        return true;
    }


    private function xuLyTuLuan($makq)
    {
        // Lấy danh sách tất cả các câu tự luận được gửi lên
        $essays = [];
        foreach ($_POST as $key => $value) {
            if (preg_match('/^essay_(\d+)_macauhoi$/', $key, $matches)) {
                $index = $matches[1];
                $essays[$index] = [
                    'macauhoi' => intval($_POST["essay_{$index}_macauhoi"]),
                    'noidung'  => $_POST["essay_{$index}_noidung"] ?? '',
                    'images'   => []
                ];

                // Thu thập tất cả ảnh của câu này
                foreach ($_POST as $imgKey => $base64) {
                    if (strpos($imgKey, "essay_{$index}_image_") === 0 && !empty($base64)) {
                        $essays[$index]['images'][] = $base64;
                    }
                }
            }
        }

        // Bây giờ xử lý từng câu một cách an toàn
        foreach ($essays as $index => $essay) {
            $macauhoi = $essay['macauhoi'];
            $noidung  = $essay['noidung'];

            // 1. Lưu nội dung trả lời tự luận
            $stmt = $this->con->prepare("INSERT INTO traloi_tuluan (makq, macauhoi, noidung) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE noidung = VALUES(noidung)");
            if (!$stmt) {
                error_log("Prepare INSERT traloi_tuluan failed: " . $this->con->error);
                continue;
            }
            $stmt->bind_param("iis", $makq, $macauhoi, $noidung);
            if (!$stmt->execute()) {
                error_log("Execute traloi_tuluan failed: " . $stmt->error);
                $stmt->close();
                continue;
            }
            $traloi_id = $this->con->insert_id ?: $this->getTraloiId($makq, $macauhoi); // phòng trường hợp đã tồn tại
            $stmt->close();

            // 2. Lưu tất cả ảnh của câu này
            foreach ($essay['images'] as $base64String) {
                $imgBinary = base64_decode($base64String);
                if ($imgBinary === false) {
                    continue;
                }

                $stmt2 = $this->con->prepare("INSERT INTO hinhanh_traloi_tuluan (traloi_id, hinhanh) VALUES (?, ?)");
                if (!$stmt2) {
                    continue;
                }

                $null = null;
                $stmt2->bind_param("ib", $traloi_id, $null);
                $stmt2->send_long_data(1, $imgBinary); // quan trọng: stream ảnh lớn
                $stmt2->execute();
                $stmt2->close();
            }
        }
    }

    // Hàm phụ trợ nếu cần lấy lại traloi_id khi đã tồn tại
    private function getTraloiId($makq, $macauhoi)
    {
        $stmt = $this->con->prepare("SELECT id FROM traloi_tuluan WHERE makq = ? AND macauhoi = ?");
        $stmt->bind_param("ii", $makq, $macauhoi);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['id'] ?? 0;
    }


    public function kiemTraDiemTuluan($makq, $diemCham)
    {
        $sql = "
            SELECT d.diem_tuluan AS diem_tuluan_max
            FROM ketqua k
            JOIN dethi d ON k.made = d.made
            WHERE k.makq = ?
            LIMIT 1
        ";

        $stmt = $this->con->prepare($sql);
        if (!$stmt) {
            throw new Exception("Lỗi prepare: " . $this->con->error);
        }

        $stmt->bind_param("i", $makq);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if (!$row) {
            return false; // makq không tồn tại
        }

        // So sánh điểm chấm với điểm tối đa
        return $diemCham <= (float)$row['diem_tuluan_max'];
    }

    // Hàm lưu đáp án trắc nghiệm
    private function luuDapAnTracNghiem($makq, $macauhoi, $dapanchon)
    {
        $stmt = $this->con->prepare("
        INSERT INTO chitietketqua (makq, macauhoi, dapanchon)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE dapanchon = VALUES(dapanchon)
    ");
        if (!$stmt) {
            error_log("Prepare chitietketqua failed: " . $this->con->error);
            return false;
        }
        $stmt->bind_param("iii", $makq, $macauhoi, $dapanchon);
        $stmt->execute();
        $stmt->close();
        return true;
    }


    // Lưu điểm TL
    public function luuDiemTuLuan($makq, $diemTong, $diemTungCau = [])
    {
        $makq     = intval($makq);
        $diemTong = round(floatval($diemTong), 2);

        if ($makq <= 0) {
            return ['success' => false, 'message' => 'Mã kết quả không hợp lệ'];
        }

        // --- Kiểm tra điểm tự luận với điểm tối đa ---
        $sql = "
        SELECT d.diem_tuluan AS diem_tuluan_max
        FROM ketqua k
        JOIN dethi d ON k.made = d.made
        WHERE k.makq = ?
        LIMIT 1
    ";
        $stmtCheck = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmtCheck, "i", $makq);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        $rowCheck = mysqli_fetch_assoc($resultCheck);
        mysqli_stmt_close($stmtCheck);

        if (!$rowCheck) {
            return ['success' => false, 'message' => 'Kết quả không tồn tại'];
        }

        $diemMax = (float)$rowCheck['diem_tuluan_max'];
        if ($diemTong > $diemMax) {
            return [
                'success' => false,
                'message' => "Điểm chấm <span style='color:green; font-size:1.5em; font-weight:bold;'>$diemTong </span>điểm vượt quá tối đa <span style='color:red; font-size:1.5em; font-weight:bold;'>$diemMax</span> điểm cho phần tự luận"
            ];
        }


        // --- Bắt đầu transaction ---
        mysqli_begin_transaction($this->con);

        try {
            // BƯỚC 1: Lưu điểm từng câu (gộp 1 câu lệnh duy nhất)
            if (!empty($diemTungCau) && is_array($diemTungCau)) {
                $values = [];
                $params = [];
                $types  = '';

                foreach ($diemTungCau as $macauhoi => $diem) {
                    $macauhoi = intval($macauhoi);
                    $diem     = round(floatval($diem), 2);

                    if ($macauhoi > 0) {
                        $values[] = "(?, ?, ?)";
                        $params[] = $makq;
                        $params[] = $macauhoi;
                        $params[] = $diem;
                        $types .= 'iid';
                    }
                }

                if (!empty($values)) {
                    $sql = "INSERT INTO cham_tuluan (makq, macauhoi, diem) VALUES "
                         . implode(',', $values)
                         . " ON DUPLICATE KEY UPDATE diem = VALUES(diem)";

                    $stmt = mysqli_prepare($this->con, $sql);
                    mysqli_stmt_bind_param($stmt, $types, ...$params);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
            }

            // BƯỚC 2: Cập nhật tổng điểm vào ketqua
            $trangthai = ($diemTong > 0) ? 'Đã chấm' : 'Chưa chấm';
            $sql = "UPDATE ketqua SET diem_tuluan = ?, trangthai_tuluan = ? WHERE makq = ?";
            $stmt = mysqli_prepare($this->con, $sql);
            mysqli_stmt_bind_param($stmt, "dsi", $diemTong, $trangthai, $makq);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            mysqli_commit($this->con);

        } catch (Exception $e) {
            mysqli_rollback($this->con);
            return ['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()];
        }

        return [
            'success' => true,
            'message' => 'Lưu điểm tự luận thành công!',
            'diem_tuluan' => $diemTong
        ];
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
        // Use an explicit, ordered column list so UNION branches match exactly
        $cols = "KQ.makq, KQ.made, KQ.manguoidung, KQ.diemthi, KQ.diem_tuluan, KQ.trangthai, KQ.trangthai_tuluan, KQ.thoigianvaothi, KQ.thoigianlambai, KQ.socaudung, KQ.solanchuyentab, ND.email, ND.hoten, ND.avatar";
        $query = "SELECT DISTINCT $cols FROM ketqua KQ JOIN nguoidung ND ON KQ.manguoidung = ND.id JOIN chitietnhom CTN ON CTN.manguoidung = ND.id WHERE KQ.made = " . $args['made'];
        if (is_array($args['manhom'])) {
            $list = implode(", ", $args['manhom']);
            $query .= " AND CTN.manhom IN ($list)";
        } else {
            $query .= " AND CTN.manhom = ".$args['manhom'];
        }
        $present_query = $query;
        // Ensure absent_query returns the same column order/aliases as present_query
        // absent_query from getListAbsentFromTest currently returns: makq, made, manguoidung, diemthi, thoigianvaothi, thoigianlambai, socaudung, solanchuyentab, email, hoten, avatar
        // We need to align it with $cols (including diem_tuluan, trangthai, trangthai_tuluan which will be NULL for absent rows)
        if (strpos($absent_query, 'SELECT') === 0) {
            // rewrite absent_query to replace its SELECT ... FROM clause with the explicit columns + FROM
            $absent_query = preg_replace(
                '/SELECT\s+.*?\s+FROM\s+/is',
                'SELECT KQ.makq, KQ.made, CTN.manguoidung, KQ.diemthi, KQ.diem_tuluan, KQ.trangthai, KQ.trangthai_tuluan, KQ.thoigianvaothi, KQ.thoigianlambai, KQ.socaudung, KQ.solanchuyentab, ND.email, ND.hoten, ND.avatar FROM ',
                $absent_query,
                1
            );
        }

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
            $query = "SELECT DISTINCT 
            KQ.*, 
            ND.email, 
            ND.hoten, 
            ND.avatar,
            DT.thoigianbatdau,
            DT.thoigianketthuc
          FROM ketqua KQ
          JOIN nguoidung ND ON KQ.manguoidung = ND.id
          JOIN chitietnhom CTN ON CTN.manguoidung = ND.id
          JOIN dethi DT ON DT.made = KQ.made
          WHERE KQ.made = ".$args['made'];

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
        $cols = "KQ.makq, KQ.made, CTN.manguoidung, KQ.diemthi, KQ.diem_tuluan, KQ.trangthai, KQ.trangthai_tuluan, KQ.thoigianvaothi, KQ.thoigianlambai, KQ.socaudung, KQ.solanchuyentab, ND.email, ND.hoten, ND.avatar";
        $sql = "(SELECT $cols FROM chitietnhom CTN JOIN nguoidung ND ON ND.id = CTN.manguoidung LEFT JOIN ketqua KQ ON CTN.manguoidung = KQ.manguoidung AND KQ.made = $made WHERE KQ.made IS NULL AND CTN.manhom IN ($list))
        UNION
        (SELECT DISTINCT $cols FROM ketqua KQ JOIN nguoidung ND ON KQ.manguoidung = ND.id JOIN chitietnhom CTN ON CTN.manguoidung = ND.id WHERE KQ.made = $made AND CTN.manhom IN ($list))
        ORDER BY manguoidung ASC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function chuyentab($made, $id)
    {
        $sql_dethi = "SELECT * FROM ketqua WHERE made='$made' AND manguoidung='$id'";
        $result = mysqli_query($this->con, $sql_dethi);
        $data_dethi = mysqli_fetch_assoc($result);

        $solan = $data_dethi['solanchuyentab'];
        $solan++;

        $sql_update = "UPDATE ketqua SET solanchuyentab = '$solan' WHERE made='$made' AND manguoidung='$id'";
        $result_update = mysqli_query($this->con, $sql_update);

        $sql_check = "SELECT * FROM dethi where made = '$made'";
        $result_check = mysqli_query($this->con, $sql_check);
        $data_check = mysqli_fetch_assoc($result_check);
        return $data_check['nopbaichuyentab'];
    }
}
