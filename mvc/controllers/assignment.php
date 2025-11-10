<?php

class Assignment extends Controller
{
    public $PhanCongModel;

    public function __construct()
    {
        $this->PhanCongModel = $this->model("PhanCongModel");
        parent::__construct();
        require_once "./mvc/core/Pagination.php";
    }

    public function default()
    {
        if (AuthCore::checkPermission("phancong", "view")) {
            $this->view("main_layout", [
                "Page" => "assignment",
                "Title" => "Phân Công Giảng Dạy",
                "Plugin" => [
                    "ckeditor" => 1,
                    "select" => 1,
                    "notify" => 1,
                    "sweetalert2" => 1,
                    "jquery-validate" => 1,
                ],
                "Script" => "assignment"
            ]);
        } else {
            $this->view("single_layout", ["Page" => "error/page_403","Title" => "Lỗi !"]);
        }
    }

    public function getGiangVien()
    {
        AuthCore::checkAuthentication();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $result = $this->PhanCongModel->getGiangVien();
            echo json_encode($result);
        }
    }

    public function getMonHoc()
    {
        AuthCore::checkAuthentication();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $result = $this->PhanCongModel->getMonHoc();
            echo json_encode($result);
        }
    }

    public function getAssignment()
    {
        AuthCore::checkAuthentication();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $result = $this->PhanCongModel->getAssignment();
            echo json_encode($result);
        }
    }

   public function addAssignment()
{
    AuthCore::checkAuthentication();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $magiangvien = $_POST['magiangvien'];
        $l_subject   = $_POST['listSubject'];
        $namhoc      = $_POST['namhoc'] ?? null;
        $hocky       = $_POST['hocky']  ?? null;

        $result = $this->PhanCongModel->addAssignment($magiangvien, $l_subject, $namhoc, $hocky);
        echo $result ? 1 : 0;
    }
}
public function update()
{
    $old_mamonhoc     = $_POST['old_mamonhoc']     ?? '';
    $old_manguoidung  = $_POST['old_manguoidung']  ?? '';
    $old_namhoc       = $_POST['old_namhoc']       ?? null;
    $old_hocky        = $_POST['old_hocky']        ?? null;

    $new_mamonhoc     = $_POST['mamonhoc']         ?? '';
    $new_manguoidung  = $_POST['magiangvien']      ?? '';
    $new_namhoc       = $_POST['namhoc']           ?? null;
    $new_hocky        = $_POST['hocky']            ?? null;

    $result = $this->PhanCongModel->update(
        $old_mamonhoc, $old_manguoidung, $old_namhoc, $old_hocky,
        $new_mamonhoc, $new_manguoidung, $new_namhoc, $new_hocky
    );

    echo json_encode(['success' => $result]);
}

   public function delete()
{
    AuthCore::checkAuthentication();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id      = $_POST['id'];
        $mamon   = $_POST['mamon'];
        $namhoc  = $_POST['namhoc'] ?? null;
        $hocky   = $_POST['hocky']  ?? null;

        $result = $this->PhanCongModel->delete($mamon, $id, $namhoc, $hocky);
        echo json_encode(['success' => $result]);
    }
}

    public function deleteAll()
    {
        AuthCore::checkAuthentication();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];
            $result = $this->PhanCongModel->deleteAll($id);
        }
    }

    public function getAssignmentByUser()
{
    AuthCore::checkAuthentication();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id      = $_POST['id'];
        $namhoc  = $_POST['namhoc'] ?? null;
        $hocky   = $_POST['hocky']  ?? null;

        $result = $this->PhanCongModel->getAssignmentByUser($id, $namhoc, $hocky);
        echo json_encode($result);
    }
}

    public function getQuery($filter, $input, $args)
    {
        AuthCore::checkAuthentication();
        $query = $this->PhanCongModel->getQuery($filter, $input, $args);
        return $query;
    }
}
