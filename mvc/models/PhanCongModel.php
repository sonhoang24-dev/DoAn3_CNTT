<?php
class PhanCongModel extends DB{

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

    public function getMonHoc(){
        $sql = "SELECT * FROM `monhoc`";
        $result = mysqli_query($this->con,$sql);
        $rows = array();
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return $rows;
    }
    public function isAssignmentExist($giangvien, $mamonhoc) {
        $sql = "SELECT COUNT(*) as count FROM phancong WHERE mamonhoc = ? AND manguoidung = ?";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $mamonhoc, $giangvien);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $count > 0;
    }
    public function addAssignment($giangvien, $listSubject) {
        if (is_string($listSubject)) {
            $listSubject = json_decode($listSubject, true);
        }

        $check = true;

        foreach ($listSubject as $mamonhoc) {
            if ($this->isAssignmentExist($giangvien, $mamonhoc)) {
                // Bỏ qua nếu đã tồn tại
                $check = false;
                continue;
            }

            $insertSql = "INSERT INTO phancong (mamonhoc, manguoidung) VALUES (?, ?)";
            $stmt2 = mysqli_prepare($this->con, $insertSql);
            mysqli_stmt_bind_param($stmt2, "ss", $mamonhoc, $giangvien);
            $insertResult = mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

            if (!$insertResult) {
                $check = false;
            }
        }

        return $check;
    }


    public function getAssignment(){
        $sql = "SELECT pc.mamonhoc, pc.manguoidung, ng.hoten, mh.tenmonhoc FROM phancong as pc JOIN monhoc as mh on pc.mamonhoc=mh.mamonhoc JOIN nguoidung as ng on pc.manguoidung=ng.id";
        $result = mysqli_query($this->con,$sql);
        $rows = array();
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return $rows;
    }

    public function delete($mamon,$id){
        $sql = "DELETE FROM `phancong` WHERE mamonhoc = '$mamon' and manguoidung = '$id'";
        $result = mysqli_query($this->con,$sql);
        return $result;
    }
    public function update($old_mamonhoc, $old_manguoidung, $new_mamonhoc, $new_manguoidung)
{
    $sql = "UPDATE phancong 
            SET mamonhoc = ?, manguoidung = ? 
            WHERE mamonhoc = ? AND manguoidung = ?";
    
    $stmt = mysqli_prepare($this->con, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $new_mamonhoc, $new_manguoidung, $old_mamonhoc, $old_manguoidung);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $result; // true/false
}

    public function deleteAll($id){
        $sql = "DELETE FROM `phancong` WHERE manguoidung = '$id'";
        $result = mysqli_query($this->con,$sql);
        return $result;
    }

    public function getAssignmentByUser($user){
        // $sql = "SELECT * FROM `phancong` where manguoidung = '$user'";
        $sql = "SELECT mamonhoc FROM `phancong` where manguoidung = '$user'";
        $result = mysqli_query($this->con,$sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows == 0) {
            return [];
        }
        $row = array();
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return $rows;
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
?>