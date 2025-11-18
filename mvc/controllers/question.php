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
   
// Hàm phụ parse MCQ (dùng chung cho cả MCQ thường và câu hỏi con Reading)
private function parseMCQBlock($lines)
{
    $questions = [];
    $current = null;

    foreach ($lines as $line) {
        $line = trim($line);

        // Bắt đầu câu hỏi mới
        if (preg_match('/^(\[|\d+[\.\)])\s*(Level:\s*\d+\s*)?(.*)/i', $line, $m)) {
            if ($current !== null) {
                if (empty($current['answer'])) $current['answer'] = 1; // mặc định A nếu thiếu
                $questions[] = $current;
            }

            $level = 1;
            if (preg_match('/Level:\s*(\d+)/i', $line, $lm)) $level = (int)$lm[1];

            $questionText = trim(preg_replace('/^(Câu\s*\d+[:\.]|\[\d+\]|\d+[\.\)])\s*(Level:\s*\d+\s*)?/i', '', $line));

            $current = [
                'type' => 'mcq',
                'level' => $level,
                'question' => $questionText,
                'option' => [],
                'answer' => 0
            ];
            continue;
        }

        // Đáp án A, B, C, D
        if (preg_match('/^([A-D])\.\s*(.+)/i', $line, $m)) {
            if ($current) {
                $current['option'][] = $m[2];
            }
            continue;
        }

        // Đáp án đúng
        if (preg_match('/^ANSWER:\s*([A-D])/i', $line, $m)) {
            if ($current) {
                $current['answer'] = ord($m[1]) - 65 + 1; // A=1, B=2...
            }
            continue;
        }

        // Nếu dòng không phải đáp án mà chưa có nội dung câu hỏi → thêm vào câu hỏi
        if ($current && !empty($line) && empty($current['option'])) {
            $current['question'] .= " " . $line;
            $current['question'] = trim($current['question']);
        }
    }

    if ($current !== null) {
        if (empty($current['answer'])) $current['answer'] = 1;
        $questions[] = $current;
    }

    return $questions;
}
public function xulyDocx()
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST" || !AuthCore::checkPermission("cauhoi", "create")) {
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }

    require_once 'vendor/autoload.php';

    $file = $_FILES["fileToUpload"]["tmp_name"];
    if (!file_exists($file)) {
        echo json_encode(['error' => 'File not found']);
        return;
    }

    $phpWord = \PhpOffice\PhpWord\IOFactory::load($file);
    $text = "";

    // Recursive extractor (giữ nguyên, rất ổn)
    $extractElementText = null;
    $extractElementText = function($el) use (&$extractElementText) {
        $out = '';
        if (is_string($el)) return $el;
        if (is_object($el) && method_exists($el, 'getText')) {
            try {
                $val = call_user_func([$el, 'getText']);
                if (is_string($val)) $out .= $val;
            } catch (\Throwable $t) {}
        }
        if (is_object($el) && method_exists($el, 'getElements')) {
            foreach ($el->getElements() as $child) {
                $out .= $extractElementText($child);
            }
        }
        return $out;
    };

    foreach ($phpWord->getSections() as $section) {
        foreach ($section->getElements() as $e) {
            $part = $extractElementText($e);
            if ($part !== '') {
                $text .= trim($part) . "\n";
            }
        }
    }

    // Chuẩn hóa dòng: bỏ dòng trống hoàn toàn, trim kỹ
    $lines = array_values(array_filter(array_map(function($line) {
        $line = trim($line);
        return $line === '' ? false : $line;
    }, preg_split("/\r\n|\r|\n/", $text))));

    $result = [];
    $i = 0;

    while ($i < count($lines)) {
        $line = $lines[$i];

        // ============================================================
        // 1) READING FORMAT MỚI (hỗ trợ tối đa mọi kiểu người dùng viết)
        // ============================================================
        if (preg_match('/^\[Reading\]\[(\d+)\](.*)$/u', $line, $m)) {
            $level = (int)$m[1];
            $passage = trim($m[2]); // có thể rỗng nếu passage ở dòng sau

            $i++;

            // Gom passage: tiếp tục lấy các dòng CHO ĐẾN KHI gặp dấu hiệu của câu hỏi hoặc đáp án
            while ($i < count($lines)) {
                $nextLine = $lines[$i];

                // Dừng gom passage nếu gặp:
                // - Câu hỏi (kết thúc bằng ?)
                // - Đáp án A. B. C. D.
                // - ANSWER:
                // - [Reading] mới
                if (preg_match('/\?$/', $nextLine) ||
                    preg_match('/^[ABCD]\.\s+/ui', $nextLine) ||
                    preg_match('/^ANSWER:/ui', $nextLine) ||
                    preg_match('/^\[Reading\]/ui', $nextLine)) {
                    break;
                }

                $passage .= " " . trim($nextLine);
                $i++;
            }

            // Chuẩn hóa passage: nhiều khoảng trắng → 1 khoảng trắng, trim lại
            $passage = preg_replace('/\s+/', ' ', trim($passage));

            // Gom câu hỏi con
            $subQuestions = [];
            while ($i < count($lines)) {
                // Nếu gặp Reading mới → dừng lại, để vòng while chính xử lý
                if (preg_match('/^\[Reading\]/ui', $lines[$i])) {
                    break;
                }

                // Bỏ qua dòng trống hoặc dòng không phải câu hỏi
                if (!preg_match('/\?$/', $lines[$i])) {
                    $i++;
                    continue;
                }

                $questionText = trim($lines[$i]);
                $i++;

                $opts = [];
                $correct = "";

                while ($i < count($lines)) {
                    $l = trim($lines[$i]);

                    // Gặp câu hỏi mới hoặc Reading mới → dừng gom đáp án
                    if (preg_match('/\?$/', $l) || preg_match('/^\[Reading\]/ui', $l)) {
                        break;
                    }

                    if (preg_match('/^A[\.\)]\s*(.+)$/ui', $l, $mm)) $opts['A'] = trim($mm[1]);
                    if (preg_match('/^B[\.\)]\s*(.+)$/ui', $l, $mm)) $opts['B'] = trim($mm[1]);
                    if (preg_match('/^C[\.\)]\s*(.+)$/ui', $l, $mm)) $opts['C'] = trim($mm[1]);
                    if (preg_match('/^D[\.\)]\s*(.+)$/ui', $l, $mm)) $opts['D'] = trim($mm[1]);
                    if (preg_match('/^ANSWER:\s*([ABCD])/ui', $l, $mm)) $correct = $mm[1];

                    $i++;
                }

                // Chỉ thêm câu hỏi nếu có ít nhất 3 đáp án và có ANSWER
                if (count($opts) >= 3 && $correct !== "") {
                    $subQuestions[] = [
                        "type" => "reading",
                        "level" => $level,
                        "question" => $questionText,
                        "option" => array_values($opts),
                        "answer" => array_search($correct, array_keys($opts)) + 1
                    ];
                }
            }

            $result[] = [
                "type" => "reading",
                "passage" => $passage,
                "questions" => $subQuestions
            ];

            // Quan trọng: không $i++ ở cuối vòng while chính khi dùng continue
            continue;
        }

        // ============================================================
        // 2) MCQ THƯỜNG (câu hỏi độc lập, không nằm trong Reading)
        // ============================================================
        if (preg_match('/\?$/', $line)) {
            $question = trim($line);
            $i++;

            $opts = [];
            $correct = "";

            while ($i < count($lines)) {
                $l = trim($lines[$i]);

                if (preg_match('/\?$/', $l) || preg_match('/^\[Reading\]/ui', $l)) {
                    break;
                }

                
                if (preg_match('/^A[\.\)]\s*(.+)$/ui', $l, $mm)) $opts['A'] = trim($mm[1]);
                if (preg_match('/^B[\.\)]\s*(.+)$/ui', $l, $mm)) $opts['B'] = trim($mm[1]);
                if (preg_match('/^C[\.\)]\s*(.+)$/ui', $l, $mm)) $opts['C'] = trim($mm[1]);
                if (preg_match('/^D[\.\)]\s*(.+)$/ui', $l, $mm)) $opts['D'] = trim($mm[1]);
                if (preg_match('/^ANSWER:\s*([ABCD])/ui', $l, $mm)) $correct = $mm[1];

                $i++;
            }

            if (count($opts) >= 3 && $correct !== "") {
                $result[] = [
                    "type" => "mcq",
                    "level" => 1,
                    "question" => $question,
                    "option" => array_values($opts),
                    "answer" => array_search($correct, array_keys($opts)) + 1
                ];
            }

            continue;
        }

        // Nếu không match gì → bỏ qua dòng này
        $i++;
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}


public function addQuesFile()
{
    header('Content-Type: application/json; charset=utf-8');

    if (!AuthCore::checkPermission("cauhoi", "create") || $_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        return;
    }

    $nguoitao = $_SESSION['user_id'];
    $monhoc   = $_POST['monhoc'] ?? null;
    $chuong   = $_POST['chuong'] ?? null;
    $items    = json_decode($_POST['questions'] ?? '[]', true);

    if (!$monhoc || !$chuong || !is_array($items)) {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
        return;
    }

    $this->cauHoiModel->con->begin_transaction();
    $inserted = 0;
    $errors = [];

    try {
        foreach ($items as $idx => $item) {
            // READING
            if ($item["type"] === "reading") {
                $passage = trim($item["passage"] ?? '');
                if (empty($passage)) {
                    $errors[] = "Mục $idx: Đoạn văn trống";
                    continue;
                }

                // Tạo doan_van
                $madv = $this->cauHoiModel->createWithDoanVan(
                    "",
                    1,
                    $monhoc,
                    $chuong,
                    $nguoitao,
                    "reading",
                    $passage,
                    $item["title"] ?? null
                );

                if (!$madv) {
                    $errors[] = "Mục $idx: Không thể tạo đoạn văn";
                    continue;
                }

                // Thêm câu hỏi con
                $subQuestions = $item["questions"] ?? [];
                foreach ($subQuestions as $subIdx => $sub) {
                    $subContent = trim($sub["question"] ?? '');
                    if (empty($subContent)) continue;

                    $subLevel = $sub["level"] ?? 1;
                    $macauhoi = $this->cauHoiModel->create(
                        $subContent,
                        $subLevel,
                        $monhoc,
                        $chuong,
                        $nguoitao,
                        "reading",
                        $madv
                    );

                    if (!$macauhoi) {
                        $errors[] = "Mục $idx câu $subIdx: Không thể tạo câu hỏi con";
                        continue;
                    }

                    // Thêm đáp án
                    $options = $sub["option"] ?? [];
                    foreach ($options as $optIdx => $opt) {
                        $optContent = trim($opt ?? '');
                        if (empty($optContent)) continue;

                        $isCorrect = ($optIdx + 1 == $sub["answer"]) ? 1 : 0;
                        if (!$this->cauTraLoiModel->create($macauhoi, $optContent, $isCorrect)) {
                            $errors[] = "Mục $idx câu $subIdx đáp án $optIdx: Lỗi thêm đáp án";
                        }
                    }

                    $inserted++;
                }

                continue;
            }

            // MCQ
            if ($item["type"] === "mcq") {
                $qContent = trim($item["question"] ?? '');
                if (empty($qContent)) {
                    $errors[] = "Mục $idx: Câu hỏi trống";
                    continue;
                }

                $macauhoi = $this->cauHoiModel->create(
                    $qContent,
                    $item["level"] ?? 1,
                    $monhoc,
                    $chuong,
                    $nguoitao,
                    "mcq"
                );

                if (!$macauhoi) {
                    $errors[] = "Mục $idx: Không thể tạo câu hỏi";
                    continue;
                }

                $options = $item["option"] ?? [];
                foreach ($options as $optIdx => $opt) {
                    $optContent = trim($opt ?? '');
                    if (empty($optContent)) continue;

                    $isCorrect = ($optIdx + 1 == $item["answer"]) ? 1 : 0;
                    if (!$this->cauTraLoiModel->create($macauhoi, $optContent, $isCorrect)) {
                        $errors[] = "Mục $idx đáp án $optIdx: Lỗi thêm đáp án";
                    }
                }

                $inserted++;
            }
        }

        $this->cauHoiModel->con->commit();

        echo json_encode([
            'status' => 'success',
            'inserted' => $inserted,
            'message' => "Đã thêm $inserted câu hỏi!",
            'errors' => $errors
        ]);

    } catch (Exception $e) {
        $this->cauHoiModel->con->rollback();
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
            'errors' => $errors
        ]);
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
