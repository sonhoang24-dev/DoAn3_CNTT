<?php

require_once "./mvc/core/AuthCore.php";

class view_subject extends Controller
{
    public $monHocModel;
    public $chuongModel;

    public function __construct()
    {
        $this->monHocModel = $this->model("MonHocModel");
        $this->chuongModel = $this->model("ChuongModel");
        require_once "./mvc/core/Pagination.php";
    }

    public function default()
    {
        if (AuthCore::checkPermission("xem_monhoc", "view")) {
            $this->view("main_layout", [
                "Page" => "view_subject",
                "Title" => "Quản lý môn học",
                "Script" => "subject",
                "Plugin" => [
                    "sweetalert2" => 1,
                    "jquery-validate" => 1,
                    "notify" => 1,
                    "pagination" => [],
                ]
            ]);
        } else {
            $this->view("single_layout", ["Page" => "error/page_403", "Title" => "Lỗi !"]);
        }
    }
}
