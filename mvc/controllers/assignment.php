
<?php
class Assignment extends Controller
{
    public $PhanCongModel;

    function __construct()
    {
        $this->PhanCongModel = $this->model("PhanCongModel");
        parent::__construct();
        require_once "./mvc/core/Pagination.php";
    }

    function default()
    {
        if(AuthCore::checkPermission("phancong","view")) {
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
        } else $this->view("single_layout", ["Page" => "error/page_403","Title" => "Lỗi !"]);
    }

    function getGiangVien(){
        AuthCore::checkAuthentication();
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            $result = $this->PhanCongModel->getGiangVien();
            echo json_encode($result);
        }
    }

    function getMonHoc(){
        AuthCore::checkAuthentication();
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            $result = $this->PhanCongModel->getMonHoc();
            echo json_encode($result);
        }
    }

    function getAssignment(){
        AuthCore::checkAuthentication();
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            $result = $this->PhanCongModel->getAssignment();
            echo json_encode($result);
        }
    }
    function addAssignment()
{
    AuthCore::checkAuthentication();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $magiangvien = $_POST['magiangvien'] ?? null;
        $l_subject   = $_POST['listSubject'] ?? [];

        if (empty($magiangvien) || empty($l_subject)) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu dữ liệu đầu vào!'
            ]);
            return;
        }

        $result = $this->PhanCongModel->addAssignment($magiangvien, $l_subject);

        // Nếu model trả về mảng (chi tiết lỗi/thành công)
        if (is_array($result)) {
            echo json_encode($result);
            return;
        }

        // Nếu model chỉ trả về true/false
        if ($result === true) {
            echo json_encode([
                'success' => true,
                'message' => 'Thêm phân công thành công!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Một hoặc nhiều phân công đã tồn tại hoặc thêm thất bại!'
            ]);
        }
    }
}
public function checkDuplicate() {
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
public function checkDuplicateForUpdate() {
    // Kiểm tra môn đã phân công trước khi update
    $giangvien = $_POST['magiangvien'] ?? '';
    $listSubject = $_POST['listSubject'] ?? [];
    $old_mamonhoc = $_POST['old_mamonhoc'] ?? '';

    if (is_string($listSubject)) {
        $listSubject = json_decode($listSubject, true);
    }

    $model = new PhanCongModel();
    $duplicates = [];

    foreach ($listSubject as $mh) {
        // Nếu môn này đã được phân công cho giảng viên khác, hoặc trùng với giảng viên hiện tại nhưng không phải môn đang edit
        if ($model->isAssignmentExist($giangvien, $mh) && $mh != $old_mamonhoc) {
            $duplicates[] = $mh;
        }
    }

    echo json_encode(["duplicates" => $duplicates]);
}
public function update()
{
    $old_mamonhoc     = $_POST['old_mamonhoc']    ?? '';
    $old_manguoidung  = $_POST['old_manguoidung'] ?? '';
    $new_mamonhoc     = $_POST['mamonhoc']        ?? '';
    $new_manguoidung  = $_POST['magiangvien']     ?? '';

    if (empty($new_mamonhoc) || empty($new_manguoidung)) {
        echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu!']);
        return;
    }

    // Nếu môn mới trùng với môn khác đã phân công thì không update
    $model = new PhanCongModel();
    if ($model->isAssignmentExist($new_manguoidung, $new_mamonhoc) && ($new_mamonhoc != $old_mamonhoc || $new_manguoidung != $old_manguoidung)) {
        echo json_encode(['success' => false, 'message' => 'Môn học đã được phân công cho giảng viên này!']);
        return;
    }

    $result = $model->update($old_mamonhoc, $old_manguoidung, $new_mamonhoc, $new_manguoidung);

    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Cập nhật phân công thành công!' : 'Cập nhật thất bại!'
    ]);
}


    function delete(){
        AuthCore::checkAuthentication();
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $id = $_POST['id'];
            $mamon = $_POST['mamon'];
            $result = $this->PhanCongModel->delete($mamon,$id);
            echo $result;
        }
    }

    function deleteAll(){
        AuthCore::checkAuthentication();
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $id = $_POST['id'];
            $result = $this->PhanCongModel->deleteAll($id);
        }
    }

    function getAssignmentByUser(){
        AuthCore::checkAuthentication();
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $id = $_POST['id'];
            $result = $this->PhanCongModel->getAssignmentByUser($id);
            echo json_encode($result);
        }
    }

    public function getQuery($filter, $input, $args) {
        AuthCore::checkAuthentication();
        $query = $this->PhanCongModel->getQuery($filter, $input, $args);
        return $query;
    }
}
