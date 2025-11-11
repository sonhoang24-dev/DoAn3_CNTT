<?php

class PhanCongModel extends DB
{
    public function getGiangVien()
    {
        $sql = "SELECT ng.id, ng.manhomquyen, ng.hoten 
            FROM nguoidung ng 
            WHERE EXISTS (
                SELECT 1 
                FROM chitietquyen ctq 
                WHERE ctq.manhomquyen = ng.manhomquyen 
                  AND ctq.chucnang IN ('cauhoi', 'monhoc', 'hocphan', 'chuong')
            )
            AND ng.manhomquyen != 3 
            GROUP BY ng.id";

        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getMonHoc()
    {
        $sql = "SELECT * FROM `monhoc`";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function getNamHoc()
    {
        $sql = "SELECT manamhoc, tennamhoc FROM namhoc ORDER BY tennamhoc DESC";
        $result = mysqli_query($this->con, $sql);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getHocKy($manamhoc)
    {
        $sql = "SELECT mahocky, tenhocky FROM hocky WHERE manamhoc = '$manamhoc'";
        $result = mysqli_query($this->con, $sql);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    // Kiểm tra trùng phân công (bao gồm năm + kỳ)
    public function isAssignmentExist($giangvien, $mamonhoc, $namhoc = null, $hocky = null)
    {
        $sql = "SELECT COUNT(*) as count FROM phancong 
            WHERE mamonhoc = ? AND manguoidung = ?";
        $params = ["ss", $mamonhoc, $giangvien];

        if ($namhoc !== null) {
            $sql .= " AND namhoc = ?";
            $params[0] .= "i";
            $params[] = $namhoc;
        }
        if ($hocky !== null) {
            $sql .= " AND hocky = ?";
            $params[0] .= "i";
            $params[] = $hocky;
        }

        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, ...$params);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $count > 0;
    }

    // Cập nhật addAssignment để hỗ trợ namhoc, hocky
    public function addAssignment($giangvien, $listSubject, $namhoc = null, $hocky = null)
    {
        if (is_string($listSubject)) {
            $listSubject = json_decode($listSubject, true);
        }

        $success = true;
        $added = [];

        foreach ($listSubject as $mamonhoc) {
            if ($this->isAssignmentExist($giangvien, $mamonhoc, $namhoc, $hocky)) {
                continue; // Bỏ qua nếu đã tồn tại
            }

            $sql = "INSERT INTO phancong (mamonhoc, manguoidung, namhoc, hocky) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->con, $sql);
            mysqli_stmt_bind_param($stmt, "ssii", $mamonhoc, $giangvien, $namhoc, $hocky);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($result) {
                $added[] = $mamonhoc;
            } else {
                $success = false;
            }
        }

        return [
            'success' => $success && count($added) > 0,
            'added' => $added,
            'message' => count($added) > 0 ? 'Thêm thành công ' . count($added) . ' môn!' : 'Không có môn nào được thêm!'
        ];
    }

    public function getAssignment()
    {
        $sql = "SELECT pc.mamonhoc, pc.manguoidung, pc.namhoc, pc.hocky, 
                   ng.hoten, mh.tenmonhoc, nh.tennamhoc, hk.tenhocky
            FROM phancong pc
            JOIN monhoc mh ON pc.mamonhoc = mh.mamonhoc
            JOIN nguoidung ng ON pc.manguoidung = ng.id
            LEFT JOIN namhoc nh ON pc.namhoc = nh.manamhoc
            LEFT JOIN hocky hk ON pc.hocky = hk.mahocky
            WHERE pc.trangthai = 1";

        $result = mysqli_query($this->con, $sql);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function update(
        $old_mamonhoc,
        $old_manguoidung,
        $old_namhoc,
        $old_hocky,
        $new_mamonhoc,
        $new_manguoidung,
        $new_namhoc,
        $new_hocky
    ) {
        $sql = "UPDATE phancong 
            SET mamonhoc = ?, manguoidung = ?, namhoc = ?, hocky = ?
            WHERE mamonhoc = ? AND manguoidung = ? AND namhoc = ? AND hocky = ?";

        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "ssii ssii",
            $new_mamonhoc,
            $new_manguoidung,
            $new_namhoc,
            $new_hocky,
            $old_mamonhoc,
            $old_manguoidung,
            $old_namhoc,
            $old_hocky
        );
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $result;
    }

    public function delete($mamon, $id, $namhoc = null, $hocky = null)
    {
        $sql = "UPDATE phancong SET trangthai = 0 WHERE mamonhoc = ? AND manguoidung = ?";
        $types = "ss";
        $values = [$mamon, $id];

        if ($namhoc !== null && $namhoc !== '') {
            $sql .= " AND namhoc = ?";
            $types .= "i";
            $values[] = (int)$namhoc;
        }

        if ($hocky !== null && $hocky !== '') {
            $sql .= " AND hocky = ?";
            $types .= "i";
            $values[] = (int)$hocky;
        }

        $stmt = mysqli_prepare($this->con, $sql);
        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, $types, ...$values);

        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $result;
    }

    public function deleteAll($id)
    {
        $sql = "DELETE FROM `phancong` WHERE manguoidung = '$id'";
        $result = mysqli_query($this->con, $sql);
        return $result;
    }
    public function getAssignmentByUser($user)
    {
        $sql = "SELECT pc.mamonhoc, pc.manguoidung, pc.namhoc, pc.hocky, 
                   ng.hoten, mh.tenmonhoc, nh.tennamhoc, hk.tenhocky
            FROM phancong pc
            JOIN monhoc mh ON pc.mamonhoc = mh.mamonhoc
            JOIN nguoidung ng ON pc.manguoidung = ng.id
            LEFT JOIN namhoc nh ON pc.namhoc = nh.manamhoc
            LEFT JOIN hocky hk ON pc.hocky = hk.mahocky
            WHERE pc.manguoidung = '$user' AND pc.trangthai = 1";

        $result = mysqli_query($this->con, $sql);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getQuery($filter, $input, $args)
    {
        if (isset($args["custom"]["function"])) {
            $func = $args["custom"]["function"];
            switch ($func) {
                case "monhoc":
                    $query = "SELECT * FROM `monhoc` WHERE trangthai = 1";
                    if ($input) {
                        $query .= " AND (monhoc.tenmonhoc LIKE N'%${input}%' OR monhoc.mamonhoc LIKE '%${input}%')";
                    }
                    return $query;
                    break;
                default:
            }
        }
        $query = "SELECT pc.mamonhoc, pc.manguoidung, ng.hoten, mh.tenmonhoc FROM phancong as pc JOIN monhoc as mh on pc.mamonhoc=mh.mamonhoc JOIN nguoidung as ng on pc.manguoidung=ng.id  WHERE pc.trangthai = 1";
        if ($input) {
            $query .= " AND (mh.tenmonhoc LIKE N'%${input}%' OR ng.hoten LIKE '%${input}%')";
        }
        return $query;
    }
}
