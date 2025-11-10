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
    // Thêm vào class PhanCongModel

public function addAssignment($giangvien, $listSubject, $namhoc = null, $hocky = null)
{
    $check = true;
    $values = array();
    foreach ($listSubject as $mamonhoc) {
        $namhocEsc = $namhoc ? "'$namhoc'" : 'NULL';
        $hockyEsc  = $hocky  ? "'$hocky'"  : 'NULL';
        $values[] = "('$mamonhoc', '$giangvien', $namhocEsc, $hockyEsc)";
    }
    if (!empty($values)) {
        $sql = "INSERT INTO `phancong` (`mamonhoc`, `manguoidung`, `namhoc`, `hocky`) VALUES " . implode(', ', $values);
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $check = false;
        }
    }
    return $check;
}

public function getAssignmentByUser($user, $namhoc = null, $hocky = null)
{
    $sql = "SELECT pc.mamonhoc 
            FROM `phancong` pc 
            JOIN monhoc mh ON pc.mamonhoc = mh.mamonhoc 
            WHERE pc.manguoidung = ? 
              AND mh.trangthai = 1";
    
    $params = [$user];
    $types = "s";

    if ($namhoc !== null) {
        $sql .= " AND pc.namhoc = ?";
        $params[] = $namhoc;
        $types .= "i";
    }
    if ($hocky !== null) {
        $sql .= " AND pc.hocky = ?";
        $params[] = $hocky;
        $types .= "i";
    }

    $stmt = mysqli_prepare($this->con, $sql);
    if ($stmt === false) return [];

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row['mamonhoc'];
    }
    mysqli_stmt_close($stmt);
    return $rows;
}

public function update($old_mamonhoc, $old_manguoidung, $old_namhoc, $old_hocky, $new_mamonhoc, $new_manguoidung, $new_namhoc, $new_hocky)
{
    $sql = "UPDATE phancong 
            SET mamonhoc = ?, manguoidung = ?, namhoc = ?, hocky = ?
            WHERE mamonhoc = ? AND manguoidung = ? AND namhoc " . ($old_namhoc === null ? "IS NULL" : "= ?") . " AND hocky " . ($old_hocky === null ? "IS NULL" : "= ?");

    $stmt = mysqli_prepare($this->con, $sql);
    
    if ($old_namhoc === null) {
        mysqli_stmt_bind_param($stmt, "ssssss", $new_mamonhoc, $new_manguoidung, $new_namhoc, $new_hocky, $old_mamonhoc, $old_manguoidung);
    } elseif ($old_hocky === null) {
        mysqli_stmt_bind_param($stmt, "ssssssi", $new_mamonhoc, $new_manguoidung, $new_namhoc, $new_hocky, $old_mamonhoc, $old_manguoidung, $old_namhoc);
    } else {
        mysqli_stmt_bind_param($stmt, "ssssssii", $new_mamonhoc, $new_manguoidung, $new_namhoc, $new_hocky, $old_mamonhoc, $old_manguoidung, $old_namhoc, $old_hocky);
    }
    
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

public function delete($mamon, $id, $namhoc = null, $hocky = null)
{
    $sql = "DELETE FROM `phancong` WHERE mamonhoc = ? AND manguoidung = ?";
    $params = [$mamon, $id];
    $types = "ss";

    if ($namhoc !== null) { $sql .= " AND namhoc = ?"; $params[] = $namhoc; $types .= "i"; }
    else { $sql .= " AND namhoc IS NULL"; }

    if ($hocky !== null) { $sql .= " AND hocky = ?"; $params[] = $hocky; $types .= "i"; }
    else { $sql .= " AND hocky IS NULL"; }

    $stmt = mysqli_prepare($this->con, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

public function deleteAll($id, $namhoc = null, $hocky = null)
{
    $sql = "DELETE FROM `phancong` WHERE manguoidung = ?";
    $params = [$id];
    $types = "s";

    if ($namhoc !== null) { $sql .= " AND namhoc = ?"; $params[] = $namhoc; $types .= "i"; }
    if ($hocky !== null) { $sql .= " AND hocky = ?"; $params[] = $hocky; $types .= "i"; }

    $stmt = mysqli_prepare($this->con, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}
      public function getQuery($filter, $input, $args) {
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
        $query = "SELECT pc.mamonhoc, pc.manguoidung, ng.hoten, mh.tenmonhoc FROM phancong as pc JOIN monhoc as mh on pc.mamonhoc=mh.mamonhoc JOIN nguoidung as ng on pc.manguoidung=ng.id";
        if ($input) {
            $query .= " AND (mh.tenmonhoc LIKE N'%${input}%' OR ng.hoten LIKE '%${input}%')";
        }
        return $query;
    }
}
