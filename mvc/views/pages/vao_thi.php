<?php
// ==================== HELPER & LOGIC (Đặt ở đầu file) ====================
date_default_timezone_set('Asia/Ho_Chi_Minh');

function h($str)
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($date)
{
    return !empty($date) ? date_format(date_create($date), "H:i d/m/Y") : 'Chưa đặt';
}

// Tính tổng số câu hỏi (ưu tiên từ chi tiết kết quả nếu có)
function getTotalQuestions($data)
{
    $keys = ['mcq_de','mcq_tb','mcq_kho','essay_de','essay_tb','essay_kho','reading_de','reading_tb','reading_kho'];
    $total = 0;
    foreach ($keys as $k) {
        $total += (int)($data['Test'][$k] ?? 0);
    }
    if ($total === 0) {
        $total = (int)($data['Test']['socaude'] ?? 0) + 
                 (int)($data['Test']['socautb'] ?? 0) + 
                 (int)($data['Test']['socaukho'] ?? 0);
    }
    return $total > 0 ? $total : 1;
}

// Xác định loại đề thi
function getLoaiDe($data)
{
    $mcq = (int)($data['Test']['mcq_de'] ?? 0) + (int)($data['Test']['mcq_tb'] ?? 0) + (int)($data['Test']['mcq_kho'] ?? 0);
    $essay = (int)($data['Test']['essay_de'] ?? 0) + (int)($data['Test']['essay_tb'] ?? 0) + (int)($data['Test']['essay_kho'] ?? 0);
    $reading = (int)($data['Test']['reading_de'] ?? 0) + (int)($data['Test']['reading_tb'] ?? 0) + (int)($data['Test']['reading_kho'] ?? 0);

    if ($mcq && !$essay && !$reading) {
        return 'Trắc nghiệm';
    }
    if (!$mcq && $essay && !$reading) {
        return 'Tự luận';
    }
    if (!$mcq && !$essay && $reading) {
        return 'Đọc hiểu';
    }
    if ($mcq && $essay && !$reading) {
        return 'Trắc nghiệm, Tự luận';
    }
    if ($mcq && !$essay && $reading) {
        return 'Trắc nghiệm, Đọc hiểu';
    }
    if (!$mcq && $essay && $reading) {
        return 'Tự luận, Đọc hiểu';
    }
    if ($mcq && $essay && $reading) {
        return 'Trắc nghiệm, Tự luận, Đọc hiểu';
    }
    return 'Chưa chọn loại câu hỏi'; // tr
}

// ==================== TÍNH TOÁN MỘT LẦN ====================
$now           = time();
$startTime     = !empty($data['Test']['thoigianbatdau']) ? strtotime($data['Test']['thoigianbatdau']) : 0;
$endTime       = !empty($data['Test']['thoigianketthuc']) ? strtotime($data['Test']['thoigianketthuc']) : PHP_INT_MAX;

$hasAttempt    = !empty($data['Check']['makq'] ?? null);
$hasScore      = isset($data['Check']['diemthi']) && $data['Check']['diemthi'] !== '';
$canViewScore  = ($data['Test']['xemdiemthi'] ?? 0) == 1;
$showDetailBtn = ($data['Test']['hienthibailam'] ?? 0) == 1;

$totalQuestions = getTotalQuestions($data);
$loaide         = getLoaiDe($data);

$startFormatted = formatDate($data['Test']['thoigianbatdau'] ?? null);
$endFormatted   = formatDate($data['Test']['thoigianketthuc'] ?? null);
?>

<div class="content row justify-content-center align-items-center min-vh-100 py-5">
    <div class="col-lg-6 col-md-10 bg-white p-4 rounded shadow-sm">
        <h4 class="text-center mb-4 text-primary fw-bold"><?= h($data["Test"]["tende"]) ?></h4>

        <div class="exam-info mb-4 border-top pt-3">
            <!-- Thời gian làm bài -->
            <div class="row mb-3 align-items-center">
                <div class="col-6"><i class="far fa-clock text-primary me-2"></i><span class="fw-medium">Thời gian làm bài</span></div>
                <div class="col-6 text-end"><span class="badge bg-primary-subtle text-primary"><?= (int)($data["Test"]["thoigianthi"] ?? 0) ?> phút</span></div>
            </div>

            <!-- Thời gian mở đề -->
            <div class="row mb-3 align-items-center">
                <div class="col-6"><i class="far fa-calendar-days text-primary me-2"></i><span class="fw-medium">Thời gian mở đề</span></div>
                <div class="col-6 text-end"><span class="badge bg-primary-subtle text-primary"><?= $startFormatted ?></span></div>
            </div>

            <!-- Thời gian kết thúc -->
            <div class="row mb-3 align-items-center">
                <div class="col-6"><i class="far fa-calendar-xmark text-primary me-2"></i><span class="fw-medium">Thời gian kết thúc</span></div>
                <div class="col-6 text-end"><span class="badge bg-primary-subtle text-primary"><?= $endFormatted ?></span></div>
            </div>

            <!-- Số lượng câu hỏi -->
            <div class="row mb-3 align-items-center">
                <div class="col-6"><i class="far fa-circle-question text-primary me-2"></i><span class="fw-medium">Số lượng câu hỏi</span></div>
                <div class="col-6 text-end"><span class="badge bg-primary-subtle text-primary"><?= $totalQuestions ?></span></div>
            </div>

            <!-- Loại đề thi -->
            <div class="row mb-3 align-items-center">
                <div class="col-6"><i class="far fa-list-check text-primary me-2"></i><span class="fw-medium">Loại đề thi</span></div>
                <div class="col-6 text-end"><span class="badge bg-primary-subtle text-primary"><?= h($loaide) ?></span></div>
            </div>

            <!-- Môn học -->
            <div class="row mb-3 align-items-center">
                <div class="col-6"><i class="far fa-file-lines text-primary me-2"></i><span class="fw-medium">Môn học</span></div>
                <div class="col-6 text-end"><span class="badge bg-primary-subtle text-primary"><?= h($data["Test"]["tenmonhoc"] ?? '') ?></span></div>
            </div>
        </div>

        <!-- NÚT HÀNH ĐỘNG CHÍNH -->
        <?php if ($hasAttempt && $hasScore): ?>
            <?php if ($canViewScore): ?>
                <?php if ($showDetailBtn): ?>
                    <button class="btn btn-success w-100 rounded-pill py-3 shadow-sm" data-bs-toggle="collapse" data-bs-target="#xemkq">
                        Xem kết quả
                    </button>
                <?php else: ?>
                    <button type="button" data-id="<?= $data['Check']['makq'] ?>" id="show-exam-detail" class="btn btn-primary w-100 rounded-pill py-3 shadow-sm">
                        Xem kết quả của tôi
                    </button>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn btn-danger w-100 rounded-pill py-3" disabled>Đã hoàn thành</button>
            <?php endif; ?>

        <?php elseif ($hasAttempt && !$hasScore): ?>
            <button class="btn btn-danger w-100 rounded-pill py-3" disabled>Đã hoàn thành (Chưa chấm)</button>

        <?php else: ?>
            <?php if ($now < $startTime): ?>
                <button class="btn btn-light w-100 rounded-pill py-3" disabled>Chưa tới thời gian mở đề</button>
            <?php elseif ($now > $endTime): ?>
                <button class="btn btn-danger w-100 rounded-pill py-3" disabled>Bài thi đã quá hạn</button>
            <?php else: ?>
                <button name="start-test" id="start-test" data-id="<?= $data['Test']['made'] ?>"
                        class="btn btn-info w-100 rounded-pill py-3 text-white shadow-sm btn-st">
                   Bắt đầu thi  <i class='fa fa-angle-right ms-2'></i>
                </button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- ==================== KẾT QUẢ (nếu được phép xem) ==================== -->
<?php if ($canViewScore && $hasScore): ?>
<div class="collapse" id="xemkq">
    <div class="content row justify-content-center align-items-center mb-5">
        <div class="col-lg-6 col-md-10 bg-white p-4 rounded shadow-sm">
            <h3 class="text-center text-uppercase fw-bold mb-3">KẾT QUẢ BÀI THI</h3>
            <h4 class="text-center mb-4 text-success">
                Điểm của bạn: 
                <span class="display-6 fw-bold"><?= h($data["Check"]["diemthi"] ?? '0') ?></span>
            </h4>

            <div class="exam-info mb-4 border-top pt-3">
                <div class="row mb-3 align-items-center">
                    <div class="col-6"><i class="far fa-clock text-primary me-2"></i>Thời gian làm bài</div>
                    <div class="col-6 text-end"><span class="badge bg-primary-subtle text-primary">
                        <?= isset($data["Check"]["thoigianlambai"]) ? max(1, round($data["Check"]["thoigianlambai"] / 60)) : 0 ?> phút
                    </span></div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-6"><i class="far fa-calendar-days text-primary me-2"></i>Thời gian vào thi</div>
                    <div class="col-6 text-end"><span class="badge bg-primary-subtle text-primary">
                        <?= !empty($data["Check"]["thoigianvaothi"]) ? formatDate($data["Check"]["thoigianvaothi"]) : 'Chưa ghi nhận' ?>
                    </span></div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-6"><i class="far fa-circle-check text-primary me-2"></i>Số câu đúng</div>
                    <div class="col-6 text-end"><span class="badge bg-success-subtle text-success">
                        <?= $data["Check"]["socaudung"] ?? 0 ?>
                    </span></div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-6"><i class="far fa-circle-question text-primary me-2"></i>Tổng số câu</div>
                    <div class="col-6 text-end"><span class="badge bg-primary-subtle text-primary"><?= $totalQuestions ?></span></div>
                </div>
            </div>

            <?php if ($showDetailBtn && !empty($data["Check"]["makq"])): ?>
                <button data-id="<?= $data["Check"]["makq"] ?>" id="show-exam-detail"
                        class="btn btn-primary w-100 rounded-pill py-3 shadow-sm">
                    Xem chi tiết bài làm
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ==================== MODAL XEM CHI TIẾT ==================== -->
<div class="modal fade" id="modal-show-test" tabindex="-1" aria-labelledby="modal-view-test-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-view-test-label">Chi tiết bài thi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="content-file"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

