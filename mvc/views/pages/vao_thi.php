<style>
    #xemkq .content {
        margin-top: -110px;
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 1em;
    }
    .btn-st, .btn-primary, .btn-danger, .btn-light {
        font-weight: 500;
    }
/* layout */
.question { border-radius:8px; overflow:hidden; }
.question-top { padding:18px; }
.question-image { max-width: 420px; max-height: 260px; object-fit: contain; display:block; margin: 0 auto 16px; }
.answers-grid { margin-top:8px; }
.answer-card { display:flex; align-items:flex-start; gap:12px; padding:8px 0; }

/* answer image */
.img-answer { width:120px; height:100px; object-fit:cover; border-radius:8px; }

/* bottom answer bar */
.test-ans { background:#0b63c9; color:#fff; padding:12px 16px; display:flex; align-items:center; gap:12px; border-radius:0 0 8px 8px; }
.test-ans .label-title { font-weight:600; margin-right:10px; }

/* circle buttons for choices */
.choice-circle {
  width:48px; height:48px; border-radius:50%; background:#fff; color:#000; display:inline-flex; align-items:center; justify-content:center; font-weight:700;
  box-shadow:0 2px 0 rgba(0,0,0,0.08);
  margin-right:12px;
}
/* highlight selected circle */
.choice-circle.active { background:#ffd; border:4px solid #0b63c9; }

/* small responsive tweaks */
@media (max-width: 767px) {
  .img-answer { width:100px; height:80px; }
  .question-image { max-width: 100%; max-height: 180px; }
}


</style>
</head>
<body>
<div class="content row justify-content-center align-items-center min-vh-100 py-5">
    <div class="col-lg-6 col-md-12 bg-white p-4 rounded shadow-sm">
        <h4 class="text-center mb-4 text-primary fw-bold"><?php echo $data["Test"]["tende"]; ?></h4>
        <div class="exam-info mb-4 border-top pt-3">
            <div class="row mb-3 align-items-center">
                <div class="col-6">
                    <span class="text-primary"><i class="far fa-clock me-2"></i></span><span class="fw-medium">Thời gian làm bài</span>
                </div>
                <div class="col-6 text-end">
                    <span class="badge bg-primary-subtle text-primary fw-medium"><?php echo $data["Test"]["thoigianthi"]; ?> phút</span>
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col-6">
                    <span class="text-primary"><i class="far fa-calendar-days me-2"></i></span><span class="fw-medium">Thời gian mở đề</span>
                </div>
                <div class="col-6 text-end">
                    <span class="badge bg-primary-subtle text-primary fw-medium"><?php echo date_format(date_create($data["Test"]["thoigianbatdau"]), "H:i d/m/Y"); ?></span>
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col-6">
                    <span class="text-primary"><i class="far fa-calendar-xmark me-2"></i></span><span class="fw-medium">Thời gian kết thúc</span>
                </div>
                <div class="col-6 text-end">
                    <span class="badge bg-primary-subtle text-primary fw-medium"><?php echo date_format(date_create($data["Test"]["thoigianketthuc"]), "H:i d/m/Y"); ?></span>
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col-6">
                    <span class="text-primary"><i class="far fa-circle-question me-2"></i></span><span class="fw-medium">Số lượng câu hỏi</span>
                </div>
                <div class="col-6 text-end">
                    <span class="badge bg-primary-subtle text-primary fw-medium"><?php echo $data["Test"]["socaude"] + $data["Test"]["socautb"] + $data["Test"]["socaukho"]; ?></span>
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col-6">
                    <span class="text-primary"><i class="far fa-file-lines me-2"></i></span><span class="fw-medium">Môn học</span>
                </div>
                <div class="col-6 text-end">
                    <span class="badge bg-primary-subtle text-primary fw-medium"><?php echo $data["Test"]["tenmonhoc"]; ?></span>
                </div>
            </div>
        </div>
        <?php
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $now = time();
        $start = strtotime($data["Test"]["thoigianbatdau"]);
        $end = strtotime($data["Test"]["thoigianketthuc"]);

        if (isset($data["Check"]['diemthi']) && $data["Check"]['diemthi'] !== '') {
            if ($data["Test"]['hienthibailam'] == 1) {
                echo "<button class='btn btn-success w-100 rounded-pill py-2' data-bs-toggle='collapse' data-bs-target='#xemkq' role='button'>Xem kết quả</button>";
            } else {
                echo "<button class='btn btn-danger w-100 rounded-pill py-2' role='button'>Đã hoàn thành</button>";
            }
        } elseif (isset($data["Check"]['makq']) && $data["Check"]['diemthi'] === '') {
            if ($now >= $start && $now <= $end) {
                echo "<a class='btn btn-info w-100 rounded-pill py-2 text-white btn-st' href='./test/taketest/".$data['Test']['made']."'>Tiếp tục thi <i class='fa fa-angle-right ms-2'></i></a>";
            } else {
                echo "<button class='btn btn-danger w-100 rounded-pill py-2' role='button'>Bài thi quá hạn</button>";
            }
        } else {
            if ($now < $start) {
                echo "<button class='btn btn-light w-100 rounded-pill py-2' role='button'>Chưa tới thời gian mở đề</button>";
            } elseif ($now > $end) {
                echo "<button class='btn btn-danger w-100 rounded-pill py-2' role='button'>Bài thi quá hạn</button>";
            } else {
                echo "<button name='start-test' id='start-test' data-id='".$data['Test']['made']."' class='btn btn-info w-100 rounded-pill py-2 text-white btn-st' role='button'>Bắt đầu thi <i class='fa fa-angle-right ms-2'></i></button>";
            }
        }
        ?>
    </div>
</div>

<?php if ($data["Test"]['xemdiemthi'] == 1) { ?>
<div class="collapse" id="xemkq">
    <div class="content row justify-content-center align-items-center mb-2">
        <div class="col-lg-6 col-md-12 bg-white p-4 rounded shadow-sm">
            <h3 class="text-center text-uppercase text-dark fw-bold mb-3">KẾT QUẢ BÀI THI</h3>
            <h4 class="text-center mb-4 text-success">
                Điểm của bạn: 
                <span class="display-6 fw-bold">
                    <?php echo isset($data["Check"]['diemthi']) && $data["Check"]['diemthi'] != '' ? $data["Check"]['diemthi'] : '0'; ?>
                </span>
            </h4>
            <div class="exam-info mb-4 border-top pt-3">
                <div class="row mb-3 align-items-center">
                    <div class="col-6">
                        <span class="text-primary"><i class="far fa-clock me-2"></i></span><span class="fw-medium">Thời gian làm bài</span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="badge bg-primary-subtle text-primary fw-medium">
                            <?php echo isset($data["Check"]['thoigianlambai']) && $data["Check"]['thoigianlambai'] > 0 ? max(1, round($data["Check"]['thoigianlambai'] / 60, 0)) : '0'; ?> phút
                        </span>
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-6">
                        <span class="text-primary"><i class="far fa-calendar-days me-2"></i></span><span class="fw-medium">Thời gian vào thi</span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="badge bg-primary-subtle text-primary fw-medium">
                            <?php echo isset($data["Check"]["thoigianvaothi"]) ? date_format(date_create($data["Check"]["thoigianvaothi"]), "H:i d/m/Y") : 'Chưa vào thi'; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-6">
                        <span class="text-primary"><i class="far fa-circle-check me-2"></i></span><span class="fw-medium">Số câu đúng</span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="badge bg-success-subtle text-success fw-medium">
                            <?php echo isset($data["Check"]['socaudung']) ? $data["Check"]['socaudung'] : '0'; ?>
                        </span>
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-6">
                        <span class="text-primary"><i class="far fa-circle-question me-2"></i></span><span class="fw-medium">Tổng số câu hỏi</span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="badge bg-primary-subtle text-primary fw-medium">
                            <?php echo $data["Test"]["socaude"] + $data["Test"]["socautb"] + $data["Test"]["socaukho"]; ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php if ($data["Test"]['hienthibailam'] == 1 && isset($data["Check"]['makq'])) { ?>
                <button data-id="<?php echo $data["Check"]['makq']; ?>" type="button" class="btn btn-primary w-100 rounded-pill py-2" id="show-exam-detail">Xem chi tiết bài thi</button>
            <?php } ?>
        </div>
    </div>
</div>
<?php } ?>

<div class="modal fade" id="modal-show-test" tabindex="-1" aria-labelledby="modal-view-test-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-view-test-label">Chi tiết kết quả</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-1">
                <div id="content-file" class="p-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-dismiss="modal">Hoàn tất</button>
            </div>
        </div>
    </div>
</div>