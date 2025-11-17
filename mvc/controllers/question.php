<?php

use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;

class Question extends Controller
{
    public $cauHoiModel;
    public $cauTraLoiModel;

    public function __construct()
    {
        $this->cauHoiModel = $this->model("CauHoiModel");
        $this->cauTraLoiModel = $this->model("CauTraLoiModel");
        parent::__construct();
        require_once "./mvc/core/Pagination.php";
    }

    public function default()
    {
        if (AuthCore::checkPermission("cauhoi", "view")) {
            $this->view("main_layout", [
                "Page" => "question",
                "Title" => "Câu hỏi",
                "Plugin" => [
                    "ckeditor" => 1,
                    "select" => 1,
                    "notify" => 1,
                    "sweetalert2" => 1,
                    "pagination" => [],
                    "jquery-validate" => 1,
                ],
                "Script" => "question",
                "user_id" => $_SESSION['user_id'],
            ]);
        } else {
            $this->view("single_layout", ["Page" => "error/page_404","Title" => "Lỗi !"]);
        }
    }

    public function xulyDocx()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && AuthCore::checkPermission("cauhoi", "create")) {
            require_once 'vendor/autoload.php';
            $filename = $_FILES["fileToUpload"]["tmp_name"];
            $objReader = WordIOFactory::createReader('Word2007');
            $phpWord = $objReader->load($filename);
            $text = '';
            // Lấy kí tự từng đoạn
            function getWordText($element)
            {
                $result = '';
                if ($element instanceof AbstractContainer) {
                    foreach ($element->getElements() as $element) {
                        $result .= getWordText($element);
                    }
                } elseif ($element instanceof Text) {
                    $result .= $element->getText();
                }
                return $result;
            }

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    $text .= trim(getWordText($element));
                    $text .= "\\n";
                }
            }

            $text = rtrim($text, "\\n");
            substr($text, -1);
            $questions = explode("\\n\\n", $text);
            $arrques = array();
            for ($i = 0; $i < count($questions); $i++) {
                $data = explode("\\n", $questions[$i]);
                $arrques[$i]['level'] = substr($data[0], 1, 1);
                $arrques[$i]['question'] = substr(trim($data[0]), 4);
                $arrques[$i]['answer'] = ord(trim(substr($data[count($data) - 1], 8))) - 65 + 1;
                $arrques[$i]['option'] = array();
                for ($j = 1; $j < count($data) - 1; $j++) {
                    $arrques[$i]['option'][] = trim(substr($data[$j], 3));
                }
            }
            echo json_encode($arrques);
        }
    }


    public function addExcel()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && AuthCore::checkPermission("cauhoi", "create")) {
            require_once 'vendor/autoload.php';
            $inputFileName = $_FILES["fileToUpload"]["tmp_name"];
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Lỗi không thể đọc file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }
            $sheet = $objPHPExcel->setActiveSheetIndex(0);
            $Totalrow = $sheet->getHighestRow();
            $LastColumn = $sheet->getHighestColumn();
            $TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
            $data = [];
            for ($i = 2; $i <= $Totalrow; $i++) {
                //----Lặp cột
                for ($j = 0; $j < $TotalCol; $j++) {
                    // Tiến hành lấy giá trị của từng ô đổ vào mảng
                    $check = '';
                    if ($j == 0) {
                        $check = "level";
                        $data[$i - 2][$check] = $sheet->getCellByColumnAndRow($j, $i)->getValue();
                    } elseif ($j == 1) {
                        $check = "question";
                        $data[$i - 2][$check] = $sheet->getCellByColumnAndRow($j, $i)->getValue();
                    } elseif ($j == $TotalCol - 1) {
                        $check = "answer";
                        $data[$i - 2][$check] = $sheet->getCellByColumnAndRow($j, $i)->getValue();
                    } else {
                        $check = "option";
                        $data[$i - 2][$check][] = $sheet->getCellByColumnAndRow($j, $i)->getValue();
                    }
                }
            }
            echo json_encode($data);
        }
    }
  public function addQues()
{
    header('Content-Type: application/json; charset=utf-8'); // đảm bảo JSON trả về đúng header

    if (!AuthCore::checkPermission("cauhoi", "create")) {
        echo json_encode(["status" => "error", "message" => "Không có quyền thêm câu hỏi"]);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["status" => "error", "message" => "Phải gửi POST"]);
        return;
    }

    $mamon      = $_POST['mamon'] ?? null;
    $machuong   = $_POST['machuong'] ?? null;
    $dokho      = $_POST['dokho'] ?? null;
    $noidungCH  = trim(strip_tags($_POST['noidung'] ?? ''));
    $loai       = $_POST['loai'] ?? 'mcq';
    $nguoitao   = $_SESSION['user_id'] ?? null;
    $madv       = null;

    $cautraloi_json  = $_POST['cautraloi'] ?? '[]';
    $noidungDV       = trim(strip_tags($_POST['doanvan_noidung'] ?? ''));
    $tieudeDV        = trim(strip_tags($_POST['doanvan_tieude'] ?? ''));

    // Validate dữ liệu cơ bản
    if (!$mamon || !$machuong || !$dokho) {
        echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ môn học, chương và độ khó"]);
        return;
    }

    // Giải mã JSON đáp án
    $cautraloi = json_decode($cautraloi_json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["status" => "error", "message" => "Dữ liệu đáp án không hợp lệ"]);
        return;
    }

    try {
        switch ($loai) {
            case 'mcq':
            case 'essay':
                if ($noidungCH === '') {
                    echo json_encode(["status" => "error", "message" => "Vui lòng nhập nội dung câu hỏi"]);
                    return;
                }

                $macauhoi = $this->cauHoiModel->create($noidungCH, $dokho, $mamon, $machuong, $nguoitao, $loai, $madv);

                if (!empty($cautraloi)) {
                    foreach ($cautraloi as $x) {
                        $content = trim(strip_tags($x['content'] ?? ''));
                        if ($content === '') continue;

                        $check = 0;
                        if ($loai === 'mcq') {
                            $check = isset($x['check']) && ($x['check'] === 'true' || $x['check'] == 1) ? 1 : 0;
                        }
                        $this->cauTraLoiModel->create($macauhoi, $content, $check);
                    }
                }
                break;

            case 'reading':
    if ($noidungDV === '') {
        echo json_encode(["status" => "error", "message" => "Vui lòng nhập nội dung đoạn văn"]);
        return;
    }

    if (empty($cautraloi)) {
        echo json_encode(["status" => "error", "message" => "Vui lòng thêm ít nhất một câu hỏi con"]);
        return;
    }

    // SỬA TẠI ĐÂY: đổi tên biến cho rõ nghĩa
    $madv = $this->cauHoiModel->createWithDoanVan('', $dokho, $mamon, $machuong, $nguoitao, $loai, $noidungDV, $tieudeDV);

    foreach ($cautraloi as $subQuestion) {
        $subContent = trim(strip_tags($subQuestion['content'] ?? ''));
        if ($subContent === '') continue;

        // SỬA TẠI ĐÂY: dùng $madv (của doan_van) thay vì $macauhoi
        $subMacauhoi = $this->cauHoiModel->create($subContent, $dokho, $mamon, $machuong, $nguoitao, 'mcq', $madv);

        foreach ($subQuestion['options'] as $option) {
            $content = trim(strip_tags($option['content'] ?? ''));
            if ($content === '') continue;
            $check = isset($option['check']) && ($option['check'] === 'true' || $option['check'] == 1) ? 1 : 0;
            $this->cauTraLoiModel->create($subMacauhoi, $content, $check);
        }
    }
    break;

            default:
                echo json_encode(["status" => "error", "message" => "Loại câu hỏi không hợp lệ"]);
                return;
        }

        echo json_encode([
            "status"   => "success",
            "message"  => "Thêm câu hỏi thành công",
            //"macauhoi" => $macauhoi,
            "loai"     => $loai
        ]);
    } catch (\Exception $e) {
        // Bắt mọi lỗi PHP / DB và trả JSON
        echo json_encode([
            "status"  => "error",
            "message" => "Lỗi hệ thống: " . $e->getMessage()
        ]);
    }
}

public function editQuesion()
{
    if (!AuthCore::checkPermission("cauhoi", "update")) {
        echo json_encode(["status" => "error", "message" => "Không có quyền chỉnh sửa"]);
        return;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'] ?? null;
        $mamon = $_POST['mamon'] ?? null;
        $machuong = $_POST['machuong'] ?? null;
        $dokho = $_POST['dokho'] ?? null;
        $nguoitao = $_SESSION['user_id'];
        $noidungRaw = $_POST['noidung'] ?? '';
        $loai = $_POST['loai'] ?? 'mcq';
        $cautraloi_json = $_POST['cautraloi'] ?? '[]';
        $tieudeDV = trim(strip_tags($_POST['doanvan_tieude'] ?? ''));

        // Giải mã cautraloi
        $cautraloi = json_decode($cautraloi_json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["status" => "error", "message" => "Dữ liệu đáp án không hợp lệ"]);
            return;
        }

        // Kiểm tra cơ bản
        if (!$id || !$mamon || !$machuong || !$dokho) {
            echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ thông tin"]);
            return;
        }

        try {
            $question = $this->cauHoiModel->getById($id);

            if ($loai === 'reading') {
                $madv = $question['madv'];
                $noidungDV = trim(strip_tags($noidungRaw));

                if ($noidungDV === '') {
                    echo json_encode(["status" => "error", "message" => "Vui lòng nhập nội dung đoạn văn"]);
                    return;
                }
                if (empty($cautraloi)) {
                    echo json_encode(["status" => "error", "message" => "Vui lòng thêm ít nhất một câu hỏi con"]);
                    return;
                }

                // Update doan_van
                $sql = "UPDATE doan_van SET noidung = ?, tieude = ?, mamonhoc = ?, machuong = ?, nguoitao = ? WHERE madv = ?";
                $stmt = $this->cauHoiModel->con->prepare($sql);
                mysqli_stmt_bind_param($stmt, "sssssi", $noidungDV, $tieudeDV, $mamon, $machuong, $nguoitao, $madv);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Xóa câu hỏi con và đáp án cũ
                $subQuestions = $this->cauHoiModel->getSubQuestions($madv);
                foreach ($subQuestions as $sub) {
                    $this->cauTraLoiModel->deletebyanswer($sub['macauhoi']);
                    $this->cauHoiModel->delete($sub['macauhoi']);
                }

                // Thêm câu hỏi con mới
                foreach ($cautraloi as $subQuestion) {
                    $subContent = trim(strip_tags($subQuestion['content'] ?? ''));
                    if ($subContent === '') continue;

                    $subMacauhoi = $this->cauHoiModel->create($subContent, $dokho, $mamon, $machuong, $nguoitao, 'mcq', $madv);
                    if (!$subMacauhoi) {
                        throw new Exception("Không thể tạo câu hỏi con");
                    }

                    foreach ($subQuestion['options'] as $option) {
                        $content = trim(strip_tags($option['content'] ?? ''));
                        if ($content === '') continue;
                        $check = isset($option['check']) && ($option['check'] === 'true' || $option['check'] == 1) ? 1 : 0;
                        if (!$this->cauTraLoiModel->create($subMacauhoi, $content, $check)) {
                            throw new Exception("Không thể thêm đáp án cho câu hỏi con");
                        }
                    }
                }

                // Update câu hỏi chính
                $this->cauHoiModel->update($id, '', $dokho, $mamon, $machuong, $nguoitao, $loai, $madv);
            } else {
                $noidung = trim(strip_tags($noidungRaw));
                if ($noidung === '') {
                    echo json_encode(["status" => "error", "message" => "Vui lòng nhập nội dung câu hỏi"]);
                    return;
                }
                if ($loai === 'mcq' && empty($cautraloi)) {
                    echo json_encode(["status" => "error", "message" => "Vui lòng thêm ít nhất một đáp án"]);
                    return;
                }

                $validAnswers = [];
                foreach ($cautraloi as $x) {
                    $content = trim(strip_tags($x['content']));
                    if ($content !== '') {
                        $isChecked = isset($x['check']) && ($x['check'] === 'true' || $x['check'] == 1 || $x['check'] === true);
                        $validAnswers[] = [
                            'content' => $content,
                            'check' => $isChecked ? 1 : 0
                        ];
                    }
                }
                if ($loai === 'mcq' && count($validAnswers) === 0) {
                    echo json_encode(["status" => "error", "message" => "Vui lòng thêm ít nhất một đáp án hợp lệ"]);
                    return;
                }

                $result = $this->cauHoiModel->update($id, $noidung, $dokho, $mamon, $machuong, $nguoitao, $loai);
                if ($result) {
                    $this->cauTraLoiModel->deletebyanswer($id);
                    foreach ($validAnswers as $ans) {
                        $this->cauTraLoiModel->create($id, $ans['content'], $ans['check']);
                    }
                }
            }

            echo json_encode([
                "status" => "success",
                "message" => "Chỉnh sửa câu hỏi thành công"
            ]);
        } catch (\Exception $e) {
            error_log("Error in editQuesion: " . $e->getMessage());
            echo json_encode([
                "status" => "error",
                "message" => "Lỗi hệ thống: " . $e->getMessage()
            ]);
        }
    }
}
    public function addQuesFile()
    {
        if (AuthCore::checkPermission("cauhoi", "create")) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nguoitao = $_SESSION['user_id'];
                $monhoc   = $_POST['monhoc'];
                $chuong   = $_POST['chuong'];
                $questions = $_POST["questions"];

                foreach ($questions as $question) {
                    $level   = (int)$question['level']; // dùng luôn từ request
                    $noidung = $question['question'];
                    $answer  = (int)$question['answer']; // 1-4
                    $options = $question['option'];

                    // Thêm câu hỏi và lấy ID (file import assumed MCQ)
                    $macauhoi = $this->cauHoiModel->create($noidung, $level, $monhoc, $chuong, $nguoitao, 'mcq');
                    if (!$macauhoi) {
                        continue; // insert lỗi thì bỏ qua
                    }

                    // Thêm đáp án
                    $index = 1;
                    foreach ($options as $option) {
                        $check = ($index == $answer) ? 1 : 0;
                        $this->cauTraLoiModel->create($macauhoi, $option, $check);
                        $index++;
                    }
                }

                echo json_encode([
                    'status' => 'success',
                    'inserted' => count($questions)
                ]);
            }
        }
    }


    public function getQuestion()
    {
        if (AuthCore::checkPermission("cauhoi", "view")) {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $result = $this->cauHoiModel->getAll();
                echo json_encode($result);
            }
        }
    }

   public function delete()
{
    if (AuthCore::checkPermission("cauhoi", "delete")) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['macauhoi'];
            $question = $this->cauHoiModel->getById($id);

            // Xóa mềm câu hỏi chính
            $this->cauHoiModel->delete($id);

            // Nếu là reading → xóa mềm luôn các câu hỏi con + xóa đoạn văn
            if ($question['loai'] === 'reading' && $question['madv']) {
                // Xóa mềm các câu hỏi con
                $sql = "UPDATE cauhoi SET trangthai = 0 WHERE madv = ?";
                $stmt = $this->cauHoiModel->con->prepare($sql);
                $stmt->bind_param("i", $question['madv']);
                $stmt->execute();
                $stmt->close();

                // Xóa đoạn văn (cứng hoặc mềm tùy ý, hiện tại xóa cứng)
                $sql2 = "UPDATE doan_van SET trangthai = 0 WHERE madv = ?";
                $stmt2 = $this->cauHoiModel->con->prepare($sql2);
                $stmt2->bind_param("i", $question['madv']);
                $stmt2->execute();
                $stmt2->close();
            }
        }
    }
}



   public function getQuestionById()
{
    if (AuthCore::checkPermission("cauhoi", "view")) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];
            $result = $this->cauHoiModel->getById($id);

            if ($result['loai'] === 'reading') {
                $doanvan = $this->cauHoiModel->getWithDoanVan($id);
                $result['noidung'] = $doanvan['doanvan_noidung'] ?? '';
                $result['tieude'] = $doanvan['doanvan_tieude'] ?? '';
                $result['madv'] = $doanvan['madv'] ?? null;
            }

            echo json_encode($result);
        }
    }
}

public function getAnswerById()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $question = $this->cauHoiModel->getById($id);

        if ($question['loai'] === 'reading') {
            $madv = $question['madv'];
            $subQuestions = $this->cauHoiModel->getSubQuestions($madv); 

            foreach ($subQuestions as &$sub) {
                $sub['options'] = $this->cauTraLoiModel->getAll($sub['macauhoi']);
            }

            echo json_encode($subQuestions);
        } else {
            $result = $this->cauTraLoiModel->getAll($id);
            echo json_encode($result);
        }
    }
}

    public function getTotalPage()
    {
        AuthCore::checkAuthentication();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $content = $_POST['content'];
            $select = $_POST['selected'];
            echo $this->cauHoiModel->getTotalPage($content);
        }
    }

    public function getQuestionBySubject()
    {
        AuthCore::checkAuthentication();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $mamonhoc = $_POST['mamonhoc'];
            $machuong = $_POST['machuong'];
            $dokho = $_POST['dokho'];
            $content = $_POST['content'];
            $page = $_POST['page'];
            $result = $this->cauHoiModel->getQuestionBySubject($mamonhoc, $machuong, $dokho, $content, $page);
            echo json_encode($result);
        }
    }

    public function getTotalPageQuestionBySubject()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $mamonhoc = $_POST['mamonhoc'];
            $machuong = $_POST['machuong'];
            $dokho = $_POST['dokho'];
            $content = $_POST['content'];
            $result = $this->cauHoiModel->getTotalPageQuestionBySubject($mamonhoc, $machuong, $dokho, $content);
            echo $result;
        }
    }

    public function getQuery($filter, $input, $args)
    {
        $result = $this->cauHoiModel->getQuery($filter, $input, $args);
        return $result;
    }

    public function getAnswersForMultipleQuestions()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $arr_question_id = $_POST['questions'];
            $result = $this->cauTraLoiModel->getAnswersForMultipleQuestions($arr_question_id);
            echo json_encode($result);
        }
    }

    public function getsoluongcauhoi()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $chuong = isset($_POST['chuong']) ? $_POST['chuong'] : array();
            $monhoc = $_POST['monhoc'];
            $dokho = $_POST['dokho'];
            $result = $this->cauHoiModel->getsoluongcauhoi($chuong, $monhoc, $dokho);
            echo $result;
        }
    }
}
