
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
                "Title" => "Phân quyền",
                "Plugin" => [
                    "ckeditor" => 1,
                    "select" => 1,
                    "notify" => 1,
                    "sweetalert2" => 1,
                    "jquery-validate" => 1,
                    "pagination" => ["main-page-pagination", "modal-add-assignment-pagination"],
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
            echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }




    public function getNamHoc()
    {
        AuthCore::checkAuthentication();
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $result = $this->PhanCongModel->getNamHoc();
            echo json_encode($result);
        }
    }
    public function getHocKy()
    {
        AuthCore::checkAuthentication();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $manamhoc = $_POST['manamhoc'];
            $result = $this->PhanCongModel->getHocKy($manamhoc);
            echo json_encode($result);
        }
    }
    public function addAssignment()
    {
        AuthCore::checkAuthentication();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $magiangvien = $_POST['magiangvien'] ?? null;
            $l_subject   = $_POST['listSubject'] ?? [];
            $namhoc      = $_POST['namhoc'] ?? null;
            $hocky       = $_POST['hocky'] ?? null;

            if (empty($magiangvien) || empty($l_subject) || empty($namhoc) || empty($hocky)) {
                echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu đầu vào!']);
                return;
            }

            $result = $this->PhanCongModel->addAssignment($magiangvien, $l_subject, $namhoc, $hocky);
            echo json_encode($result);
        }
    }
    public function checkDuplicate()
    {
        $giangvien = $_POST['magiangvien'] ?? '';
        $listSubject = $_POST['listSubject'] ?? [];

        if (is_string($listSubject)) {
            $listSubject = json_decode($listSubject, true);
        }

        $model = new PhanCongModel();
        $duplicates = [];

        foreach ($listSubject as $mh) {
            if ($model->isAssignmentExist($giangvien, $mh)) {
                $duplicates[] = $mh;
            }
        }

        echo json_encode(["duplicates" => $duplicates]);
    }
    public function checkDuplicateForUpdate()
    {
        $giangvien = $_POST['magiangvien'] ?? '';
        $listSubject = $_POST['listSubject'] ?? [];
        $old_mamonhoc = $_POST['old_mamonhoc'] ?? '';

        if (is_string($listSubject)) {
            $listSubject = json_decode($listSubject, true);
        }

        $model = new PhanCongModel();
        $duplicates = [];

        foreach ($listSubject as $mh) {
            if ($model->isAssignmentExist($giangvien, $mh) && $mh != $old_mamonhoc) {
                $duplicates[] = $mh;
            }
        }

        echo json_encode(["duplicates" => $duplicates]);
    }
    public function update()
    {
        $old_mamonhoc = $_POST['old_mamonhoc'] ?? '';
        $old_manguoidung = $_POST['old_manguoidung'] ?? '';
        $old_namhoc = $_POST['old_namhoc'] ?? '';
        $old_hocky = $_POST['old_hocky'] ?? '';
        $new_mamonhoc = $_POST['mamonhoc'] ?? '';
        $new_manguoidung = $_POST['magiangvien'] ?? '';
        $new_namhoc = $_POST['namhoc'] ?? '';
        $new_hocky = $_POST['hocky'] ?? '';

        if (empty($new_mamonhoc) || empty($new_manguoidung) || empty($new_namhoc) || empty($new_hocky)) {
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu!']);
            return;
        }

        $model = new PhanCongModel();
        if ($model->isAssignmentExist($new_manguoidung, $new_mamonhoc, $new_namhoc, $new_hocky)) {
            echo json_encode(['success' => false, 'message' => 'Môn học đã được phân công trong học kỳ này!']);
            return;
        }

        $result = $model->update(
            $old_mamonhoc,
            $old_manguoidung,
            $old_namhoc,
            $old_hocky,
            $new_mamonhoc,
            $new_manguoidung,
            $new_namhoc,
            $new_hocky
        );

        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Cập nhật thành công!' : 'Cập nhật thất bại!'
        ]);
    }



    public function delete()
    {
        AuthCore::checkAuthentication();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];
            $mamon = $_POST['mamon'];
            $namhoc = $_POST['namhoc'] ?? null;
            $hocky = $_POST['hocky'] ?? null;
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
            $id = $_POST['id'];
            $result = $this->PhanCongModel->getAssignmentByUser($id);
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
