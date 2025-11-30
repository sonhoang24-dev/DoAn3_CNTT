<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include "./mvc/models/CauTraLoiModel.php";
include "./mvc/models/CauHoiModel.php";

class DeThiModel extends DB
{
    public function executeQuery($sql)
    {
        return mysqli_query($this->con, $sql);
    }

    public function insertAndGetId($sql)
    {
        mysqli_query($this->con, $sql);
        return mysqli_insert_id($this->con);
    }



    private function checkQuestionAvailability($monhoc, $chuong, $loaicauhoi, $socaude, $socautb, $socaukho)
    {
        $monhoc = mysqli_real_escape_string($this->con, $monhoc);
        $counts = [];

        // Duyệt từng loại câu hỏi được chọn
        foreach ($loaicauhoi as $type) {
            foreach ([1 => 'socaude',2 => 'socautb',3 => 'socaukho'] as $level => $qty_field) {
                $qty = $$qty_field;
                if ($qty <= 0) {
                    continue;
                }

                $sql = "SELECT COUNT(*) as count FROM cauhoi ch
                    WHERE ch.mamonhoc='$monhoc' 
                    AND ch.dokho=$level 
                    AND ch.loai='$type' 
                    AND ch.trangthai!=0 
                    AND (";

                $chuongArr = [];
                foreach ($chuong as $c) {
                    $chuongArr[] = "ch.machuong='".mysqli_real_escape_string($this->con, $c)."'";
                }
                $sql .= implode(' OR ', $chuongArr).")";

                $res = mysqli_query($this->con, $sql);
                if (!$res) {
                    die("Lỗi SQL: ".mysqli_error($this->con));
                }

                $count = mysqli_fetch_assoc($res)['count'];
                $counts[$type][$level] = $count;

                if ($count < $qty) {
                    return [
                        'valid' => false,
                        'message' => "Không đủ câu hỏi loại $type mức độ $level: yêu cầu $qty, hiện có $count"
                    ];
                }
            }
        }

        return ['valid' => true, 'counts' => $counts];
    }
   public function create(
    $monthi,
    $nguoitao,
    $tende,
    $thoigianthi,
    $thoigianbatdau,
    $thoigianketthuc,
    $hienthibailam,
    $xemdiemthi,
    $xemdapan,
    $daocauhoi,
    $daodapan,
    $tudongnop,
    $loaide,
    $socau,
    $chuong,
    $nhom,
    $loaicauhoi,
    $diem_tracnghiem = 0,
    $diem_tuluan = 0,
    $diem_dochieu = 0
) {
    try {
        // ===== Normalize =====
        $monthi = trim($monthi);
        $nguoitao = trim($nguoitao);
        $tende = trim($tende);

        $thoigianthi = intval($thoigianthi);
        $hienthibailam = intval($hienthibailam);
        $xemdiemthi = intval($xemdiemthi);
        $xemdapan = intval($xemdapan);
        $daocauhoi = intval($daocauhoi);
        $daodapan = intval($daodapan);
        $tudongnop = intval($tudongnop);
        $loaide = intval($loaide);

        $chuong = is_array($chuong) ? array_map('intval', $chuong) : [];
        $nhom = is_array($nhom) ? array_map('intval', $nhom) : [];
        $loaicauhoi = is_array($loaicauhoi) ? array_filter(array_map('trim', $loaicauhoi)) : ['mcq'];

        // ===== Init số câu =====
        $mcq_de = $mcq_tb = $mcq_kho = 0;
        $essay_de = $essay_tb = $essay_kho = 0;
        $reading_de = $reading_tb = $reading_kho = 0;

        if (is_array($socau)) {
            foreach ($socau as $type => $levels) {
                $de = intval($levels['de'] ?? 0);
                $tb = intval($levels['tb'] ?? 0);
                $kho = intval($levels['kho'] ?? 0);

                if ($type === 'mcq') {
                    $mcq_de = $de;  $mcq_tb = $tb;  $mcq_kho = $kho;
                } elseif ($type === 'essay') {
                    $essay_de = $de; $essay_tb = $tb; $essay_kho = $kho;
                } elseif ($type === 'reading') {
                    $reading_de = $de; $reading_tb = $tb; $reading_kho = $kho;
                }
            }
        }

        $trangthai = 1;

        // ===== INSERT =====
        $sql = "INSERT INTO dethi 
        (monthi, nguoitao, tende, thoigianthi, thoigianbatdau, thoigianketthuc,
         hienthibailam, xemdiemthi, xemdapan, troncauhoi, trondapan, nopbaichuyentab,
         loaide, mcq_de, mcq_tb, mcq_kho,
         essay_de, essay_tb, essay_kho,
         reading_de, reading_tb, reading_kho,
         diem_tracnghiem, diem_tuluan, diem_dochieu,
         trangthai)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->con));
        }

        // ===== 26 biến → 26 types =====
        $types = "sssissiiiiiiiiiiiiiiiidddi";

        mysqli_stmt_bind_param(
            $stmt,
            $types,
            $monthi,
            $nguoitao,
            $tende,
            $thoigianthi,
            $thoigianbatdau,
            $thoigianketthuc,
            $hienthibailam,
            $xemdiemthi,
            $xemdapan,
            $daocauhoi,
            $daodapan,
            $tudongnop,
            $loaide,
            $mcq_de,
            $mcq_tb,
            $mcq_kho,
            $essay_de,
            $essay_tb,
            $essay_kho,
            $reading_de,
            $reading_tb,
            $reading_kho,
            $diem_tracnghiem,
            $diem_tuluan,
            $diem_dochieu,
            $trangthai
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $made = mysqli_insert_id($this->con);
        mysqli_stmt_close($stmt);

        if ($made <= 0) {
            throw new Exception("Insert_id invalid");
        }

        // ===== Add chương/nhóm =====
        if (!empty($chuong)) $this->create_chuongdethi($made, $chuong);
        if (!empty($nhom))   $this->create_giaodethi($made, $nhom);

        // ===== Auto add câu hỏi khi tạo đề tự động =====
        if ($loaide == 1 && !empty($socau)) {
            $this->addQuestionsToAutoTest($made, $socau, $chuong, $monthi, $loaicauhoi);
        }

        return $made;

    } catch (\Exception $e) {
        error_log("create exception: " . $e->getMessage());
        return false;
    }
}



    private function addQuestionsToAutoTest($made, $socau, $chuong, $monthi, $loaicauhoi)
    {
        try {
            $cauhoiModel = new CauHoiModel($this->con);

            foreach ($socau as $type => $levels) {
                error_log("Processing type: $type, levels: " . json_encode($levels));

                foreach (['de','tb','kho'] as $level) {
                    $qty = intval($levels[$level] ?? 0);
                    if ($qty <= 0) {
                        continue;
                    }

                    $levelMap = ['de' => '1','tb' => '2','kho' => '3'];
                    $levelNum = $levelMap[$level];

                    // Nếu là reading -> lấy theo đoạn
                    if ($type === 'reading') {
                        $questions = $cauhoiModel->getReadingQuestions($chuong, $monthi, $levelNum, $qty);
                    } else {
                        // mcq / essay -> lấy bình thường
                        $questions = $cauhoiModel->getQuestions($chuong, $monthi, $levelNum, [$type], $qty);
                    }

                    if ($questions === false) {
                        throw new Exception("SQL error when fetching $type level $levelNum");
                    }

                    if (count($questions) < $qty) {
                        error_log("Warning: Chỉ lấy được " . count($questions) . " câu cho $type level $levelNum, yêu cầu $qty");
                    }

                    // Thêm câu hỏi vào chitietdethi
                    foreach ($questions as $q) {
                        $sql = "INSERT INTO chitietdethi (made, macauhoi) VALUES (?, ?)";
                        $stmt = mysqli_prepare($this->con, $sql);
                        if (!$stmt) {
                            continue;
                        }
                        mysqli_stmt_bind_param($stmt, "ii", $made, $q['macauhoi']);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                }
            }

            return true;

        } catch (\Exception $e) {
            error_log("addQuestionsToAutoTest exception: " . $e->getMessage());
            return false;
        }
    }



    // Sửa addCauHoiToDeThi để dùng đúng macauhoi
    public function addCauHoiToDeThi($made, $macauhoi)
    {
        try {
            $made = intval($made);
            $macauhoi = intval($macauhoi);

            $sql = "INSERT INTO chitietdethi(made, macauhoi) VALUES (?, ?)";
            $stmt = mysqli_prepare($this->con, $sql);

            if (!$stmt) {
                error_log("Prepare addCauHoiToDeThi failed: " . mysqli_error($this->con));
                return false;
            }

            mysqli_stmt_bind_param($stmt, "ii", $made, $macauhoi);

            if (!mysqli_stmt_execute($stmt)) {
                error_log("Execute addCauHoiToDeThi failed: " . mysqli_stmt_error($stmt) . " made=$made, macauhoi=$macauhoi");
                mysqli_stmt_close($stmt);
                return false;
            }

            mysqli_stmt_close($stmt);
            return true;

        } catch (\Exception $e) {
            error_log("addCauHoiToDeThi exception: " . $e->getMessage());
            return false;
        }
    }

    // ===== CẬP NHẬT CHƯƠNG CỦA ĐỀ TỰ ĐỘNG =====
    public function update_chuongdethi($made, $chuong)
    {
        try {
            $made = (int)$made;

            // Xóa toàn bộ chương cũ (an toàn hơn dùng prepare)
            $sql_del = "DELETE FROM dethitudong WHERE made = ?";
            $stmt_del = mysqli_prepare($this->con, $sql_del);
            if (!$stmt_del) {
                error_log("Prepare delete dethitudong failed: " . mysqli_error($this->con));
                return false;
            }
            mysqli_stmt_bind_param($stmt_del, "i", $made);
            mysqli_stmt_execute($stmt_del);
            mysqli_stmt_close($stmt_del);

            // Nếu không có chương mới → xóa xong là được
            if (empty($chuong) || !is_array($chuong)) {
                return true;
            }

            // Thêm chương mới
            return $this->create_chuongdethi($made, $chuong);

        } catch (Exception $e) {
            error_log("update_chuongdethi error: " . $e->getMessage());
            return false;
        }
    }

    // ===== CẬP NHẬT NHÓM ĐƯỢC GIAO ĐỀ =====
    public function update_giaodethi($made, $nhom)
    {
        try {
            $made = (int)$made;

            // Xóa toàn bộ nhóm cũ
            $sql_del = "DELETE FROM giaodethi WHERE made = ?";
            $stmt_del = mysqli_prepare($this->con, $sql_del);
            if (!$stmt_del) {
                error_log("Prepare delete giaodethi failed: " . mysqli_error($this->con));
                return false;
            }
            mysqli_stmt_bind_param($stmt_del, "i", $made);
            mysqli_stmt_execute($stmt_del);
            mysqli_stmt_close($stmt_del);

            // Nếu không có nhóm mới → xong
            if (empty($nhom) || !is_array($nhom)) {
                return true;
            }

            // Thêm nhóm mới
            return $this->create_giaodethi($made, $nhom);

        } catch (Exception $e) {
            error_log("update_giaodethi error: " . $e->getMessage());
            return false;
        }
    }

    // ===== TẠO MỚI CHƯƠNG CHO ĐỀ =====
    public function create_chuongdethi($made, $chuong)
    {
        try {
            $made = (int)$made;
            $chuong = array_unique(array_map('intval', $chuong)); // loại trùng + ép kiểu

            $sql = "INSERT IGNORE INTO dethitudong (made, machuong) VALUES (?, ?)";
            $stmt = mysqli_prepare($this->con, $sql);
            if (!$stmt) {
                error_log("Prepare create_chuongdethi failed: " . mysqli_error($this->con));
                return false;
            }

            foreach ($chuong as $machuong) {
                mysqli_stmt_bind_param($stmt, "ii", $made, $machuong);
                if (!mysqli_stmt_execute($stmt)) {
                    error_log("Insert dethitudong failed (made=$made, machuong=$machuong): " . mysqli_stmt_error($stmt));
                    // Không return false ngay → vẫn cố insert các cái còn lại
                }
            }

            mysqli_stmt_close($stmt);
            return true;

        } catch (Exception $e) {
            error_log("create_chuongdethi error: " . $e->getMessage());
            return false;
        }
    }

    // ===== TẠO MỚI NHÓM ĐƯỢC GIAO ĐỀ =====
    public function create_giaodethi($made, $nhom)
    {
        try {
            $made = (int)$made;
            $nhom = array_unique(array_map('intval', $nhom));

            $sql_check = "SELECT manhom FROM nhom WHERE manhom = ? AND trangthai != 0 LIMIT 1";
            $stmt_check = mysqli_prepare($this->con, $sql_check);

            $sql_insert = "INSERT IGNORE INTO giaodethi (made, manhom) VALUES (?, ?)";
            $stmt_insert = mysqli_prepare($this->con, $sql_insert);
            if (!$stmt_insert) {
                error_log("Prepare insert giaodethi failed");
                return false;
            }

            foreach ($nhom as $manhom) {
                // Kiểm tra nhóm có tồn tại và đang hoạt động
                if ($stmt_check) {
                    mysqli_stmt_bind_param($stmt_check, "i", $manhom);
                    mysqli_stmt_execute($stmt_check);
                    $result = mysqli_stmt_get_result($stmt_check);
                    if (mysqli_num_rows($result) == 0) {
                        error_log("Nhóm $manhom không tồn tại hoặc đã bị xóa");
                        continue; // bỏ qua nhóm không hợp lệ
                    }
                    mysqli_free_result($result);
                }

                // Insert
                mysqli_stmt_bind_param($stmt_insert, "ii", $made, $manhom);
                mysqli_stmt_execute($stmt_insert);
            }

            if ($stmt_check) {
                mysqli_stmt_close($stmt_check);
            }
            mysqli_stmt_close($stmt_insert);

            return true;

        } catch (Exception $e) {
            error_log("create_giaodethi error: " . $e->getMessage());
            return false;
        }
    }
    public function create_dethi_auto($made, $monhoc, $chuong, $socaude, $socautb, $socaukho, $loai_cauhoi = [])
    {
        // Lấy thông tin đề thi
        $sql_dethi = "SELECT troncauhoi, trondapan FROM dethi WHERE made = '$made'";
        $dethi_data = mysqli_fetch_assoc(mysqli_query($this->con, $sql_dethi));
        $troncauhoi = $dethi_data['troncauhoi'];
        $trondapan  = $dethi_data['trondapan'];

        $orderBy = $troncauhoi == 1 ? "ORDER BY RAND()" : "ORDER BY ch.macauhoi ASC";

        // CHUẨN HÓA CHƯƠNG
        $listChuong = implode(",", array_map(function ($c) {
            return "'" . $c . "'";
        }, $chuong));

        $data = [];

        // ----------------------------
        // 1) MCQ (luôn lấy)
        // ----------------------------
        $data = array_merge($data, $this->fetchQuestionsByType('mcq', $monhoc, $listChuong, $socaude, $socautb, $socaukho, $trondapan, $orderBy));

        // ----------------------------
        // 2) Các loại khác nếu có
        // ----------------------------
        if (in_array('essay', $loai_cauhoi)) {
            $data = array_merge($data, $this->fetchQuestionsByType('essay', $monhoc, $listChuong, $socaude, $socautb, $socaukho, $trondapan, $orderBy));
        }

        if (in_array('reading', $loai_cauhoi)) {
            // lấy block đọc hiểu
            $sql_dv = "SELECT * FROM doan_van WHERE mamonhoc='$monhoc' AND machuong IN ($listChuong) AND trangthai != 0";
            $res_dv = mysqli_query($this->con, $sql_dv);

            $reading_blocks = [];
            while ($dv = mysqli_fetch_assoc($res_dv)) {
                $sql_cau = "SELECT * FROM cauhoi WHERE loai='reading' AND trangthai!=0 AND madv={$dv['madv']} ORDER BY macauhoi ASC";
                $res_cau = mysqli_query($this->con, $sql_cau);

                $questions = [];
                while ($q = mysqli_fetch_assoc($res_cau)) {
                    $sql_ans = "SELECT * FROM cautraloi WHERE macauhoi = {$q['macauhoi']} ORDER BY macautl ASC";
                    $ans = mysqli_query($this->con, $sql_ans);
                    $q['answers'] = [];
                    while ($a = mysqli_fetch_assoc($ans)) {
                        $q['answers'][] = $a;
                    }
                    $questions[] = $q;
                }

                $reading_blocks[] = [
                    "type" => "reading_block",
                    "doanvan" => $dv,
                    "questions" => $questions
                ];
            }

            if ($troncauhoi == 1) {
                shuffle($reading_blocks);
            }

            $data = array_merge($data, $reading_blocks);
        }

        return [
            "valid" => true,
            "data" => $data
        ];
    }

    private function fetchQuestionsByType($type, $monhoc, $listChuong, $socaude, $socautb, $socaukho, $trondapan, $orderBy)
    {
        $data = [];
        $levels = ['1' => $socaude,'2' => $socautb,'3' => $socaukho];
        foreach ($levels as $dokho => $limit) {
            $sql = "SELECT * FROM cauhoi WHERE loai='$type' AND mamonhoc='$monhoc' AND dokho=$dokho AND trangthai!=0 AND machuong IN ($listChuong) $orderBy LIMIT $limit";
            $res = mysqli_query($this->con, $sql);

            while ($row = mysqli_fetch_assoc($res)) {
                if ($type === 'mcq') {
                    $sql_ans = "SELECT * FROM cautraloi WHERE macauhoi = {$row['macauhoi']} ORDER BY macautl ASC";
                    $ans_res = mysqli_query($this->con, $sql_ans);
                    $answers = [];
                    while ($a = mysqli_fetch_assoc($ans_res)) {
                        $answers[] = $a;
                    }
                    if ($trondapan == 1) {
                        shuffle($answers);
                    }
                    $row['answers'] = $answers;
                }
                $data[] = $row;
            }
        }
        return $data;
    }




    public function update(
    $made,
    $monthi,
    $nguoitao,
    $tende,
    $thoigianthi,
    $thoigianbatdau,
    $thoigianketthuc,
    $hienthibailam,
    $xemdiemthi,
    $xemdapan,
    $daocauhoi,
    $daodapan,
    $tudongnop,
    $loaide,
    $socau_json, 
    $chuong,
    $nhom,
    $loaicauhoi,
    $diem_tracnghiem = 0,
    $diem_tuluan = 0,
    $diem_dochieu = 0
) {
    try {
        // ===== Chuẩn hóa dữ liệu =====
        $monthi           = trim($monthi);
        $nguoitao         = trim($nguoitao);
        $tende            = trim($tende);
        $thoigianthi      = (int)$thoigianthi;
        $hienthibailam    = (int)$hienthibailam;
        $xemdiemthi       = (int)$xemdiemthi;
        $xemdapan         = (int)$xemdapan;
        $daocauhoi        = (int)$daocauhoi;
        $daodapan         = (int)$daodapan;
        $tudongnop        = (int)$tudongnop;
        $loaide           = (int)$loaide;
        $made             = (int)$made;

        // ===== Xử lý mảng =====
        $chuong = is_array($chuong) ? array_map('intval', $chuong) : [];
        $nhom   = is_array($nhom)   ? array_map('intval', $nhom)   : [];
        $loaicauhoi = is_array($loaicauhoi) ? array_unique(array_map('trim', $loaicauhoi)) : [];

        if (empty($loaicauhoi)) $loaicauhoi = ['mcq'];

        // ===== Giải mã số câu =====
        $socau = json_decode($socau_json, true);
        if (!is_array($socau)) $socau = [];

        // Khởi tạo mặc định
        $mcq_de = $mcq_tb = $mcq_kho = 0;
        $essay_de = $essay_tb = $essay_kho = 0;
        $reading_de = $reading_tb = $reading_kho = 0;

        foreach ($loaicauhoi as $type) {
            if (!isset($socau[$type])) continue;

            $de  = (int)($socau[$type]['de'] ?? 0);
            $tb  = (int)($socau[$type]['tb'] ?? 0);
            $kho = (int)($socau[$type]['kho'] ?? 0);

            if ($type === 'mcq') {
                $mcq_de = $de;  $mcq_tb = $tb;  $mcq_kho = $kho;
            } elseif ($type === 'essay') {
                $essay_de = $de; $essay_tb = $tb; $essay_kho = $kho;
            } elseif ($type === 'reading') {
                $reading_de = $de; $reading_tb = $tb; $reading_kho = $kho;
            }
        }

        // ===== SQL Update =====
        $sql = "UPDATE dethi SET
                    monthi=?, nguoitao=?, tende=?, thoigianthi=?, thoigianbatdau=?, thoigianketthuc=?,
                    hienthibailam=?, xemdiemthi=?, xemdapan=?, troncauhoi=?, trondapan=?, nopbaichuyentab=?,
                    loaide=?,
                    mcq_de=?, mcq_tb=?, mcq_kho=?,
                    essay_de=?, essay_tb=?, essay_kho=?,
                    reading_de=?, reading_tb=?, reading_kho=?,
                    diem_tracnghiem=?, diem_tuluan=?, diem_dochieu=?
                WHERE made=?";

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->con));
        }

        $types = "sssissiiiiiiiiiiiiiiiidddi";

        mysqli_stmt_bind_param(
            $stmt,
            $types,
            $monthi,
            $nguoitao,
            $tende,
            $thoigianthi,
            $thoigianbatdau,
            $thoigianketthuc,
            $hienthibailam,
            $xemdiemthi,
            $xemdapan,
            $daocauhoi,
            $daodapan,
            $tudongnop,
            $loaide,
            $mcq_de,
            $mcq_tb,
            $mcq_kho,
            $essay_de,
            $essay_tb,
            $essay_kho,
            $reading_de,
            $reading_tb,
            $reading_kho,
            $diem_tracnghiem,   // d
            $diem_tuluan,       // d
            $diem_dochieu,      // d
            $made               // i
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

        // ===== Cập nhật chương & nhóm học phần =====
        if (!$this->update_chuongdethi($made, $chuong)) {
            throw new Exception("Cập nhật chương thất bại");
        }
        if (!$this->update_giaodethi($made, $nhom)) {
            throw new Exception("Cập nhật nhóm học phần thất bại");
        }

        // ===== Nếu đề tự động thì tạo lại câu hỏi =====
        if ($loaide == 1) {
            mysqli_query($this->con, "DELETE FROM chitietdethi WHERE made = " . $made);
            $this->addQuestionsToAutoTest($made, $socau, $chuong, $monthi, $loaicauhoi);
        }

        return ['success' => true];

    } catch (Exception $e) {
        error_log("updateTest error: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

    public function delete($made)
    {
        mysqli_begin_transaction($this->con);
        try {
            // Kiểm tra bảng ketqua trước
            $sql_check_ketqua = "SELECT COUNT(*) as count FROM `ketqua` WHERE `made` = ?";
            $stmt_check_ketqua = mysqli_prepare($this->con, $sql_check_ketqua);
            if (!$stmt_check_ketqua) {
                throw new Exception("Lỗi chuẩn bị truy vấn kiểm tra kết quả thi: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt_check_ketqua, "s", $made);
            mysqli_stmt_execute($stmt_check_ketqua);
            $result_check_ketqua = mysqli_stmt_get_result($stmt_check_ketqua);
            $row_ketqua = mysqli_fetch_assoc($result_check_ketqua);
            mysqli_stmt_close($stmt_check_ketqua);
            if ($row_ketqua['count'] > 0) {
                throw new Exception("Không thể xóa đề thi vì đã có {$row_ketqua['count']} thí sinh hoàn thành bài thi.");
            }

            // Lấy danh sách manhom từ giaodethi
            $sql_get_manhom = "SELECT DISTINCT manhom FROM `giaodethi` WHERE `made` = ?";
            $stmt_get_manhom = mysqli_prepare($this->con, $sql_get_manhom);
            if (!$stmt_get_manhom) {
                throw new Exception("Lỗi chuẩn bị truy vấn lấy mã nhóm: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt_get_manhom, "s", $made);
            mysqli_stmt_execute($stmt_get_manhom);
            $result_manhom = mysqli_stmt_get_result($stmt_get_manhom);
            $manhom_list = [];
            while ($row = mysqli_fetch_assoc($result_manhom)) {
                $manhom_list[] = $row['manhom'];
            }
            mysqli_stmt_close($stmt_get_manhom);

            // Xóa bản ghi trong trangthaithongbao dựa trên matb từ chitietthongbao
            if (!empty($manhom_list)) {
                $placeholders = implode(',', array_fill(0, count($manhom_list), '?'));
                $sql_delete_trangthaithongbao = "DELETE FROM `trangthaithongbao` WHERE `matb` IN (SELECT `matb` FROM `chitietthongbao` WHERE `manhom` IN ($placeholders))";
                $stmt_delete_trangthaithongbao = mysqli_prepare($this->con, $sql_delete_trangthaithongbao);
                if (!$stmt_delete_trangthaithongbao) {
                    throw new Exception("Lỗi chuẩn bị truy vấn xóa trạng thái thông báo: " . mysqli_error($this->con));
                }
                mysqli_stmt_bind_param($stmt_delete_trangthaithongbao, str_repeat('s', count($manhom_list)), ...$manhom_list);
                mysqli_stmt_execute($stmt_delete_trangthaithongbao);
                mysqli_stmt_close($stmt_delete_trangthaithongbao);

                // Xóa bản ghi trong chitietthongbao
                $sql_delete_chitietthongbao = "DELETE FROM `chitietthongbao` WHERE `manhom` IN ($placeholders)";
                $stmt_delete_chitietthongbao = mysqli_prepare($this->con, $sql_delete_chitietthongbao);
                if (!$stmt_delete_chitietthongbao) {
                    throw new Exception("Lỗi chuẩn bị truy vấn xóa chi tiết thông báo: " . mysqli_error($this->con));
                }
                mysqli_stmt_bind_param($stmt_delete_chitietthongbao, str_repeat('s', count($manhom_list)), ...$manhom_list);
                mysqli_stmt_execute($stmt_delete_chitietthongbao);
                mysqli_stmt_close($stmt_delete_chitietthongbao);

                // Xóa bản ghi trong thongbao (chỉ xóa những thông báo không còn liên kết với nhóm nào)
                $sql_delete_thongbao = "DELETE FROM `thongbao` WHERE `matb` NOT IN (SELECT `matb` FROM `chitietthongbao`)";
                $stmt_delete_thongbao = mysqli_query($this->con, $sql_delete_thongbao);
                if (!$stmt_delete_thongbao) {
                    throw new Exception("Lỗi xóa thông báo: " . mysqli_error($this->con));
                }
            }

            // Xóa bản ghi trong chitietdethi
            $sql_delete_chitietdethi = "DELETE FROM `chitietdethi` WHERE `made` = ?";
            $stmt_delete_chitietdethi = mysqli_prepare($this->con, $sql_delete_chitietdethi);
            if (!$stmt_delete_chitietdethi) {
                throw new Exception("Lỗi chuẩn bị truy vấn xóa chi tiết đề thi: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt_delete_chitietdethi, "s", $made);
            mysqli_stmt_execute($stmt_delete_chitietdethi);
            mysqli_stmt_close($stmt_delete_chitietdethi);

            // Xóa bản ghi trong dethitudong
            $sql_delete_dethitudong = "DELETE FROM `dethitudong` WHERE `made` = ?";
            $stmt_delete_dethitudong = mysqli_prepare($this->con, $sql_delete_dethitudong);
            if (!$stmt_delete_dethitudong) {
                throw new Exception("Lỗi chuẩn bị truy vấn xóa đề thi tự động: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt_delete_dethitudong, "s", $made);
            mysqli_stmt_execute($stmt_delete_dethitudong);
            mysqli_stmt_close($stmt_delete_dethitudong);

            // Xóa bản ghi trong giaodethi
            $sql_delete_giaodethi = "DELETE FROM `giaodethi` WHERE `made` = ?";
            $stmt_delete_giaodethi = mysqli_prepare($this->con, $sql_delete_giaodethi);
            if (!$stmt_delete_giaodethi) {
                throw new Exception("Lỗi chuẩn bị truy vấn xóa giao đề thi: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt_delete_giaodethi, "s", $made);
            mysqli_stmt_execute($stmt_delete_giaodethi);
            mysqli_stmt_close($stmt_delete_giaodethi);

            // Xóa đề thi
            $sql = "DELETE FROM `dethi` WHERE `made` = ?";
            $stmt = mysqli_prepare($this->con, $sql);
            if (!$stmt) {
                throw new Exception("Lỗi chuẩn bị truy vấn xóa đề thi: " . mysqli_error($this->con));
            }
            mysqli_stmt_bind_param($stmt, "s", $made);
            $success = mysqli_stmt_execute($stmt);
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);

            if ($success && $affected_rows > 0) {
                mysqli_commit($this->con);
                return [
                    'success' => true,
                    'message' => 'Xóa đề thi thành công!'
                ];
            } else {
                throw new Exception("Không tìm thấy đề thi hoặc không thể xóa.");
            }
        } catch (Exception $e) {
            mysqli_rollback($this->con);
            error_log("Lỗi xóa đề thi: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    public function getAll($nguoitao)
    {
        $sql = "SELECT dethi.made, tende, monhoc.tenmonhoc, thoigianbatdau, thoigianketthuc, nhom.tennhom, namhoc, hocky
        FROM dethi, monhoc, giaodethi, nhom
        WHERE dethi.monthi = monhoc.mamonhoc AND dethi.made = giaodethi.made AND nhom.manhom = giaodethi.manhom AND nguoitao = $nguoitao AND dethi.trangthai = 1
        ORDER BY dethi.made DESC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $made = $row['made'];
            $index = array_search($made, array_column($rows, 'made'));
            if ($index === false) {
                $item = [
                    "made" => $made,
                    "tende" => $row['tende'],
                    "thoigianbatdau" => date_format(date_create($row['thoigianbatdau']), "H:i d/m/Y"),
                    "thoigianketthuc" => date_format(date_create($row['thoigianketthuc']), "H:i d/m/Y"),
                    "tenmonhoc" => $row['tenmonhoc'],
                    "namhoc" => $row['namhoc'],
                    "hocky" => $row['hocky'],
                    "nhom" => [$row['tennhom']]
                ];
                array_push($rows, $item);
            } else {
                array_push($rows[$index]["nhom"], $row['tennhom']);
            }
        }
        return $rows;
    }

    public function getById($made)
    {
        $sql_dethi = "SELECT dethi.*, monhoc.tenmonhoc FROM dethi, monhoc WHERE made = $made AND dethi.monthi = monhoc.mamonhoc";
        $result_dethi = mysqli_query($this->con, $sql_dethi);
        $dethi = mysqli_fetch_assoc($result_dethi);
        if ($dethi != null) {
            $sql_giaodethi = "SELECT manhom FROM giaodethi WHERE made = $made";
            $sql_dethitudong = "SELECT machuong FROM dethitudong WHERE made = $made";
            $result_giaodethi = mysqli_query($this->con, $sql_giaodethi);
            $result_dethitudong = mysqli_query($this->con, $sql_dethitudong);
            $dethi['chuong'] = array();
            while ($row = mysqli_fetch_assoc($result_dethitudong)) {
                $dethi['chuong'][] = $row['machuong'];
            }
            $dethi['nhom'] = array();
            while ($row = mysqli_fetch_assoc($result_giaodethi)) {
                $dethi['nhom'][] = $row['manhom'];
            }
        }
        return $dethi;
    }

    public function getInfoTestBasic($made)
    {
        $sql_dethi = "SELECT dethi.made, dethi.tende, dethi.thoigiantao,dethi.loaide,dethi.nguoitao,monhoc.mamonhoc, monhoc.tenmonhoc FROM dethi, monhoc WHERE made = $made AND dethi.monthi = monhoc.mamonhoc";
        $result_dethi = mysqli_query($this->con, $sql_dethi);
        $dethi = mysqli_fetch_assoc($result_dethi);
        if ($dethi != null) {
            $sql_giaodethi = "SELECT giaodethi.manhom, nhom.tennhom FROM giaodethi, nhom WHERE made = $made AND giaodethi.manhom = nhom.manhom";
            $result_giaodethi = mysqli_query($this->con, $sql_giaodethi);
            $dethi['nhom'] = array();
            while ($row = mysqli_fetch_assoc($result_giaodethi)) {
                $dethi['nhom'][] = $row;
            }
        }
        return $dethi;
    }

    public function getListTestGroup($manhom)
    {
        $sql = "SELECT dethi.made, dethi.tende, dethi.thoigianbatdau, dethi.thoigianketthuc
        FROM giaodethi, dethi
        WHERE manhom = '$manhom' AND giaodethi.made = dethi.made ORDER BY dethi.made DESC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $row['thoigianbatdau'] = date_format(date_create($row['thoigianbatdau']), "H:i d/m/Y");
            $row['thoigianketthuc'] = date_format(date_create($row['thoigianketthuc']), "H:i d/m/Y");
            $rows[] = $row;
        }
        return $rows;
    }

    public function getQuestionOfTest($made)
    {
        $sql_dethi = "select * from dethi where made = '$made'";
        $data_dethi = mysqli_fetch_assoc(mysqli_query($this->con, $sql_dethi));
        $question = array();
        if ($data_dethi['loaide'] == 0) {
            $question = $this->getQuestionOfTestManual($made);
        } else {
            $question = $this->getQuestionTestAuto($made);
        }
        $makq = $this->getMaDe($made, $_SESSION['user_id']);
        foreach ($question as $data) {
            $macauhoi = $data['macauhoi'];
            $sql = "INSERT INTO `chitietketqua`(`makq`, `macauhoi`) VALUES ('$makq','$macauhoi')";
            $addCtKq = mysqli_query($this->con, $sql);
        }

        return $question;
    }





    public function getQuestionByUser($made, $user)
    {
        $made = intval($made);
        $user = trim($user);
        $rows = [];
        $ctlmodel = new CauTraLoiModel();

        // === 1. Lấy đề thi ===
        $sql_dethi = "SELECT * FROM dethi WHERE made = ?";
        $stmt = mysqli_prepare($this->con, $sql_dethi);
        mysqli_stmt_bind_param($stmt, "i", $made);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $de = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        if (!$de) {
            return [];
        }

        $troncauhoi = $de['troncauhoi'];
        $trondapan  = $de['trondapan'];
        $loaide     = $de['loaide'];

        // === 2. Lấy kết quả nếu đã làm bài ===
        $sql_kq = "SELECT * FROM ketqua WHERE made = ? AND manguoidung = ?";
        $stmt = mysqli_prepare($this->con, $sql_kq);
        mysqli_stmt_bind_param($stmt, "is", $made, $user);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $data_kq = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        $makq = $data_kq['makq'] ?? 0;

        // === 3. Mapping số câu theo loại + độ khó ===
        $config = [
            'mcq' => [1 => intval($de['mcq_de']), 2 => intval($de['mcq_tb']), 3 => intval($de['mcq_kho'])],
            'essay' => [1 => intval($de['essay_de']), 2 => intval($de['essay_tb']), 3 => intval($de['essay_kho'])],
            'reading' => [1 => intval($de['reading_de']), 2 => intval($de['reading_tb']), 3 => intval($de['reading_kho'])]
        ];

        // === 4. Lấy câu hỏi theo từng loại và độ khó ===
        foreach ($config as $type => $levels) {
            foreach ($levels as $dokho => $limit) {
                if ($limit <= 0) {
                    continue;
                }

                if ($type === 'reading') {
                    // 1. Lấy tất cả đoạn văn của môn học trong đề
                    $sql_dv = "
        SELECT * FROM doan_van
        WHERE mamonhoc = ? AND machuong IN (
            SELECT machuong FROM chitietdethi WHERE made = ? AND trangthai = 1
        )
    ";
                    $stmt_dv = mysqli_prepare($this->con, $sql_dv);
                    mysqli_stmt_bind_param($stmt_dv, "si", $de['monthi'], $made);
                    mysqli_stmt_execute($stmt_dv);
                    $res_dv = mysqli_stmt_get_result($stmt_dv);

                    $all_questions = [];
                    while ($dv = mysqli_fetch_assoc($res_dv)) {
                        // 2. Lấy tất cả câu hỏi đọc hiểu trong đoạn văn này và độ khó
                        $sql_ch = "
            SELECT ch.macauhoi, ch.noidung, ch.dokho, ch.loai, ch.hinhanh, ctkq.dapanchon
            FROM cauhoi ch
            LEFT JOIN chitietketqua ctkq ON ch.macauhoi = ctkq.macauhoi AND ctkq.makq = ?
            WHERE ch.madv = ? AND ch.dokho = ? AND ch.loai = 'reading' AND ch.trangthai = 1
        ";
                        $stmt_ch = mysqli_prepare($this->con, $sql_ch);
                        mysqli_stmt_bind_param($stmt_ch, "iii", $makq, $dv['madv'], $dokho);
                        mysqli_stmt_execute($stmt_ch);
                        $res_ch = mysqli_stmt_get_result($stmt_ch);

                        while ($row = mysqli_fetch_assoc($res_ch)) {
                            $row['context'] = $dv['noidung'];
                            $row['tieude_context'] = $dv['tieude'] ?? '';

                            // 3. Lấy đáp án
                            if ($row['dapanchon']) {
                                $arr = [['macautl' => $row['dapanchon'], 'noidungtl' => $row['dapanchon']]];
                            } else {
                                $arr = $ctlmodel->getAllWithoutAnswer($row['macauhoi']);
                            }
                            if ($trondapan) {
                                shuffle($arr);
                            }
                            $row['cautraloi'] = $arr;

                            if (!empty($row['hinhanh'])) {
                                $row['hinhanh'] = base64_encode($row['hinhanh']);
                            }
                            $all_questions[] = $row;
                        }
                        mysqli_stmt_close($stmt_ch);
                    }
                    mysqli_stmt_close($stmt_dv);

                    // 4. Shuffle toàn bộ câu hỏi reading và chỉ lấy $limit của từng độ khó
                    if (!empty($all_questions)) {
                        shuffle($all_questions);
                        $rows = array_merge($rows, array_slice($all_questions, 0, $limit));
                    }
                } else {
                    // MCQ & Essay
                    $sql = "
                    SELECT ch.macauhoi, ch.noidung, ch.dokho, ch.loai, ch.hinhanh, ctkq.dapanchon
                    FROM chitietdethi ctdt
                    JOIN cauhoi ch ON ctdt.macauhoi = ch.macauhoi
                    LEFT JOIN chitietketqua ctkq ON ch.macauhoi = ctkq.macauhoi AND ctkq.makq = ?
                    WHERE ctdt.made = ? AND ch.loai = ? AND ch.dokho = ? AND ch.trangthai = 1
                    ORDER BY RAND()
                    LIMIT ?
                ";
                    $stmt = mysqli_prepare($this->con, $sql);
                    mysqli_stmt_bind_param($stmt, "iisii", $makq, $made, $type, $dokho, $limit);
                    mysqli_stmt_execute($stmt);
                    $res_sql = mysqli_stmt_get_result($stmt);

                    while ($row = mysqli_fetch_assoc($res_sql)) {
                        if ($type === 'mcq') {
                            $arr = $row['dapanchon']
                                ? [['macautl' => $row['dapanchon'], 'noidungtl' => $row['dapanchon']]]
                                : $ctlmodel->getAllWithoutAnswer($row['macauhoi']);
                            if ($trondapan) {
                                shuffle($arr);
                            }
                            $row['cautraloi'] = $arr;
                        } else { // Essay
                            $row['cautraloi'] = $ctlmodel->getAllWithoutAnswer($row['macauhoi']);
                        }
                        if (!empty($row['hinhanh'])) {
                            $row['hinhanh'] = base64_encode($row['hinhanh']);
                        }
                        $rows[] = $row;
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }

        // === 5. Shuffle toàn bộ câu nếu đề trộn ===
        if ($loaide == 1 && $troncauhoi == 1) {
            shuffle($rows);
        }

        return $rows;
    }


    public function getAllSubjects()
    {
        $sql = "SELECT mamonhoc, tenmonhoc FROM monhoc WHERE trangthai != 0 ORDER BY tenmonhoc ASC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getMaDe($made, $user)
    {
        $sql = "SELECT * FROM `ketqua` WHERE made = '$made' and manguoidung = '$user'";
        $result = mysqli_query($this->con, $sql);
        $data = mysqli_fetch_assoc($result);
        return $data['makq'];
    }

    public function getQuestionTestAuto($made)
    {
        $sql_dethi = "SELECT * FROM dethi WHERE made = '$made'";
        $data_dethi = mysqli_fetch_assoc(mysqli_query($this->con, $sql_dethi));

        // Lấy đúng cột theo CSDL
        $socaude   = $data_dethi['mcq_de'];
        $socautb   = $data_dethi['mcq_tb'];
        $socaukho  = $data_dethi['mcq_kho'];
        $mamonhoc  = $data_dethi['monthi'];
        $troncauhoi = $data_dethi['troncauhoi'];

        // Nếu không xáo trộn thì ORDER BY cố định
        $orderBy = $troncauhoi == 1 ? "ORDER BY RAND()" : "ORDER BY ch.macauhoi ASC";

        // Câu dễ
        $sql_cd = "SELECT ch.macauhoi, ch.noidung, ch.dokho
        FROM dethitudong dttd
        JOIN cauhoi ch ON dttd.machuong = ch.machuong
        WHERE ch.dokho = 1
        AND dttd.made = '$made'
        AND ch.mamonhoc = '$mamonhoc'
        AND ch.trangthai != 0
        $orderBy
        LIMIT $socaude";

        // Trung bình
        $sql_ctb = "SELECT ch.macauhoi, ch.noidung, ch.dokho
        FROM dethitudong dttd
        JOIN cauhoi ch ON dttd.machuong = ch.machuong
        WHERE ch.dokho = 2
        AND dttd.made = '$made'
        AND ch.mamonhoc = '$mamonhoc'
        AND ch.trangthai != 0
        $orderBy
        LIMIT $socautb";

        // Khó
        $sql_ck = "SELECT ch.macauhoi, ch.noidung, ch.dokho
        FROM dethitudong dttd
        JOIN cauhoi ch ON dttd.machuong = ch.machuong
        WHERE ch.dokho = 3
        AND dttd.made = '$made'
        AND ch.mamonhoc = '$mamonhoc'
        AND ch.trangthai != 0
        $orderBy
        LIMIT $socaukho";

        $result_cd = mysqli_query($this->con, $sql_cd);
        $result_tb = mysqli_query($this->con, $sql_ctb);
        $result_ck = mysqli_query($this->con, $sql_ck);

        // Gom kết quả
        $result = [];
        while ($row = mysqli_fetch_assoc($result_cd)) {
            $result[] = $row;
        }
        while ($row = mysqli_fetch_assoc($result_tb)) {
            $result[] = $row;
        }
        while ($row = mysqli_fetch_assoc($result_ck)) {
            $result[] = $row;
        }

        // Chỉ xáo trộn khi bật
        if ($troncauhoi == 1) {
            shuffle($result);
        }

        // Lấy đáp án
        $rows = [];
        $ctlmodel = new CauTraLoiModel();

        foreach ($result as $row) {
            $row['cautraloi'] = $ctlmodel->getAllWithoutAnswer($row['macauhoi']);
            $rows[] = $row;
        }

        return $rows;
    }



    public function getNameGroup($manhom)
    {
        $sql = "SELECT * FROM `nhom` WHERE manhom=$manhom";
        $result = mysqli_query($this->con, $sql);
        $nameGroup = mysqli_fetch_assoc($result)['tennhom'];
        return $nameGroup;
    }

    public function getQuestionOfTestManual($made)
    {
        $sql = "SELECT CTDT.macauhoi, noidung, dokho, thutu FROM chitietdethi CTDT, cauhoi CH WHERE CTDT.macauhoi = CH.macauhoi AND CTDT.made = $made ORDER BY thutu ASC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        $ctlmodel = new CauTraLoiModel();
        while ($row = mysqli_fetch_assoc($result)) {
            $row['cautraloi'] = $ctlmodel->getAllWithoutAnswer($row['macauhoi']);
            $rows[] = $row;
        }
        return $rows;
    }

    public function getResultDetail($makq)
    {
        $makq = mysqli_real_escape_string($this->con, $makq);

        $sql = "SELECT ch.macauhoi, ch.noidung, ch.dokho, ch.loai,
                   dv.noidung AS context, dv.tieude AS tieude_context,
                   ct.dapanchon
            FROM chitietketqua ct
            INNER JOIN cauhoi ch ON ct.macauhoi = ch.macauhoi
            LEFT JOIN doan_van dv ON ch.madv = dv.madv
            WHERE ct.makq = '$makq'
            ORDER BY ch.macauhoi ASC";

        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            die("Query Error: " . mysqli_error($this->con));
        }

        $rows = [];
        $ctlmodel = new CauTraLoiModel();

        while ($row = mysqli_fetch_assoc($result)) {
            $row['cautraloi'] = $ctlmodel->getAll($row['macauhoi']);
            $row['dapanchon'] = $row['dapanchon'] ?? null;

            // Nếu là câu hỏi reading, đoạn văn đã nằm trong $row['context'] và tiêu đề $row['tieude_context']

            $rows[] = $row;
        }

        return $rows;
    }



    public function getTimeTest($dethi, $nguoidung)
    {
        $sql = "Select * from ketqua where made = '$dethi' and manguoidung = '$nguoidung'";
        $sql_dethi = "select * from dethi where made = '$dethi'";
        $result_dethi = mysqli_query($this->con, $sql_dethi);
        $result = mysqli_query($this->con, $sql);
        if ($result) {
            $data = mysqli_fetch_assoc($result);
            $data_dethi = mysqli_fetch_assoc($result_dethi);
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $thoigianketthuc = date("Y-m-d H:i:s", strtotime($data['thoigianvaothi']) + ($data_dethi['thoigianthi'] * 60));
            return $thoigianketthuc;
        }
        return false;
    }

    public function getTimeEndTest($dethi)
    {
        $sql_dethi = "select * from dethi where made = '$dethi'";
        $result_dethi = mysqli_query($this->con, $sql_dethi);
        $data_dethi = mysqli_fetch_assoc($result_dethi);
        $thoigianketthuc = date("Y-m-d H:i:s", strtotime($data_dethi['thoigianketthuc']));
        return $thoigianketthuc;
    }

    public function getGroupsTakeTests($tests)
    {
        $string = implode(', ', $tests);
        $sql = "SELECT GDT.*, tennhom, namhoc, hocky FROM giaodethi GDT, nhom N WHERE GDT.manhom = N.manhom AND made IN ($string)";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function checkStudentAllowed($manguoidung, $madethi)
    {
        $valid = true;
        $sql = "SELECT *
        FROM giaodethi, chitietnhom
        WHERE giaodethi.made = '$madethi' AND giaodethi.manhom = chitietnhom.manhom AND chitietnhom.manguoidung = '$manguoidung'";
        $result = mysqli_query($this->con, $sql);
        if (!mysqli_fetch_assoc($result)) {
            $valid = false;
        }
        return $valid;
    }

    public function getAllGroups()
    {
        $sql = "SELECT manhom, tennhom FROM nhom WHERE trangthai != 0 ORDER BY tennhom ASC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getQuery($filter, $input, $args)
    {
        $query = "";
        if (isset($args["custom"]["function"])) {
            $func = $args["custom"]["function"];
            switch ($func) {
                case "getUserTestSchedule":
                    $query = "SELECT T1.*, T2.diemthi, T2.dathi, T2.xemdiemthi
              FROM (
                  SELECT DT.made, tende, thoigianbatdau, thoigianketthuc, CTN.manhom,
                         tennhom, tenmonhoc, namhoc, hocky 
                  FROM chitietnhom CTN, giaodethi GDT, dethi DT, monhoc MH, nhom N
                  WHERE N.trangthai != 0
                    AND N.manhom = CTN.manhom
                    AND CTN.manhom = GDT.manhom
                    AND DT.made = GDT.made
                    AND MH.mamonhoc = DT.monthi
                    AND DT.trangthai = 1
                    AND manguoidung = '" . $args['manguoidung'] . "'
              ) T1
              LEFT JOIN (
                  SELECT KQ.diem_tuluan, DISTINCT DT.made,
                         CASE WHEN DT.xemdiemthi = 1 THEN KQ.diemthi ELSE NULL END AS diemthi,
                         CASE WHEN KQ.manguoidung IS NOT NULL THEN 1 ELSE 0 END AS dathi,
                         DT.xemdiemthi
                  FROM chitietnhom CTN, giaodethi GDT, dethi DT,
                       monhoc MH, nhom N, ketqua KQ
                  WHERE N.manhom = CTN.manhom
                    AND CTN.manhom = GDT.manhom
                    AND DT.made = GDT.made
                    AND MH.mamonhoc = DT.monthi
                    AND KQ.made = DT.made
                    AND DT.trangthai = 1
                    AND KQ.manguoidung = '" . $args['manguoidung'] . "'
              ) T2
              ON T1.made = T2.made
              WHERE 1";

                    if (isset($filter)) {
                        switch ($filter) {
                            case "0":
                                $query .= " AND CURRENT_TIMESTAMP() BETWEEN thoigianbatdau AND thoigianketthuc AND (T2.dathi IS NULL OR T2.dathi = 0)";
                                break;
                            case "1":
                                $query .= " AND CURRENT_TIMESTAMP() > thoigianketthuc AND (T2.dathi IS NULL OR T2.dathi = 0)";
                                break;
                            case "2":
                                $query .= " AND CURRENT_TIMESTAMP() < thoigianbatdau";
                                break;
                            case "3":
                                $query .= " AND T2.dathi = 1";
                                break;
                        }
                    }

                    if ($input) {
                        $query .= " AND (tende LIKE N'%$input%' OR tenmonhoc LIKE N'%$input%')";
                    }
                    $query .= " ORDER BY made DESC";
                    break;


                case "getAllCreatedTest":
                    $query = "SELECT 
                DT.made, 
                DT.tende, 
                MH.tenmonhoc, 
                DT.thoigianbatdau, 
                DT.thoigianketthuc,
                GROUP_CONCAT(DISTINCT N.tennhom SEPARATOR ', ') AS nhom,
                NH.tennamhoc, 
                HK.tenhocky
              FROM dethi DT
              JOIN giaodethi GDT ON DT.made = GDT.made
              JOIN nhom N ON N.manhom = GDT.manhom
              JOIN monhoc MH ON DT.monthi = MH.mamonhoc
              JOIN namhoc NH ON N.namhoc = NH.manamhoc
              JOIN hocky HK ON N.hocky = HK.mahocky
              WHERE DT.nguoitao = '" . $args['id'] . "' 
                AND DT.trangthai = 1";
                    if (isset($filter)) {
                        switch ($filter) {
                            case "0":
                                $query .= " AND CURRENT_TIMESTAMP() < thoigianbatdau";
                                break;
                            case "1":
                                $query .= " AND CURRENT_TIMESTAMP() BETWEEN thoigianbatdau AND thoigianketthuc";
                                break;
                            case "2":
                                $query .= " AND CURRENT_TIMESTAMP() > thoigianketthuc";
                                break;
                            default:
                        }
                    }
                    if ($input) {
                        $query .= " AND (tende LIKE N'%$input%' OR tenmonhoc LIKE N'%$input%')";
                    }
                    if (isset($args['subject']) && $args['subject'] !== '') {
                        $subject = mysqli_real_escape_string($this->con, $args['subject']);
                        $query .= " AND DT.monthi = '$subject'";
                    }
                    if (isset($args['group']) && $args['group'] !== '') {
                        $group = mysqli_real_escape_string($this->con, $args['group']);
                        $query .= " AND GDT.manhom = '$group'";
                    }
                    $query .= " GROUP BY DT.made ORDER BY DT.made DESC";
                    break;
                case "getQuestionsForTest":
                    $id = mysqli_real_escape_string($this->con, $args['id']);
                    $mamonhoc = mysqli_real_escape_string($this->con, $args['mamonhoc']);
                    $page = $_GET['page'] ?? 1;
                    $limit = 10;
                    $offset = ($page - 1) * $limit;

                    $query = "SELECT cauhoi.*, cauhoi.noidung AS noidungplaintext FROM cauhoi JOIN phancong ON cauhoi.mamonhoc = phancong.mamonhoc WHERE cauhoi.trangthai = 1 AND phancong.manguoidung = '$id' AND cauhoi.mamonhoc = '$mamonhoc'";

                    if (!empty($filter['machuong'])) {
                        $query .= " AND cauhoi.machuong = " . intval($filter['machuong']);
                    }
                    if (!empty($filter['dokho'])) {
                        $query .= " AND cauhoi.dokho = " . intval($filter['dokho']);
                    }
                    if (!empty($input)) {
                        $input_safe = mysqli_real_escape_string($this->con, $input);
                        $query .= " AND (noidung LIKE '%$input_safe%')";
                    }

                    error_log("Query getQuestionsForTest: $query");
                    break;
            }
        }
        return $query;
    }

    public function getTestsGroupWithUserResult($manhom, $manguoidung)
    {
        $sql = "SELECT T1.*, diemthi FROM (SELECT DT.made, tende, thoigianbatdau, thoigianketthuc FROM dethi DT, giaodethi GDT WHERE DT.made = GDT.made AND DT.trangthai = 1 AND manhom = $manhom) T1 LEFT JOIN (SELECT KQ.made, diemthi FROM ketqua KQ, giaodethi GDT WHERE KQ.made = GDT.made AND manguoidung = '$manguoidung' AND GDT.manhom = $manhom) T2 ON T1.made = T2.made ORDER BY made DESC";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
