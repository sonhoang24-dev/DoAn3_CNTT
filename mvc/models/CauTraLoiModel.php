<?php

class CauTraLoiModel extends DB
{
    public function create($macauhoi, $noidungtl, $ladapan)
{
    $stmt = mysqli_prepare($this->con, 
        "INSERT INTO cautraloi (macauhoi, noidungtl, ladapan) VALUES (?, ?, ?)"
    );

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($this->con));
    }

    // $macauhoi: int, $noidungtl: string, $ladapan: int
    mysqli_stmt_bind_param($stmt, "isi", $macauhoi, $noidungtl, $ladapan);

    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        die("Execute failed: " . mysqli_error($this->con));
    }

    mysqli_stmt_close($stmt);
    return $result;
}



    public function update($macautl, $macauhoi, $noidungtl, $ladapan)
    {
        $valid = true;
        $sql = "UPDATE `cautraloi` SET `macauhoi`=$macauhoi,`noidungtl`='$noidungtl',`ladapan`='$ladapan' WHERE `macautl`='$macautl'";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            $valid = false;
        }
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
        $sql = "SELECT * FROM `cautraloi` WHERE `macauhoi` = $macauhoi";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getAllWithoutAnswer($macauhoi)
    {
        $sql = "SELECT `macautl`, `noidungtl` FROM `cautraloi` WHERE `macauhoi` = $macauhoi";
        $result = mysqli_query($this->con, $sql);
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getById($macautl)
    {
        $sql = "SELECT * FROM `cautraloi` WHERE `macautl` = $macautl";
        $result = mysqli_query($this->con, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function deletebyanswer($macauhoi)
    {
        $valid = true;
        // When deleting all answers for a question, clear any student selections
        // referencing these answers to avoid foreign key constraint failures.
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
