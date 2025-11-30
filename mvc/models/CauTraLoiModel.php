<?php

class CauTraLoiModel extends DB
{
    public function create($macauhoi, $noidungtl, $ladapan, $hinhanh = null)
    {
        $sql = "INSERT INTO cautraloi (macauhoi, noidungtl, ladapan, hinhanh)
            VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->con, $sql);

        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($this->con));
        }

        $blob = null;

        mysqli_stmt_bind_param(
            $stmt,
            "isib",
            $macauhoi,
            $noidungtl,
            $ladapan,
            $blob
        );

        // Nếu có ảnh → gửi dữ liệu binary
        if ($hinhanh !== null) {
            mysqli_stmt_send_long_data($stmt, 3, $hinhanh);
        }

        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            die("Execute failed: " . mysqli_error($this->con));
        }

        mysqli_stmt_close($stmt);
        return true;
    }



    public function updateAnswer($macautl, $macauhoi, $noidungtl, $ladapan, $hinhanh = null)
    {
        $valid = true;

        if ($hinhanh !== null) {
            $sql = "UPDATE cautraloi 
                SET macauhoi = ?, noidungtl = ?, ladapan = ?, hinhanh = ? 
                WHERE macautl = ?";
        } else {
            $sql = "UPDATE cautraloi 
                SET macauhoi = ?, noidungtl = ?, ladapan = ? 
                WHERE macautl = ?";
        }

        $stmt = mysqli_prepare($this->con, $sql);

        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($this->con));
        }

        if ($hinhanh !== null) {
            mysqli_stmt_bind_param(
                $stmt,
                "issbi",
                $macauhoi,
                $noidungtl,
                $ladapan,
                $hinhanh,
                $macautl
            );
            mysqli_stmt_send_long_data($stmt, 3, $hinhanh); // cột thứ 4 là BLOB
        } else {
            mysqli_stmt_bind_param(
                $stmt,
                "issi",
                $macauhoi,
                $noidungtl,
                $ladapan,
                $macautl
            );
        }

        if (!mysqli_stmt_execute($stmt)) {
            $valid = false;
        }

        mysqli_stmt_close($stmt);

        return $valid;
    }


    public function delete($macautl)
    {
        $valid = true;
        // Clear any references from chitietketqua.dapanchon to avoid FK constraint errors
        $clearSql = "UPDATE `chitietketqua` SET `dapanchon` = NULL WHERE `dapanchon` = $macautl";
        mysqli_query($this->con, $clearSql);

        $sql = "DELETE FROM `cautraloi` WHERE `macautl` = $macautl";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
        return $valid;
    }

    public function getAll($macauhoi)
    {
        $macauhoi = mysqli_real_escape_string($this->con, $macauhoi);

        $sql = "SELECT macautl, macauhoi, noidungtl, ladapan, hinhanh 
            FROM `cautraloi` 
            WHERE `macauhoi` = '$macauhoi' 
            ORDER BY macautl ASC";

        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            die("Query Error: " . mysqli_error($this->con));
        }

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['hinhanh'] = $row['hinhanh'];

            $row['noidungtl'] = $row['noidungtl'] ?? '';
            $row['ladapan']   = $row['ladapan'] ?? 0;

            $rows[] = $row;
        }

        return $rows;
    }


    public function getAllWithoutAnswer($macauhoi)
    {
        $sql = "SELECT `macautl`, `noidungtl`, `hinhanh`
            FROM `cautraloi`
            WHERE `macauhoi` = " . intval($macauhoi);

        $result = mysqli_query($this->con, $sql);
        $rows = [];

        while ($row = mysqli_fetch_assoc($result)) {

            // Xử lý ảnh đáp án
            if (!empty($row['hinhanh'])) {
                $row['hinhanhtl'] = base64_encode($row['hinhanh']);
            } else {
                $row['hinhanhtl'] = "";
            }

            unset($row['hinhanh']);
            $rows[] = $row;
        }

        return $rows;
    }



    public function getById($macautl)
    {
        $sql = "SELECT * FROM `cautraloi` WHERE `macautl` = ?";
        $stmt = mysqli_prepare($this->con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $macautl);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        // Trong getById()
        if (!empty($row['hinhanh'])) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($row['hinhanh']) ?: 'image/jpeg';

            $row['hinhanh_base64'] = 'data:' . $mime . ';base64,' . base64_encode($row['hinhanh']);
            $row['option_image_base64'] = $row['hinhanh_base64']; // cho JS
        } else {
            $row['hinhanh_base64'] = null;
            $row['option_image_base64'] = null;
        }

        return $row;
    }

    public function deletebyanswer($macauhoi)
    {
        $valid = true;
        $clearSql = "UPDATE `chitietketqua` SET `dapanchon` = NULL WHERE `macauhoi` = $macauhoi";
        mysqli_query($this->con, $clearSql);

        $sql = "DELETE FROM `cautraloi` WHERE `macauhoi` = $macauhoi";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
        return $valid;
    }

    public function getAnswersForMultipleQuestions($arr_question_id)
    {
        $list = implode(", ", $arr_question_id);
        $sql = "SELECT * FROM cautraloi WHERE macauhoi IN ($list)";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    

}
