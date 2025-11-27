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
                    if (empty($current['answer'])) {
                        $current['answer'] = 1;
                    } // mặc định A nếu thiếu
                    $questions[] = $current;
                }

                $level = 1;
                if (preg_match('/Level:\s*(\d+)/i', $line, $lm)) {
                    $level = (int)$lm[1];
                }

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
            if (empty($current['answer'])) {
                $current['answer'] = 1;
            }
            $questions[] = $current;
        }

        return $questions;
    }



    // === THAY TOÀN BỘ HÀM xulyDocx() BẰNG ĐOẠN NÀY ===
    public function xulydoanvan()
    {
        ob_clean(); // xóa sạch mọi output rác
        header("Content-Type: application/json; charset=utf-8");

        try {

            // ============= CHECK FILE =============
            if ($_SERVER["REQUEST_METHOD"] !== "POST" || !AuthCore::checkPermission("cauhoi", "create")) {
                throw new Exception("Unauthorized");
            }

            require_once 'vendor/autoload.php';

            if (!isset($_FILES["fileToUpload"]["tmp_name"])) {
                throw new Exception("No file uploaded");
            }

            $file = $_FILES["fileToUpload"]["tmp_name"];
            if (!file_exists($file)) {
                throw new Exception("File not found");
            }

            // ============= LOAD FILE DOCX ============
            try {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($file);
            } catch (\Throwable $t) {
                throw new Exception("Cannot read DOCX: " . $t->getMessage());
            }

            // ============= EXTRACT TEXT ============
            $text = "";
            $extract = function ($el) use (&$extract) {
                $out = "";
                if (is_string($el)) {
                    return $el;
                }

                if (is_object($el) && method_exists($el, 'getText')) {
                    try {
                        $out .= $el->getText();
                    } catch (\Throwable $t) {
                    }
                }

                if (is_object($el) && method_exists($el, 'getElements')) {
                    foreach ($el->getElements() as $child) {
                        $out .= $extract($child);
                    }
                }
                return $out;
            };

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $e) {
                    $part = trim($extract($e));
                    if ($part !== "") {
                        $text .= $part . "\n";
                    }
                }
            }

            // ============= SPLIT LINES ============
            $lines = array_values(array_filter(array_map("trim", preg_split("/\r\n|\n|\r/", $text))));
            $total = count($lines);

            $result = [];
            $i = 0;

            // ============= PARSER ============
            while ($i < $total) {

                $line = $lines[$i];

                // detect Reading block: [Reading][x] - Tiêu đề -
                if (preg_match('/^\[Reading\]\[(\d+)\]\s*-\s*(.+?)\s*-\s*$/iu', $line, $m)) {

                    $levelHeader = intval($m[1]);       // mức độ
                    $title = trim($m[2]);               // tiêu đề
                    $passage = "";                       // passage
                    $i++;

                    // ===== GOM PASSAGE =====
                    while ($i < $total) {
                        $next = $lines[$i];

                        if (preg_match('/^\[Reading\]/i', $next)) {
                            break;
                        }  // block mới
                        if (preg_match('/\?$/', $next)) {
                            break;
                        }           // câu hỏi bắt đầu
                        if (preg_match('/^\d+[\.\)]\s+/', $next)) {
                            break;
                        }
                        if (preg_match('/^[A-D][)\.]\s+/', $next)) {
                            break;
                        }
                        if (preg_match('/^ANSWER:/i', $next)) {
                            break;
                        }

                        $passage .= " " . trim($next);
                        $i++;
                    }

                    // ===== PARSE QUESTIONS =====
                    $subQuestions = [];
                    while ($i < $total && preg_match('/\?$/', $lines[$i])) {

                        $questionText = trim($lines[$i]);
                        $subLevel = $levelHeader;

                        if (preg_match('/Level:\s*(\d+)/i', $questionText, $lm)) {
                            $subLevel = intval($lm[1]);
                            $questionText = trim(preg_replace('/Level:\s*\d+/i', '', $questionText));
                        }

                        $i++;
                        $opts = [];
                        $correct = null;

                        while ($i < $total) {
                            $l = trim($lines[$i]);
                            if (preg_match('/^\[Reading\]/i', $l)) {
                                break;
                            }
                            if (preg_match('/\?$/', $l)) {
                                break;
                            }

                            if (preg_match('/^([A-D])[)\.]\s*(.+)$/u', $l, $mm)) {
                                $letter = strtoupper($mm[1]);
                                $opts[$letter] = trim($mm[2]);
                            }
                            if (preg_match('/^ANSWER:\s*([A-D])$/i', $l, $ans)) {
                                $correct = strtoupper($ans[1]);
                            }
                            $i++;
                        }

                        if (count($opts) == 0 || !$correct) {
                            throw new Exception("Missing options or ANSWER in a question");
                        }

                        uksort($opts, fn ($a, $b) => ord($a) - ord($b));
                        $optionsArr = array_values($opts);
                        $answerNumber = array_search($correct, array_keys($opts)) + 1;

                        $subQuestions[] = [
                            "type" => "reading",
                            "level" => $subLevel,
                            "question" => $questionText,
                            "option" => $optionsArr,
                            "answer" => $answerNumber
                        ];
                    }

                    $result[] = [
                        "type" => "reading",
                        "level" => $levelHeader,
                        "title" => $title,
                        "passage" => $passage,
                        "questions" => $subQuestions
                    ];

                    continue;
                }

                $i++;
            }

            // ============= RETURN JSON ============
            ob_clean();
            echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;

        } catch (Exception $e) {

            ob_clean();
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    public function xulytracnghiem()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !AuthCore::checkPermission("cauhoi", "create")) {
            echo json_encode(["status" => "error", "message" => "Unauthorized"]);
            exit;
        }

        try {
            require_once 'vendor/autoload.php';

            if (!isset($_FILES["fileToUpload"]["tmp_name"])) {
                throw new Exception("No file uploaded");
            }

            $file = $_FILES["fileToUpload"]["tmp_name"];
            if (!file_exists($file)) {
                throw new Exception("File not found");
            }

            // Load DOCX
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($file);

            // Hàm đệ quy lấy text từ element
            $extract = function ($el) use (&$extract) {
                $out = "";
                if (is_string($el)) {
                    return $el;
                }

                if (is_object($el) && method_exists($el, 'getText')) {
                    try {
                        $out .= $el->getText();
                    } catch (\Throwable $t) {
                    }
                }

                if (is_object($el) && method_exists($el, 'getElements')) {
                    foreach ($el->getElements() as $child) {
                        $out .= $extract($child);
                    }
                }
                return $out;
            };

            // Lấy tất cả text
            $text = "";
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $e) {
                    $part = trim($extract($e));
                    if ($part !== "") {
                        $text .= $part . "\n";
                    }
                }
            }

            // Tách từng dòng
            $lines = array_values(array_filter(array_map("trim", preg_split("/\r\n|\n|\r/", $text))));
            $total = count($lines);
            $result = [];
            $i = 0;

            while ($i < $total) {
                $line = $lines[$i];

                // detect MCQ block: [mcq][x] Câu hỏi...
                if (preg_match('/^\[mcq\]\[(\d+)\]\s*(.+)$/i', $line, $m)) {
                    $level = intval($m[1]);
                    $questionText = trim($m[2]);
                    $i++;

                    $options = [];
                    $answer = null;

                    // Lấy các option và ANSWER
                    while ($i < $total) {
                        $l = trim($lines[$i]);
                        if (preg_match('/^[A-D][)\.]\s*(.+)$/', $l, $opt)) {
                            $letter = strtoupper($l[0]);
                            $options[$letter] = trim($opt[1]);
                        } elseif (preg_match('/^ANSWER:\s*([A-D])/i', $l, $ans)) {
                            $answer = strtoupper($ans[1]);
                            break;
                        }
                        $i++;
                    }

                    if (!$answer || count($options) === 0) {
                        throw new Exception("Missing options or ANSWER in question: $questionText");
                    }

                    // Sắp xếp option theo A-D
                    uksort($options, fn ($a, $b) => ord($a) - ord($b));
                    $optionsArr = array_values($options);
                    $answerNumber = array_search($answer, array_keys($options)) + 1;

                    $result[] = [
                        "type" => "mcq",
                        "level" => $level,
                        "question" => $questionText,
                        "option" => $optionsArr,
                        "answer" => $answerNumber
                    ];
                } else {
                    $i++;
                }
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;

        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    public function xulytuluan()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !AuthCore::checkPermission("cauhoi", "create")) {
            echo json_encode(["status" => "error", "message" => "Unauthorized"]);
            exit;
        }

        try {
            require_once 'vendor/autoload.php';

            if (!isset($_FILES["fileToUpload"]["tmp_name"])) {
                throw new Exception("No file uploaded");
            }

            $file = $_FILES["fileToUpload"]["tmp_name"];
            if (!file_exists($file)) {
                throw new Exception("File not found");
            }

            // Load DOCX
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($file);

            // Hàm đệ quy lấy text từ element
            $extract = function ($el) use (&$extract) {
                $out = "";
                if (is_string($el)) {
                    return $el;
                }

                if (is_object($el) && method_exists($el, 'getText')) {
                    try {
                        $out .= $el->getText();
                    } catch (\Throwable $t) {
                    }
                }

                if (is_object($el) && method_exists($el, 'getElements')) {
                    foreach ($el->getElements() as $child) {
                        $out .= $extract($child);
                    }
                }
                return $out;
            };

            // Lấy tất cả text
            $text = "";
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $e) {
                    $part = trim($extract($e));
                    if ($part !== "") {
                        $text .= $part . "\n";
                    }
                }
            }

            // Tách từng dòng
            $lines = array_values(array_filter(array_map("trim", preg_split("/\r\n|\n|\r/", $text))));
            $total = count($lines);
            $result = [];
            $i = 0;

            while ($i < $total) {
                $line = $lines[$i];

                // detect Essay block: [essay][x] Câu hỏi...
                if (preg_match('/^\[essay\]\[(\d+)\]\s*(.+)$/i', $line, $m)) {
                    $level = intval($m[1]);
                    $questionText = trim($m[2]);

                    $result[] = [
                        "type" => "essay",
                        "level" => $level,
                        "question" => $questionText
                    ];

                    $i++;
                } else {
                    $i++;
                }
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit;

        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function updateQuestionJSON()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        $json = $_POST['questions'] ?? '';
        if (!$json) {
            echo json_encode(['status' => 'error', 'message' => 'No data provided']);
            return;
        }

        $questions = json_decode($json, true);
        if (!$questions || !is_array($questions)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
            return;
        }

        foreach ($questions as &$item) {

            // ======== MỨC ĐỘ ========
            if (!isset($item['level']) || !$item['level']) {
                $item['level'] = 1;
            }

            // ======== ĐỌC HIỂU ========
            // ======== ĐỌC HIỂU ========
            if ($item['type'] === 'reading') {
                // Thiếu title hoặc passage -> đảm bảo tồn tại
                if (!isset($item['title'])) {
                    $item['title'] = "";
                }
                if (!isset($item['passage'])) {
                    $item['passage'] = "";
                }

                // Đảm bảo level của block luôn tồn tại
                if (!isset($item['level']) || !$item['level']) {
                    $item['level'] = 1;
                }

                // Nếu không có mảng câu hỏi con
                if (!isset($item['questions']) || !is_array($item['questions'])) {
                    $item['questions'] = [];
                }

                // ===> QUAN TRỌNG: GHI ĐÈ LUÔN LEVEL CỦA TẤT CẢ CÂU HỎI CON ===
                foreach ($item['questions'] as &$sub) {
                    // Luôn gán level của câu hỏi con = level của block reading
                    $sub['level'] = $item['level'];

                    // Chuẩn hoá các trường khác
                    if (!isset($sub['option']) || !is_array($sub['option'])) {
                        $sub['option'] = [];
                    }
                    if (!isset($sub['answer']) || !$sub['answer']) {
                        $sub['answer'] = 1;
                    }
                    if (!isset($sub['question'])) {
                        $sub['question'] = "";
                    }
                }
                unset($sub); // phá tham chiếu
            }
            // ======== TRẮC NGHIỆM ========
            elseif ($item['type'] === 'mcq') {

                if (!isset($item['question'])) {
                    $item['question'] = "";
                }

                if (!isset($item['option']) || !is_array($item['option'])) {
                    $item['option'] = [];
                }

                if (!isset($item['answer']) || !$item['answer']) {
                    $item['answer'] = 1;
                }
            }

            // ======== TỰ LUẬN ========
            elseif ($item['type'] === 'essay') {

                if (!isset($item['question'])) {
                    $item['question'] = "";
                }

                // Tự luận không có option & answer
                $item['option'] = [];
                $item['answer'] = null;
            }

        }

        echo json_encode([
            'status' => 'success',
            'questions' => $questions
        ]);
    }

    public function addQuesFile()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!AuthCore::checkPermission("cauhoi", "create") || $_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $nguoitao = $_SESSION['user_id'] ?? null;
        $monhoc   = $_POST['monhoc'] ?? null;
        $chuong   = $_POST['chuong'] ?? null;
        $items    = json_decode($_POST['questions'] ?? '[]', true);

        if (!$nguoitao || !$monhoc || !$chuong || !is_array($items)) {
            echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        $errors = [];
        $inserted = 0;
        $ansValues = []; // Lưu tất cả đáp án tạm thời

        try {
            // ===================== Insert câu hỏi =====================
            foreach ($items as $idx => $item) {
                if (!isset($item["type"])) {
                    $errors[] = "Mục $idx: Dữ liệu sai";
                    continue;
                }

                // --- READING ---
                if ($item["type"] === "reading") {
                    $passage = trim($item["passage"] ?? "");
                    if ($passage === "") {
                        $errors[] = "Mục $idx: Đoạn văn trống";
                        continue;
                    }

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
                        $errors[] = "Mục $idx: Lỗi tạo đoạn văn";
                        continue;
                    }

                    $subQuestions = $item["questions"] ?? [];
                    foreach ($subQuestions as $subIdx => $sub) {
                        $qText = trim($sub["question"] ?? "");
                        $opts  = $sub["option"] ?? [];
                        $ans   = intval($sub["answer"] ?? 0);

                        if ($qText === "" || count($opts) < 2 || $ans < 1) {
                            $errors[] = "Mục $idx câu $subIdx: Dữ liệu không hợp lệ";
                            continue;
                        }

                        $noidung = mysqli_real_escape_string($this->cauHoiModel->con, $qText);
                        $dokho   = intval($sub["level"] ?? 1);

                        $sqlQ = "INSERT INTO cauhoi (noidung, dokho, mamonhoc, machuong, nguoitao, loai, madv)
                             VALUES ('$noidung', $dokho, '$monhoc', $chuong, '$nguoitao', 'reading', $madv)";
                        mysqli_query($this->cauHoiModel->con, $sqlQ);
                        $qId = mysqli_insert_id($this->cauHoiModel->con);

                        // Lưu đáp án tạm
                        foreach ($opts as $i => $opt) {
                            $noidungtl = mysqli_real_escape_string($this->cauTraLoiModel->con, trim($opt));
                            $ladapan = ($i + 1 == $ans) ? 1 : 0;
                            $ansValues[] = "($qId, '$noidungtl', $ladapan)";
                        }

                        $inserted++;
                    }
                    continue;
                }

                // --- MCQ ---
                if ($item["type"] === "mcq") {
                    $qText = trim($item["question"] ?? "");
                    $opts  = $item["option"] ?? [];
                    $ans   = intval($item["answer"] ?? 0);

                    if ($qText === "" || count($opts) < 2 || $ans < 1) {
                        $errors[] = "Mục $idx: Dữ liệu câu hỏi không hợp lệ";
                        continue;
                    }

                    $noidung = mysqli_real_escape_string($this->cauHoiModel->con, $qText);
                    $dokho   = intval($item["level"] ?? 1);

                    $sqlQ = "INSERT INTO cauhoi (noidung, dokho, mamonhoc, machuong, nguoitao, loai, madv)
                         VALUES ('$noidung', $dokho, '$monhoc', $chuong, '$nguoitao', 'mcq', NULL)";
                    mysqli_query($this->cauHoiModel->con, $sqlQ);
                    $qId = mysqli_insert_id($this->cauHoiModel->con);

                    foreach ($opts as $i => $opt) {
                        $noidungtl = mysqli_real_escape_string($this->cauTraLoiModel->con, trim($opt));
                        $ladapan = ($i + 1 == $ans) ? 1 : 0;
                        $ansValues[] = "($qId, '$noidungtl', $ladapan)";
                    }

                    $inserted++;
                }
                // --- ESSAY ---
                elseif ($item["type"] === "essay") {
                    $qText = trim($item["question"] ?? "");
                    if ($qText === "") {
                        $errors[] = "Mục $idx: Câu hỏi trống";
                        continue;
                    }

                    $noidung = mysqli_real_escape_string($this->cauHoiModel->con, $qText);
                    $dokho   = intval($item["level"] ?? 1);

                    $sqlQ = "INSERT INTO cauhoi (noidung, dokho, mamonhoc, machuong, nguoitao, loai, madv)
                         VALUES ('$noidung', $dokho, '$monhoc', $chuong, '$nguoitao', 'essay', NULL)";
                    if (!mysqli_query($this->cauHoiModel->con, $sqlQ)) {
                        $errors[] = "Mục $idx: Lỗi tạo câu hỏi tự luận";
                        continue;
                    }

                    $inserted++;
                } else {
                    $errors[] = "Mục $idx: Loại câu hỏi không hợp lệ";
                    continue;
                }

            }

            // ===================== Batch insert đáp án =====================
            $batchSize = 500; // 500 đáp án mỗi batch
            $totalAns = count($ansValues);
            for ($start = 0; $start < $totalAns; $start += $batchSize) {
                $batch = array_slice($ansValues, $start, $batchSize);
                $sqlA = "INSERT INTO cautraloi (macauhoi, noidungtl, ladapan) VALUES " . implode(',', $batch);
                if (!mysqli_query($this->cauTraLoiModel->con, $sqlA)) {
                    throw new Exception("Lỗi insert đáp án: " . mysqli_error($this->cauTraLoiModel->con));
                }
            }

            echo json_encode([
                "status" => "success",
                "inserted" => $inserted,
                "errors" => $errors
            ]);

        } catch (\Throwable $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage(),
                "errors" => $errors
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
                for ($j = 0; $j < $TotalCol; $j++) {
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
        header('Content-Type: application/json; charset=utf-8');

        if (!AuthCore::checkPermission("cauhoi", "create")) {
            echo json_encode(["status" => "error", "message" => "Không có quyền thêm câu hỏi"]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => "error", "message" => "Phải gửi POST"]);
            return;
        }

        $mamon = $_POST['mamon'] ?? null;
        $machuong = $_POST['machuong'] ?? null;
        $dokho = $_POST['dokho'] ?? null;
        $noidungCH = trim(strip_tags($_POST['noidung'] ?? ''));
        $loai = $_POST['loai'] ?? 'mcq';
        $nguoitao = $_SESSION['user_id'] ?? null;
        $madv = null;

        $cautraloi_json = $_POST['cautraloi'] ?? '[]';
        $noidungDV = trim(strip_tags($_POST['doanvan_noidung'] ?? ''));

        $hinhanh = null;
        if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
            $hinhanh = file_get_contents($_FILES['hinhanh']['tmp_name']);
        }

        if (!$mamon || !$machuong || !$dokho) {
            echo json_encode(["status" => "error", "message" => "Vui lòng nhập đầy đủ môn học, chương và độ khó"]);
            return;
        }

        $cautraloi = json_decode($cautraloi_json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["status" => "error", "message" => "Dữ liệu đáp án không hợp lệ"]);
            return;
        }

        $optionFiles = $_FILES['option_hinhanh'] ?? [];

        try {
            switch ($loai) {
                case 'mcq':
                case 'essay':
                    $macauhoi = $this->cauHoiModel->create($noidungCH, $dokho, $mamon, $machuong, $nguoitao, $loai, $madv, $hinhanh);

                    foreach ($cautraloi as $i => $x) {
                        $content = trim(strip_tags($x['content'] ?? ''));
                        if ($content === '') {
                            continue;
                        }
                        $check = ($loai === 'mcq' && (isset($x['check']) && ($x['check'] === true || $x['check'] == 1))) ? 1 : 0;

                        $hinhanhDL = null;
                        if (!empty($optionFiles['tmp_name'][$i]) && $optionFiles['error'][$i] === UPLOAD_ERR_OK) {
                            $hinhanhDL = file_get_contents($optionFiles['tmp_name'][$i]);
                        }

                        $this->cauTraLoiModel->create($macauhoi, $content, $check, $hinhanhDL);
                    }
                    break;

                case 'reading':
                    if ($noidungDV === '' || empty($cautraloi)) {
                        echo json_encode(["status" => "error", "message" => "Vui lòng thêm nội dung và câu hỏi con"]);
                        return;
                    }

                    $madv = $this->cauHoiModel->createWithDoanVan('', $dokho, $mamon, $machuong, $nguoitao, $loai, $noidungDV, '', $hinhanh);

                    foreach ($cautraloi as $i => $subQuestion) {
                        $subContent = trim(strip_tags($subQuestion['content'] ?? ''));
                        if ($subContent === '') {
                            continue;
                        }

                        $subMacauhoi = $this->cauHoiModel->create($subContent, $dokho, $mamon, $machuong, $nguoitao, 'mcq', $madv);

                        foreach ($subQuestion['options'] as $j => $option) {
                            $content = trim(strip_tags($option['content'] ?? ''));
                            if ($content === '') {
                                continue;
                            }
                            $check = (isset($option['check']) && ($option['check'] === true || $option['check'] == 1)) ? 1 : 0;

                            $hinhanhDL = null;
                            if (!empty($_FILES['suboption_hinhanh']['tmp_name'][$i][$j]) &&
                                $_FILES['suboption_hinhanh']['error'][$i][$j] === UPLOAD_ERR_OK) {
                                $hinhanhDL = file_get_contents($_FILES['suboption_hinhanh']['tmp_name'][$i][$j]);
                            }

                            $this->cauTraLoiModel->create($subMacauhoi, $content, $check, $hinhanhDL);
                        }
                    }
                    break;
            }

            echo json_encode(["status" => "success", "message" => "Thêm câu hỏi thành công", "loai" => $loai]);
        } catch (\Exception $e) {
            echo json_encode(["status" => "error", "message" => "Lỗi hệ thống: " . $e->getMessage()]);
        }
    }

    public function editQuesion()
    {
        if (!AuthCore::checkPermission("cauhoi", "update")) {
            echo json_encode(["status" => "error", "message" => "Không có quyền chỉnh sửa"]);
            return;
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["status" => "error", "message" => "Invalid request"]);
            return;
        }

        $id           = $_POST['id'] ?? null;
        $mamon        = $_POST['mamon'] ?? null;
        $machuong     = $_POST['machuong'] ?? null;
        $dokho        = $_POST['dokho'] ?? null;
        $nguoitao     = $_SESSION['user_id'];
        $loai         = $_POST['loai'] ?? 'mcq';
        $cautraloi_json = $_POST['cautraloi'] ?? '[]';
        $tieudeDV     = trim(strip_tags($_POST['doanvan_tieude'] ?? ''));

        // THÊM DÒNG NÀY: Xử lý xóa ảnh câu hỏi chính
        $delete_question_image = (!empty($_POST['delete_question_image']) && $_POST['delete_question_image'] == '1');

        $noidungRaw   = $_POST['noidung'] ?? '';

        // Xử lý ảnh mới (nếu có upload)
        $hinhanhFile  = $_FILES['hinhanh']['tmp_name'] ?? null;
        $hinhanh      = null;
        if ($hinhanhFile && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
            $hinhanh = file_get_contents($hinhanhFile);
        }

        $cautraloi = json_decode($cautraloi_json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["status" => "error", "message" => "Dữ liệu đáp án không hợp lệ"]);
            return;
        }

        if (!$id || !$mamon || !$machuong || !$dokho) {
            echo json_encode(["status" => "error", "message" => "Thiếu thông tin bắt buộc"]);
            return;
        }

        try {
            $question = $this->cauHoiModel->getById($id);
            if (!$question) {
                echo json_encode(["status" => "error", "message" => "Câu hỏi không tồn tại"]);
                return;
            }

            // XỬ LÝ ẢNH CÂU HỎI CHÍNH TRƯỚC KHI UPDATE
            if ($delete_question_image) {
                $hinhanh = null;
            }


            if ($loai === 'reading') {
                $madv = $question['madv'];

                $noidungDV = trim(strip_tags($noidungRaw));
                if ($noidungDV === '') {
                    echo json_encode(["status" => "error", "message" => "Vui lòng nhập nội dung đoạn văn"]);
                    return;
                }
                if (empty($cautraloi)) {
                    echo json_encode(["status" => "error", "message" => "Phải có ít nhất 1 câu hỏi con"]);
                    return;
                }

                $sql = "UPDATE doan_van SET noidung = ?, tieude = ?, mamonhoc = ?, machuong = ?, nguoitao = ? WHERE madv = ?";
                $stmt = $this->cauHoiModel->con->prepare($sql);
                $stmt->bind_param("sssssi", $noidungDV, $tieudeDV, $mamon, $machuong, $nguoitao, $madv);
                $stmt->execute();
                $stmt->close();

                $subQuestions = $this->cauHoiModel->getSubQuestions($madv);
                foreach ($subQuestions as $sub) {
                    $this->cauTraLoiModel->deletebyanswer($sub['macauhoi']);
                    $this->cauHoiModel->delete($sub['macauhoi']);
                }

                foreach ($cautraloi as $subQ) {
                    $subContent = trim(strip_tags($subQ['content'] ?? ''));
                    if ($subContent === '') {
                        continue;
                    }

                    $subHinhanh = null;
                    if (!empty($subQ['file'])) {
                        $subHinhanh = file_get_contents($subQ['file']);
                    }

                    $subId = $this->cauHoiModel->create(
                        $subContent,
                        $dokho,
                        $mamon,
                        $machuong,
                        $nguoitao,
                        'mcq',
                        $madv,
                        $subHinhanh
                    );

                    if (!$subId) {
                        throw new Exception("Lỗi tạo câu hỏi con");
                    }

                    foreach ($subQ['options'] as $opt) {
                        $optContent = trim(strip_tags($opt['content'] ?? ''));
                        if ($optContent === '') {
                            continue;
                        }

                        $optHinhanh = null;
                        if (!empty($opt['image']) && strpos($opt['image'], 'data:image') === 0) {
                            $optHinhanh = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $opt['image']));
                        } elseif (!empty($opt['file'])) {
                            $optHinhanh = file_get_contents($opt['file']);
                        }

                        $isCorrect = (!empty($opt['check']) && ($opt['check'] == 1 || $opt['check'] === 'true')) ? 1 : 0;
                        $this->cauTraLoiModel->create($subId, $optContent, $isCorrect, $optHinhanh);
                    }
                }

                // CẬP NHẬT CÂU HỎI CHÍNH
                $this->cauHoiModel->update($id, '', $dokho, $mamon, $machuong, $nguoitao, $loai, $madv, $hinhanh);

            } else {
                $noidung = trim(strip_tags($noidungRaw));
                if ($noidung === '') {
                    echo json_encode(["status" => "error", "message" => "Vui lòng nhập nội dung câu hỏi"]);
                    return;
                }

                if ($loai === 'mcq' && empty($cautraloi)) {
                    echo json_encode(["status" => "error", "message" => "Phải có ít nhất 1 đáp án"]);
                    return;
                }

                $validAnswers = [];
                foreach ($cautraloi as $ans) {
                    $content = trim(strip_tags($ans['content'] ?? ''));
                    if ($content === '') {
                        continue;
                    }

                    $isCorrect = (!empty($ans['check']) && ($ans['check'] == 1 || $Swal['check'] === 'true')) ? 1 : 0;

                    $optImage = null;
                    if (!empty($ans['image']) && strpos($ans['image'], 'data:image') === 0) {
                        $optImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $ans['image']));
                    } elseif (!empty($ans['file'])) {
                        $optImage = file_get_contents($ans['file']);
                    }

                    $validAnswers[] = [
                        'content' => $content,
                        'check'   => $isCorrect,
                        'image'   => $optImage
                    ];
                }

                if ($loai === 'mcq' && empty($validAnswers)) {
                    echo json_encode(["status" => "error", "message" => "Phải có ít nhất 1 đáp án hợp lệ"]);
                    return;
                }

                // CẬP NHẬT CÂU HỎI THƯỜNG → có xử lý ảnh
                $this->cauHoiModel->update($id, $noidung, $dokho, $mamon, $machuong, $nguoitao, $loai, null, $hinhanh, $delete_question_image);

                $this->cauTraLoiModel->deletebyanswer($id);
                foreach ($validAnswers as $ans) {
                    $this->cauTraLoiModel->create($id, $ans['content'], $ans['check'], $ans['image']);
                }
            }

            echo json_encode(["status" => "success", "message" => "Cập nhật câu hỏi thành công"]);

        } catch (Exception $e) {
            error_log("Edit question error: " . $e->getMessage());
            echo json_encode(["status" => "error", "message" => "Lỗi hệ thống: " . $e->getMessage()]);
        }
    }
    public function delete()
    {
        if (AuthCore::checkPermission("cauhoi", "delete")) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id = $_POST['macauhoi'];
                $this->cauHoiModel->delete($id);
            }
        }
    }

    public function getQuestionById()
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        if (!AuthCore::checkPermission("cauhoi", "view") || $_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["error" => "no_permission"]);
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(["error" => "missing_id"]);
            return;
        }

        $result = $this->cauHoiModel->getById($id);

        if (!$result) {
            echo json_encode(["error" => "not_found"]);
            return;
        }
        if (!empty($result['hinhanh'])) {
            $mime = 'image/jpeg';
            try {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $detected = $finfo->buffer($result['hinhanh']);
                if ($detected) {
                    $mime = $detected;
                }
            } catch (Exception $e) {
            }

            $base64 = 'data:' . $mime . ';base64,' . base64_encode($result['hinhanh']);

            $result['question_image_base64'] = $base64;
            $result['hinhanh_base64'] = $base64;
        } else {
            $result['question_image_base64'] = null;
            $result['hinhanh_base64'] = null;
        }

        unset($result['hinhanh']);
        if ($result['loai'] === 'reading') {
            $dv = $this->cauHoiModel->getWithDoanVan($id);
            $result['noidung'] = $dv['doanvan_noidung'] ?? '';
            $result['tieude'] = $dv['doanvan_tieude'] ?? '';
            $result['madv'] = $dv['madv'] ?? null;
        }

        echo json_encode($result);
    }

    public function getAnswerById()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            exit(json_encode([]));
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            exit(json_encode([]));
        }

        $question = $this->cauHoiModel->getById($id);
        if (!$question) {
            exit(json_encode([]));
        }

        // READING
        if ($question['loai'] === 'reading') {
            $madv = $question['madv'];
            $subQuestions = $this->cauHoiModel->getSubQuestions($madv);

            $result = [];

            foreach ($subQuestions as $sub) {
                $answers = $this->cauTraLoiModel->getAll($sub['macauhoi']);

                foreach ($answers as $ans) {
                    $item = [
                        'macauhoicon' => $sub['macauhoi'],      // ID câu hỏi con
                        'noidung_con' => $sub['noidung'],       // Nội dung câu hỏi con
                        'noidungtl'   => $ans['noidungtl'],     // Nội dung đáp án
                        'ladapan'     => $ans['ladapan']        // 1/0
                    ];

                    // Ảnh câu hỏi con
                    if (!empty($sub['hinhanh'])) {
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mime = $finfo->buffer($sub['hinhanh']) ?: 'image/jpeg';
                        $item['question_image_base64'] = "data:$mime;base64," . base64_encode($sub['hinhanh']);
                    } else {
                        $item['question_image_base64'] = null;
                    }

                    // Ảnh đáp án
                    if (!empty($ans['hinhanh'])) {
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mime = $finfo->buffer($ans['hinhanh']) ?: 'image/jpeg';
                        $item['option_image_base64'] = "data:$mime;base64," . base64_encode($ans['hinhanh']);
                    } else {
                        $item['option_image_base64'] = null;
                    }

                    $result[] = $item;
                }
            }

            echo json_encode($result);
            return;
        }

        // MCQ / ESSAY
        $answers = $this->cauTraLoiModel->getAll($id);
        $result = [];

        foreach ($answers as $ans) {
            $item = [
                'macautl'  => $ans['macautl'],
                'noidungtl' => $ans['noidungtl'],
                'ladapan'  => $ans['ladapan'],
                'macauhoi' => $ans['macauhoi']
            ];

            if (!empty($ans['hinhanh'])) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->buffer($ans['hinhanh']) ?: 'image/jpeg';
                $item['option_image_base64'] = "data:$mime;base64," . base64_encode($ans['hinhanh']);
            } else {
                $item['option_image_base64'] = null;
            }

            $result[] = $item;
        }

        echo json_encode($result);
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
        if ($_SERVER["REQUEST_METHOD"] != "POST" || !AuthCore::checkPermission("cauhoi", "view")) {
            echo json_encode(['success' => false, 'error' => 'Yêu cầu không hợp lệ hoặc không có quyền truy cập.']);
            return;
        }

        $chuong = isset($_POST['chuong']) ? (array)$_POST['chuong'] : [];
        $monhoc = $_POST['monhoc'] ?? '';
        $loaicauhoi = !empty($_POST['loaicauhoi']) ? (array)$_POST['loaicauhoi'] : ['mcq'];

        $result = [];

        foreach ($loaicauhoi as $type) {
            $result[$type] = [
                'de' => $this->cauHoiModel->getsoluongcauhoi($chuong, $monhoc, 1, $type),
                'tb' => $this->cauHoiModel->getsoluongcauhoi($chuong, $monhoc, 2, $type),
                'kho' => $this->cauHoiModel->getsoluongcauhoi($chuong, $monhoc, 3, $type),
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'data' => $result]);
    }
}
