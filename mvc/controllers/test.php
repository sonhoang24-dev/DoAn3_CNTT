<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
use Dompdf\Dompdf;
use Sabberworm\CSS\Value\Size;

class Test extends Controller
{
    public $dethimodel;
    public $chitietde;
    public $ketquamodel;
    public $cauhoimodel;
    public $announcementmodel;


    public function __construct()
    {
        $this->dethimodel = $this->model("DeThiModel");
        $this->chitietde = $this->model("ChiTietDeThiModel");
        $this->ketquamodel = $this->model("KetQuaModel");
        $this->cauhoimodel = $this->model("CauHoiModel");
        $this->announcementmodel = $this->model("AnnouncementModel");



        parent::__construct();
        require_once "./mvc/core/Pagination.php";
    }

    public function default()
    {
        if (AuthCore::checkPermission("dethi", "view")) {
            $this->view("main_layout", [
                "Page" => "test",
                "Title" => "Đề kiểm tra",
                "Plugin" => [
                    "notify" => 1,
                    "sweetalert2" => 1,
                    "pagination" => [],
                ],
                "Script" => "test",
                "user_id" => $_SESSION['user_id'],
            ]);
        } else {
            $this->view("single_layout", ["Page" => "error/page_403", "Title" => "Lỗi !"]);
        }
    }

    public function add()
    {
        if (AuthCore::checkPermission("dethi", "create")) {
            $this->view("main_layout", [
                "Page" => "add_update_test",
                "Title" => "Tạo đề kiểm tra",
                "Plugin" => [
                    "datepicker" => 1,
                    "flatpickr" => 1,
                    "select" => 1,
                    "notify" => 1,
                    "jquery-validate" => 1
                ],
                "Script" => "action_test",
                "Action" => "create"
            ]);
        } else {
            $this->view("single_layout", ["Page" => "error/page_403", "Title" => "Lỗi !"]);
        }
    }

    public function update($made)
    {
        if (filter_var($made, FILTER_VALIDATE_INT) !== false) {
            $dethi = $this->dethimodel->getById($made);
            if (isset($dethi)) {
                if (AuthCore::checkPermission("dethi", "update") && $dethi['nguoitao'] == $_SESSION['user_id']) {
                    $this->view("main_layout", [
                        "Page" => "add_update_test",
                        "Title" => "Cập nhật đề kiểm tra",
                        "Plugin" => [
                            "datepicker" => 1,
                            "flatpickr" => 1,
                            "select" => 1,
                            "notify" => 1,
                            "jquery-validate" => 1
                        ],
                        "Script" => "action_test",
                        "Action" => "update"
                    ]);
                } else {
                    $this->view("single_layout", ["Page" => "error/page_403", "Title" => "Lỗi !"]);
                }
            } else {
                $this->view("single_layout", ["Page" => "error/page_404", "Title" => "Lỗi !"]);
            }
        } else {
            $this->view("single_layout", ["Page" => "error/page_404", "Title" => "Lỗi !"]);
        }
    }

    public function start($made)
    {
        if (filter_var($made, FILTER_VALIDATE_INT) !== false) {
            $dethi = $this->dethimodel->getById($made);
            $check_allow = $this->dethimodel->checkStudentAllowed($_SESSION['user_id'], $made);
            if (isset($dethi)) {
                if (AuthCore::checkPermission("tgthi", "join") && $check_allow) {
                    $this->view("main_layout", [
                        "Page" => "vao_thi",
                        "Title" => "Bắt đầu thi",
                        "Test" => $dethi,
                        "Check" => $this->ketquamodel->getMaKQ($made, $_SESSION['user_id']),
                        "Script" => "vaothi",
                        "Plugin" => [
                            "notify" => 1
                        ]
                    ]);
                } else {
                    $this->view("single_layout", ["Page" => "error/page_403", "Title" => "Lỗi !"]);
                }
            } else {
                $this->view("single_layout", ["Page" => "error/page_404", "Title" => "Lỗi !"]);
            }
        } else {
            $this->view("single_layout", ["Page" => "error/page_404", "Title" => "Lỗi !"]);
        }
    }

    public function detail($made)
    {
        if (filter_var($made, FILTER_VALIDATE_INT) !== false) {
            $dethi = $this->dethimodel->getInfoTestBasic($made);
            if (isset($dethi)) {
                if (AuthCore::checkPermission("dethi", "create") && $dethi['nguoitao'] == $_SESSION['user_id']) {
                    $this->view("main_layout", [
                        "Page" => "test_detail",
                        "Title" => "Danh sách đã thi",
                        "Test" => $dethi,
                        "Script" => "test_detail",
                        "Plugin" => [
                            "pagination" => [],
                            "chart" => 1
                        ]
                    ]);
                } else {
                    $this->view("single_layout", ["Page" => "error/page_403", "Title" => "Lỗi !"]);
                }
            } else {
                $this->view("single_layout", ["Page" => "error/page_404", "Title" => "Lỗi !"]);
            }
        } else {
            $this->view("single_layout", ["Page" => "error/page_404", "Title" => "Lỗi !"]);
        }
    }
    public function get_subjects()
    {
        $model = new DeThiModel();
        $subjects = $model->getAllSubjects();
        header('Content-Type: application/json');
        echo json_encode($subjects);
        exit;
    }
    public function get_groups()
    {
        $model = new DeThiModel();
        $groups = $model->getAllGroups();
        header('Content-Type: application/json');
        echo json_encode($groups);
        exit;
    }

    public function select($made)
    {
        if (filter_var($made, FILTER_VALIDATE_INT) !== false) {
            $check = $this->dethimodel->getById($made);
            error_log("select method called with made: $made, check: " . print_r($check, true));
            if (isset($check) && !empty($check)) {
                if (($check && (AuthCore::checkPermission("dethi", "create") || AuthCore::checkPermission("dethi", "update"))) && $check['loaide'] == 0 && $check['nguoitao'] == $_SESSION['user_id']) {
                    error_log("Access granted for select_question, loaide: {$check['loaide']}, nguoitao: {$check['nguoitao']}, user_id: {$_SESSION['user_id']}");
                    $this->view('main_layout', [
                        "Page" => "select_question",
                        "Title" => "Chọn câu hỏi",
                        "Script" => "select_question",
                        "Plugin" => [
                            "notify" => 1,
                            "pagination" => [],
                        ],
                    ]);
                } else {
                    error_log("Access denied: Permission or loaide/nguoitao check failed");
                    $this->view("single_layout", ["Page" => "error/page_403", "Title" => "Lỗi !"]);
                }
            } else {
                error_log("Test not found for made: $made");
                $this->view("single_layout", ["Page" => "error/page_404", "Title" => "Lỗi !"]);
            }
        } else {
            error_log("Invalid made: $made");
            $this->view("single_layout", ["Page" => "error/page_404", "Title" => "Lỗi !"]);
        }
    }

    // Tham gia thi
    public function taketest($made)
    {
        if (filter_var($made, FILTER_VALIDATE_INT) !== false) {
            if (AuthCore::checkPermission("tgthi", "join")) {
                $user_id = $_SESSION['user_id'];
                $check = $this->ketquamodel->getMaKQ($made, $user_id);
                $infoTest = $this->dethimodel->getById($made);
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $now = new DateTime();
                $timestart = new DateTime($infoTest['thoigianbatdau']);
                $timeend = new DateTime($infoTest['thoigianketthuc']);
                if ($now >= $timestart && $now <= $timeend && $check['diemthi'] == '') {
                    $this->view("single_layout", [
                        "Page" => "de_thi",
                        "Title" => "Làm bài kiểm tra",
                        "Made" => $made,
                        "Script" => "de_thi",
                        "Plugin" => [
                            "sweetalert2" => 1
                        ]
                    ]);
                } else {
                    header("Location: ../start/$made");
                }
            } else {
                $this->view("single_layout", ["Page" => "error/page_403", "Title" => "Lỗi !"]);
            }
        } else {
            $this->view("single_layout", ["Page" => "error/page_404", "Title" => "Lỗi !"]);
        }
    }
    public function delete()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && AuthCore::checkPermission("dethi", "delete")) {
            $made = $_POST['made'];
            $result = $this->dethimodel->delete($made);
            echo json_encode($result);
        } else {
            echo json_encode(false);
        }
    }

    public function addTest()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER["REQUEST_METHOD"] != "POST" || !AuthCore::checkPermission("dethi", "create")) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'error' => 'Yêu cầu không hợp lệ hoặc không có quyền truy cập.'
            ]);
            exit;
        }

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        try {
            $mamonhoc = trim($_POST['mamonhoc'] ?? '');
            $tende = trim($_POST['tende'] ?? '');
            $thoigianthi = (int)($_POST['thoigianthi'] ?? 0);
            $nguoitao = $_SESSION['user_id'] ?? 0;

            $chuong = isset($_POST['chuong']) && is_array($_POST['chuong'])
                ? array_map('intval', $_POST['chuong']) : [];
            $nhom = isset($_POST['manhom']) && is_array($_POST['manhom'])
                ? array_map('intval', $_POST['manhom']) : [];
            $loaide = (int)($_POST['loaide'] ?? 0);

            // === Validate & chuẩn hóa datetime ===
            $thoigianbatdau = !empty($_POST['thoigianbatdau']) ? date('Y-m-d H:i:s', strtotime($_POST['thoigianbatdau'])) : null;
            $thoigianketthuc = !empty($_POST['thoigianketthuc']) ? date('Y-m-d H:i:s', strtotime($_POST['thoigianketthuc'])) : null;

            // === Các option khác ===
            $xembailam = (int)($_POST['xembailam'] ?? 0);
            $xemdiemthi = (int)($_POST['xemdiem'] ?? 0);
            $xemdapan = (int)($_POST['xemdapan'] ?? 0);
            $daocauhoi = (int)($_POST['daocauhoi'] ?? 0);
            $daodapan = (int)($_POST['daodapan'] ?? 0);
            $tudongnop = (int)($_POST['tudongnop'] ?? 0);
            $loaicauhoi = isset($_POST['loaicauhoi']) && is_array($_POST['loaicauhoi'])
                ? $_POST['loaicauhoi'] : ['mcq'];

            $socau = [];
            if (!empty($_POST['socau'])) {
                $socau = json_decode($_POST['socau'], true);
                if (!is_array($socau)) {
                    throw new Exception("Dữ liệu số câu không hợp lệ");
                }
            }

            if ($loaide == 1 && !empty($socau)) {
                $cauHoiModel = new CauHoiModel($this->dethimodel->con);

                foreach ($socau as $type => $levels) {
                    foreach (['de' => 1,'tb' => 2,'kho' => 3] as $key => $levelNum) {
                        $qty = intval($levels[$key] ?? 0);
                        if ($qty <= 0) {
                            continue;
                        }

                        $available = $cauHoiModel->getsoluongcauhoi($chuong, $mamonhoc, $levelNum, [$type]);
                        if ($available < $qty) {
                            throw new Exception("Không đủ câu hỏi loại $type mức độ $key: Có $available, yêu cầu $qty.");
                        }
                    }
                }
            }

            $made = $this->dethimodel->create(
                $mamonhoc,
                $nguoitao,
                $tende,
                $thoigianthi,
                $thoigianbatdau,
                $thoigianketthuc,
                $xembailam,
                $xemdiemthi,
                $xemdapan,
                $daocauhoi,
                $daodapan,
                $tudongnop,
                $loaide,
                $socau,
                $chuong,
                $nhom,
                $loaicauhoi
            );

            if (!$made || $made <= 0) {
                throw new Exception("Lỗi hệ thống khi tạo đề thi.");
            }

            echo json_encode(['success' => true, 'made' => $made]);
            exit;

        } catch (\Exception $e) {
            error_log("addTest error: " . $e->getMessage());
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        } catch (\Throwable $e) {
            error_log(" addTest fatal: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Lỗi hệ thống']);
            exit;
        }
    }










    public function updateTest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && AuthCore::checkPermission("dethi", "update")) {
            $response = ['success' => false, 'error' => ''];

            $made = (int)($_POST['made'] ?? 0);
            $mamonhoc = trim($_POST['mamonhoc'] ?? '');
            $tende = trim($_POST['tende'] ?? '');
            $thoigianthi = (int)($_POST['thoigianthi'] ?? 0);
            $thoigianbatdau = trim($_POST['thoigianbatdau'] ?? '');
            $thoigianketthuc = trim($_POST['thoigianketthuc'] ?? '');
            $socaude = (int)($_POST['socaude'] ?? 0);
            $socautb = (int)($_POST['socautb'] ?? 0);
            $socaukho = (int)($_POST['socaukho'] ?? 0);
            $loaide = (int)($_POST['loaide'] ?? 0);
            $xemdiem = (int)($_POST['xemdiem'] ?? 0);
            $xemdapan = (int)($_POST['xemdapan'] ?? 0);
            $hienthibailam = (int)($_POST['xembailam'] ?? 0);
            $daocauhoi = (int)($_POST['daocauhoi'] ?? 0);
            $daodapan = (int)($_POST['daodapan'] ?? 0);
            $tudongnop = (int)($_POST['tudongnop'] ?? 0);
            $manhom = isset($_POST['manhom']) ? (array)$_POST['manhom'] : [];

            // Lấy danh sách chương từ cơ sở dữ liệu
            $dethi = $this->dethimodel->getById($made);
            $chuong = isset($dethi['chuong']) ? (array)$dethi['chuong'] : [];

            error_log("updateTest: made=$made, mamonhoc=$mamonhoc, chuong=" . json_encode($chuong) . ", socaude=$socaude, socautb=$socautb, socaukho=$socaukho, loaide=$loaide, manhom=" . json_encode($manhom));

            // Kiểm tra dữ liệu bắt buộc
            if (!$made) {
                $response['error'] = "Mã đề thi không hợp lệ.";
                error_log($response['error']);
                echo json_encode($response);
                return;
            }
            if (!$mamonhoc) {
                $response['error'] = "Vui lòng chọn môn học.";
                error_log($response['error']);
                echo json_encode($response);
                return;
            }
            if (!$tende) {
                $response['error'] = "Vui lòng nhập tên đề thi.";
                error_log($response['error']);
                echo json_encode($response);
                return;
            }
            if ($thoigianthi <= 0) {
                $response['error'] = "Thời gian thi phải lớn hơn 0.";
                error_log($response['error']);
                echo json_encode($response);
                return;
            }
            if (!$thoigianbatdau || !$thoigianketthuc) {
                $response['error'] = "Vui lòng chọn thời gian bắt đầu và kết thúc.";
                error_log($response['error']);
                echo json_encode($response);
                return;
            }
            if (strtotime($thoigianketthuc) <= strtotime($thoigianbatdau)) {
                $response['error'] = "Thời gian kết thúc phải sau thời gian bắt đầu.";
                error_log($response['error']);
                echo json_encode($response);
                return;
            }
            if (empty($manhom)) {
                $response['error'] = "Vui lòng chọn ít nhất một nhóm học phần.";
                error_log($response['error']);
                echo json_encode($response);
                return;
            }

            // Kiểm tra số lượng câu hỏi nếu tạo đề tự động
            if ($loaide == 1) {
                $availableEasy = $this->cauhoimodel->getsoluongcauhoi($chuong, $mamonhoc, 1);
                $availableMedium = $this->cauhoimodel->getsoluongcauhoi($chuong, $mamonhoc, 2);
                $availableHard = $this->cauhoimodel->getsoluongcauhoi($chuong, $mamonhoc, 3);

                if ($availableEasy < $socaude) {
                    $response['error'] = "Không đủ câu hỏi dễ: Có $availableEasy, yêu cầu $socaude.";
                    error_log($response['error']);
                    echo json_encode($response);
                    return;
                }
                if ($availableMedium < $socautb) {
                    $response['error'] = "Không đủ câu hỏi trung bình: Có $availableMedium, yêu cầu $socautb.";
                    error_log($response['error']);
                    echo json_encode($response);
                    return;
                }
                if ($availableHard < $socaukho) {
                    $response['error'] = "Không đủ câu hỏi khó: Có $availableHard, yêu cầu $socaukho.";
                    error_log($response['error']);
                    echo json_encode($response);
                    return;
                }
            }

            // Gọi update từ DeThiModel
            $result = $this->dethimodel->update(
                $made,
                $mamonhoc,
                $tende,
                $thoigianthi,
                $thoigianbatdau,
                $thoigianketthuc,
                $hienthibailam,
                $xemdiem,
                $xemdapan,
                $daocauhoi,
                $daodapan,
                $tudongnop,
                $loaide,
                $socaude,
                $socautb,
                $socaukho,
                $chuong,
                $manhom
            );

            if ($result) {
                $response['success'] = true;
            } else {
                $response['error'] = "Lỗi hệ thống khi cập nhật đề thi: " . mysqli_error($this->dethimodel->con);
                error_log($response['error']);
            }

            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'error' => 'Yêu cầu không hợp lệ hoặc không có quyền truy cập.']);
        }
    }
    public function getDetail()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && AuthCore::checkPermission("dethi", "view")) {
            $made = $_POST['made'];
            $result = $this->dethimodel->getById($made);
            echo json_encode($result);
        }
    }

    public function getTestGroup()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $manhom = $_POST['manhom'];
            $result = $this->dethimodel->getListTestGroup($manhom);
            echo json_encode($result);
        }
    }

    public function addDetail()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(['success' => false, 'error' => 'Yêu cầu không hợp lệ']);
            return;
        }

        $made = isset($_POST['made']) ? $_POST['made'] : null;
        $cauhoi = isset($_POST['cauhoi']) && is_array($_POST['cauhoi']) ? $_POST['cauhoi'] : [];

        error_log("POST data: made=$made, cauhoi=" . print_r($cauhoi, true));

        if (!$made || empty($cauhoi)) {
            echo json_encode(['success' => false, 'error' => 'Mã đề hoặc danh sách câu hỏi không hợp lệ']);
            return;
        }

        $result = $this->chitietde->createMultiple($made, $cauhoi);
        if ($result['valid']) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $result['error']]);
        }
    }

public function getQuestion()
{
    header('Content-Type: application/json; charset=utf-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'error' => 'Phương thức không hợp lệ']);
        exit;
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $made = $_POST['made'] ?? 0;
    
    error_log("API getQuestion called, made=$made");
    error_log("SESSION user_id = " . ($_SESSION['user_id'] ?? 'NULL'));

    $user = $_SESSION['user_id'] ?? null;

    // CHỈ SỬA CHỖ NÀY
    if (intval($made) <= 0 || empty($user)) {
        echo json_encode(['success' => false, 'error' => 'Mã đề hoặc người dùng không hợp lệ']);
        exit;
    }

    // không ép intval($user) nữa
    $result = $this->dethimodel->getQuestionByUser(intval($made), $user);
    echo json_encode($result);
    exit;
}



    public function getQuestionOfTestManual()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $made = $_POST['made'];
            $result = $this->dethimodel->getQuestionOfTestManual($made);
            echo json_encode($result);
        }
    }

    

public function getResultDetail()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        header('Content-Type: application/json; charset=utf-8');

        if (empty($_POST['makq'])) {
            echo json_encode(['success' => false, 'error' => 'Missing makq']);
            exit;
        }

        $makq = $_POST['makq'];

        try {
            $result = $this->dethimodel->getResultDetail($makq);
            echo json_encode(['success' => true, 'data' => $result]);
        } catch (\Throwable $e) {
            error_log('getResultDetail error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Lỗi server khi lấy chi tiết bài làm']);
        }

        exit;
    }
}



    public function startTest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $made = $_POST['made'];
            $user_id = $_SESSION['user_id'];
            $result = $this->ketquamodel->start($made, $user_id);
            $question = $this->dethimodel->getQuestionOfTest($made);
            echo json_encode($result);
        }
    }

    public function getTimeTest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dethi = $_POST['dethi'];
            $result = $this->dethimodel->getTimeTest($dethi, $_SESSION['user_id']);
            echo $result;
        }
    }

    public function getTimeEndTest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dethi = $_POST['dethi'];
            $result = $this->dethimodel->getTimeEndTest($dethi);
            echo $result;
        }
    }

    public function submit()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $listtr = $_POST['listCauTraLoi'];
            $thoigian = $_POST['thoigianlambai'];
            str_replace("(Indochina Time)", "(UTC+7:00)", $thoigian);
            $date = DateTime::createFromFormat('D M d Y H:i:s e+', $thoigian);
            $made = $_POST['made'];
            $nguoidung = $_SESSION['user_id'];
            $result = $this->ketquamodel->submit($made, $nguoidung, $listtr, $date->format('Y-m-d H:i:s'));
            echo $result;
        }
    }

    public function getDethi()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dethi = $_POST['made'];
            $result = $this->dethimodel->create_dethi($dethi);
            echo json_encode($result);
        }
    }

    public function tookTheExam()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $made = $_POST['made'];
            $result = $this->ketquamodel->tookTheExam($made);
            echo json_encode($result);
        }
    }

    public function getExamineeByGroup()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $made = $_POST['made'];
            $manhom = $_POST['manhom'];
            $result = $this->ketquamodel->getExamineeByGroup($made, $manhom);
            echo json_encode($result);
        }
    }

    public function getQuery($filter, $input, $args)
    {
        $result = $this->ketquamodel->getQuery($filter, $input, $args);
        return $result;
    }

    public function getStatictical()
    {
        $made = $_POST['made'];
        $manhom = $_POST['manhom'];
        $result = $this->ketquamodel->getStatictical($made, $manhom);
        echo json_encode($result);
    }
    //hàm chuyển tab trả về true nếu bảng dethi.solanchuyentab = 1 ngược lại thì false
    public function chuyentab()
    {
        $made = $_POST['made'];
        $id = $_SESSION['user_id'];
        $result = $this->ketquamodel->chuyentab($made, $id); // gọi hàm chuyentab ở dòng 380 của file KetquaModel.php
        echo $result;
    }

    public function exportPdf($makq)
    {
        $dompdf = new Dompdf();

        $info = $this->ketquamodel->getInfoPrintPdf($makq);
        $cauHoi = $this->dethimodel->getResultDetail($makq);
        $diem = $info['diemthi'] != "" ? $info['diemthi'] : 0;
        $socaudung = $info['socaudung'] != "" ? $info['socaudung'] : 0;
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style>
                * {padding: 0;margin: 0;box-sizing: border-box;}
                body{font-family: "Times New Roman", serif; padding: 50px 50px}
            </style>
        </head>
        <body>
            <table style="width:100%">
                <tr>
                    <td style="text-align: center;font-weight:bold">
                        DHT ONTEST<br>
                        Website tạo và quản lý bài thi<br><br><br>
                    </td>
                    <td style="text-align: center;">
                        <p style="font-weight:bold">' . mb_strtoupper($info['tende'], "UTF-8") . '</p>
                        <p style="font-weight:bold">Học phần: ' . $info['tenmonhoc'] . '</p>
                        <p style="font-weight:bold">Mã học phần: ' . $info['mamonhoc'] . '</p>
                        <p style="font-style:italic">Thời gian làm bài: ' . $info['thoigianthi'] . ' phút</p>
                    </td>
                </tr>
            </table>
            <table style="width:100%;margin-bottom:10px">
                <tr style="width:100%">
                    <td>Mã sinh viên: ' . $info['manguoidung'] . '</td>
                    <td>Tên thí sinh: ' . $info['hoten'] . '</td>
                </tr>
                <tr style="width:100%">
                    <td>Số câu đúng: ' . $socaudung . '/' . $info['tongsocauhoi'] . '</td>
                    <td>Điểm: ' . $diem . '</td>
                </tr>
            </table>       
            <hr>
            <div style="margin-top:20px">
        ';
        foreach ($cauHoi as $index => $ch) {
            $html .= '<li style="list-style:none"><strong>Câu ' . ($index + 1) . '</strong>: ' . $ch['noidung'] . '<ol type="A" style="margin-left:30px">';
            foreach ($ch['cautraloi'] as $ctl) {
                $dapAn = $ctl['ladapan'] == "1" ? " (Đáp án chính xác)" : "";
                $dapAnChon = $ctl['macautl'] == $ch['dapanchon'] ? " (Đáp án chọn)" : "";
                $html .= '<li>' . $ctl['noidungtl'] . $dapAnChon . $dapAn . '</li>';
            }

            $html .= '</ol></li>';
        }

        $html .= '
        </div>
        </body>
        </html>
        ';
        $dompdf->loadHtml($html, 'UTF-8');

        // Thiết lập kích thước giấy và hướng giấy
        $dompdf->setPaper('A4', 'portrait');

        // Xuất PDF
        $dompdf->render();
        $output = $dompdf->output();
        echo base64_encode($output);
    }

    public function exportExcel()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $made = $_POST['made'];
            $manhom = $_POST['manhom'];
            $ds = $_POST['ds'];
            $result = $this->ketquamodel->getTestScoreGroup($made, $manhom);
            if ($manhom == 0) {
                $result = $this->ketquamodel->getTestAll($made, $ds);
            }
            //Khởi tạo đối tượng
            $excel = new PHPExcel();
            //Chọn trang cần ghi (là số từ 0->n)
            $excel->setActiveSheetIndex(0);
            //Tạo tiêu đề cho trang. (có thể không cần)
            $excel->getActiveSheet()->setTitle("Danh sách kết quả");

            //Xét chiều rộng cho từng, nếu muốn set height thì dùng setRowHeight()
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $excel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);


            //Xét in đậm cho khoảng cột
            $phpColor = new PHPExcel_Style_Color();
            $phpColor->setRGB('FFFFFF');
            $excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setColor($phpColor);
            $excel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '33FF33')
                    )
                )
            );
            $excel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
            );
            $excel->getActiveSheet()->setCellValue('A1', 'MSSV');
            $excel->getActiveSheet()->setCellValue('B1', 'Họ và tên');
            $excel->getActiveSheet()->setCellValue('C1', 'Điểm thi');
            $excel->getActiveSheet()->setCellValue('D1', 'Thời gian vào thi');
            $excel->getActiveSheet()->setCellValue('E1', 'Thời gian làm bài');
            $excel->getActiveSheet()->setCellValue('F1', 'Số câu đúng');
            $excel->getActiveSheet()->setCellValue('G1', 'Số lần chuyển Tab');
            // thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
            // dòng bắt đầu = 2
            $numRow = 2;
            foreach ($result as $row) {
                $excel->getActiveSheet()->setCellValue('A' . $numRow, $row["manguoidung"]);
                $excel->getActiveSheet()->setCellValue('B' . $numRow, $row["hoten"]);
                $excel->getActiveSheet()->setCellValue('C' . $numRow, $row["diemthi"] == "" ? "0" : $row["diemthi"]);
                $excel->getActiveSheet()->setCellValue('D' . $numRow, $row["thoigianvaothi"] == "" ? "0" : $row["thoigianvaothi"]);
                $excel->getActiveSheet()->setCellValue('E' . $numRow, $row["thoigianlambai"] == "" ? "0" : $row["thoigianlambai"]);
                $excel->getActiveSheet()->setCellValue('F' . $numRow, $row["socaudung"] == "" ? "0" : $row["socaudung"]);
                $excel->getActiveSheet()->setCellValue('G' . $numRow, $row["solanchuyentab"] == "" ? "0" : $row["solanchuyentab"]);
                $excel->getActiveSheet()->getStyle("A".$numRow.":G"."$numRow")->getAlignment()->applyFromArray(
                    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                );
                ;
                $numRow++;
            }
            ob_start();
            $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $write->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
            $response =  array(
                'status' => true,
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );

            die(json_encode($response));
        }
    }

    public function getMarkOfAllTest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $manhom = $_POST['manhom'];
            $result = $this->ketquamodel->getMarkOfAllTest($manhom);
            $excel = new PHPExcel();
            //Chọn trang cần ghi (là số từ 0->n)
            $excel->setActiveSheetIndex(0);
            //Tạo tiêu đề cho trang. (có thể không cần)
            $excel->getActiveSheet()->setTitle("Danh sách kết quả");

            //Xét chiều rộng cho từng, nếu muốn set height thì dùng setRowHeight()
            $end = $this->toAlpha(count($result[0]) - 1);
            for ($x = 0; $x < count($result[0]); $x++) {
                $excel->getActiveSheet()->getColumnDimension($this->toAlpha($x))->setWidth(25);
            }
            //Xét in đậm cho khoảng cột
            $phpColor = new PHPExcel_Style_Color();
            $phpColor->setRGB('FFFFFF');
            $excel->getActiveSheet()->getStyle("A1:".($end)."1")->getFont()->setBold(true);
            $excel->getActiveSheet()->getStyle("A1:".($end)."1")->getFont()->setColor($phpColor);
            $excel->getActiveSheet()->getStyle("A1:".($end)."1")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '33FF33')
                    )
                )
            );

            $excel->getActiveSheet()->getStyle("A1:".($end)."1")->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
            );

            for ($x = 0; $x < count($result[0]); $x++) {
                $excel->getActiveSheet()->setCellValue($this->toAlpha($x)."1", $result[0][$x]);
            }

            // thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
            // dòng bắt đầu = 2
            $numRow = 2;
            for ($x = 1; $x < count($result); $x++) {
                for ($y = 0;$y < count($result[$x]);$y++) {
                    $excel->getActiveSheet()->setCellValue($this->toAlpha($y) . $numRow, $result[$x][$y] == "" ? "0" : $result[$x][$y]);
                }
                $excel->getActiveSheet()->getStyle("A".$numRow.":G"."$numRow")->getAlignment()->applyFromArray(
                    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
                );
                ;
                $numRow++;
            }
            ob_start();
            $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $write->save('php://output');
            $xlsData = ob_get_contents();
            ob_end_clean();
            $response =  array(
                'status' => true,
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
            );
            die(json_encode($response));
        }
    }

    public function toAlpha($num)
    {
        return chr(substr("000".($num + 65), -3));
    }

    public function check()
    {
        $result = $this->ketquamodel->getMarkOfAllTest(2);
        echo "</br>";
        print_r($result);
    }

    public function getGroupsTakeTests()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tests = $_POST["tests"];
            $result = $this->dethimodel->getGroupsTakeTests($tests);
            echo json_encode($result);
        }
    }

    public function getTestsGroupWithUserResult()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $manhom = $_POST['manhom'];
            $result = $this->dethimodel->getTestsGroupWithUserResult($manhom, $_SESSION['user_id']);
            echo json_encode($result);
        }
    }
}
