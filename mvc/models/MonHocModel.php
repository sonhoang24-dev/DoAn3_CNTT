<?php

class MonHocModel extends DB
{
    public function create($mamon, $tenmon, $sotinchi, $sotietlythuyet, $sotietthuchanh)
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        $manguoidung = $_SESSION['user_id'];

        $check_sql = "SELECT * FROM monhoc WHERE mamonhoc = ?";
        $stmt_check = mysqli_prepare($this->con, $check_sql);
        mysqli_stmt_bind_param($stmt_check, "s", $mamon);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            mysqli_stmt_close($stmt_check);
            return "exist";
        }
        mysqli_stmt_close($stmt_check);

        mysqli_begin_transaction($this->con);

        try {
            $sql_monhoc = "INSERT INTO `monhoc` (`mamonhoc`, `tenmonhoc`, `sotinchi`, `sotietlythuyet`, `sotietthuchanh`, `trangthai`) VALUES (?, ?, ?, ?, ?, 1)";
            $stmt_monhoc = mysqli_prepare($this->con, $sql_monhoc);
            if (!$stmt_monhoc) {
                throw new Exception('Lỗi chuẩn bị truy vấn môn học: ' . mysqli_error($this->con));
            }

            mysqli_stmt_bind_param($stmt_monhoc, "ssiii", $mamon, $tenmon, $sotinchi, $sotietlythuyet, $sotietthuchanh);
            $result_monhoc = mysqli_stmt_execute($stmt_monhoc);
            mysqli_stmt_close($stmt_monhoc);

            if (!$result_monhoc) {
                throw new Exception('Lỗi chèn môn học: ' . mysqli_error($this->con));
            }

            $sql_phancong = "INSERT INTO `phancong` (`mamonhoc`, `manguoidung`) VALUES (?, ?)";
            $stmt_phancong = mysqli_prepare($this->con, $sql_phancong);
            if (!$stmt_phancong) {
                throw new Exception('Lỗi chuẩn bị truy vấn phân công: ' . mysqli_error($this->con));
            }

            mysqli_stmt_bind_param($stmt_phancong, "ss", $mamon, $manguoidung);
            $result_phancong = mysqli_stmt_execute($stmt_phancong);
            mysqli_stmt_close($stmt_phancong);

            if (!$result_phancong) {
                throw new Exception('Lỗi chèn phân công: ' . mysqli_error($this->con));
            }

            mysqli_commit($this->con);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->con);
            error_log("Lỗi tạo môn học: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $mamon, $tenmon, $sotinchi, $sotietlythuyet, $sotietthuchanh)
    {
        $valid = true;
        $sql = "UPDATE `monhoc` SET `mamonhoc`='$mamon',`tenmonhoc`='$tenmon',`sotinchi`='$sotinchi',`sotietlythuyet`='$sotietlythuyet',`sotietthuchanh`='$sotietthuchanh' WHERE `mamonhoc`='$id'";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
        return $valid;
    }

public function delete($mamon)
{
    mysqli_begin_transaction($this->con);
    try {
        // Kiểm tra các bảng liên quan trước khi xóa
        // 1. Kiểm tra bảng cauhoi
        $sql_check_cauhoi = "SELECT COUNT(*) as count FROM `cauhoi` WHERE `mamonhoc` = ?";
        $stmt_check_cauhoi = mysqli_prepare($this->con, $sql_check_cauhoi);
        mysqli_stmt_bind_param($stmt_check_cauhoi, "s", $mamon);
        mysqli_stmt_execute($stmt_check_cauhoi);
        $result_check_cauhoi = mysqli_stmt_get_result($stmt_check_cauhoi);
        $row_cauhoi = mysqli_fetch_assoc($result_check_cauhoi);
        mysqli_stmt_close($stmt_check_cauhoi);
        if ($row_cauhoi['count'] > 0) {
            throw new Exception("Không thể xóa môn học vì vẫn còn câu hỏi liên quan.");
        }

        // 2. Kiểm tra bảng chuong
        $sql_check_chuong = "SELECT COUNT(*) as count FROM `chuong` WHERE `mamonhoc` = ?";
        $stmt_check_chuong = mysqli_prepare($this->con, $sql_check_chuong);
        mysqli_stmt_bind_param($stmt_check_chuong, "s", $mamon);
        mysqli_stmt_execute($stmt_check_chuong);
        $result_check_chuong = mysqli_stmt_get_result($stmt_check_chuong);
        $row_chuong = mysqli_fetch_assoc($result_check_chuong);
        mysqli_stmt_close($stmt_check_chuong);
        if ($row_chuong['count'] > 0) {
            throw new Exception("Không thể xóa môn học vì vẫn còn chương liên quan.");
        }

        // 3. Kiểm tra bảng dethi
        $sql_check_dethi = "SELECT COUNT(*) as count FROM `dethi` WHERE `monthi` = ? AND `trangthai` = 1";
        $stmt_check_dethi = mysqli_prepare($this->con, $sql_check_dethi);
        mysqli_stmt_bind_param($stmt_check_dethi, "s", $mamon);
        mysqli_stmt_execute($stmt_check_dethi);
        $result_check_dethi = mysqli_stmt_get_result($stmt_check_dethi);
        $row_dethi = mysqli_fetch_assoc($result_check_dethi);
        mysqli_stmt_close($stmt_check_dethi);
        if ($row_dethi['count'] > 0) {
            throw new Exception("Không thể xóa môn học vì vẫn còn đề thi đang hoạt động liên quan.");
        }

        $sql_check_nhom = "SELECT COUNT(*) as count FROM `nhom` WHERE `mamonhoc` = ? AND `trangthai` = 1";
        $stmt_check_nhom = mysqli_prepare($this->con, $sql_check_nhom);
        mysqli_stmt_bind_param($stmt_check_nhom, "s", $mamon);
        mysqli_stmt_execute($stmt_check_nhom);
        $result_check_nhom = mysqli_stmt_get_result($stmt_check_nhom);
        $row_nhom = mysqli_fetch_assoc($result_check_nhom);
        mysqli_stmt_close($stmt_check_nhom);
        if ($row_nhom['count'] > 0) {
            throw new Exception("Không thể xóa môn học vì vẫn còn nhóm học phần đang hoạt động liên quan.");
        }

        $sql_check_phancong = "SELECT COUNT(*) as count FROM `phancong` WHERE `mamonhoc` = ?";
        $stmt_check_phancong = mysqli_prepare($this->con, $sql_check_phancong);
        mysqli_stmt_bind_param($stmt_check_phancong, "s", $mamon);
        mysqli_stmt_execute($stmt_check_phancong);
        $result_check_phancong = mysqli_stmt_get_result($stmt_check_phancong);
        $row_phancong = mysqli_fetch_assoc($result_check_phancong);
        mysqli_stmt_close($stmt_check_phancong);
        if ($row_phancong['count'] > 0) {
            throw new Exception("Không thể xóa môn học vì vẫn còn phân công giảng dạy liên quan.");
        }

        $sql_monhoc = "DELETE FROM `monhoc` WHERE `mamonhoc` = ?";
        $stmt_monhoc = mysqli_prepare($this->con, $sql_monhoc);
        if (!$stmt_monhoc) {
            throw new Exception("Lỗi chuẩn bị truy vấn cập nhật trạng thái môn học: " . mysqli_error($this->con));
        }
        mysqli_stmt_bind_param($stmt_monhoc, "s", $mamon);
        $result_monhoc = mysqli_stmt_execute($stmt_monhoc);
        mysqli_stmt_close($stmt_monhoc);
        if (!$result_monhoc) {
            throw new Exception("Lỗi cập nhật trạng thái môn học: " . mysqli_error($this->con));
        }

        mysqli_commit($this->con);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($this->con);
        error_log("Lỗi xóa môn học: " . $e->getMessage());
        return $e->getMessage(); // Trả về thông báo lỗi cụ thể
    }
}

    public function getAll()
    {
        $sql = "SELECT * FROM `monhoc` WHERE `trangthai` = 1";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM `monhoc` WHERE `mamonhoc` = '$id'";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function search($input)
    {
        $sql = "SELECT * FROM `monhoc` WHERE `mamonhoc` LIKE '%$input%' OR `tenmonhoc` LIKE N'%$input%';";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getAllSubjectAssignment($userid)
    {
        $sql = "SELECT monhoc.* FROM phancong, monhoc WHERE manguoidung = '$userid' AND monhoc.mamonhoc = phancong.mamonhoc AND monhoc.trangthai = 1";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getQuery($filter, $input, $args)
    {
        $query = "SELECT * FROM `monhoc` WHERE `trangthai` = '1'";
        $params = array();

        if ($input) {
            $query .= " AND (`monhoc`.`tenmonhoc` LIKE ? OR `monhoc`.`mamonhoc` LIKE ?)";
            $params = array('ss', "%$input%", "%$input%");
        }

        if (isset($filter)) {
            if (isset($filter['mamonhoc'])) {
                $query .= " AND `monhoc`.`mamonhoc` = ?";
                $params[0] = isset($params[0]) ? $params[0] . 's' : 's';
                $params[] = $filter['mamonhoc'];
            }
            if (isset($filter['tenmonhoc'])) {
                $query .= " AND `monhoc`.`tenmonhoc` LIKE ?";
                $params[0] = isset($params[0]) ? $params[0] . 's' : 's';
                $params[] = "%{$filter['tenmonhoc']}%";
            }
        }

        $query .= " ORDER BY `mamonhoc` ASC";

        return ['query' => $query, 'params' => $params];
    }
    public function checkSubject($mamon)
    {
        $sql = "SELECT * FROM `monhoc` WHERE `mamonhoc` = $mamon";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
