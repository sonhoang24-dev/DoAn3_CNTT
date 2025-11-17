<div class="content" data-id="<?php echo $_SESSION["user_id"] ?>">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Tất cả câu hỏi</h3>
            <div class="block-options">
                <button type="button" class="btn btn-hero btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modal-add-question" id="addquestionnew" data-role="cauhoi" data-action="create">
                    <i class="fa fa-plus me-1"></i> Thêm câu hỏi mới
                </button>
            </div>
        </div>
        <div class="block-content">
           <form id="search-form" onsubmit="return false;">
                <!-- 4 ô lọc -->
                <div class="row g-3 align-items-center mb-4">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                        <select class="js-select2 form-select" id="main-page-monhoc" name="main-page-monhoc"
                            data-placeholder="Chọn môn học">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                        <select class="js-select2 form-select" id="main-page-chuong" name="main-page-chuong"
                            data-placeholder="Chọn chương">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                        <select class="js-select2 form-select" id="main-page-dokho" name="main-page-dokho"
                            data-placeholder="Tất cả độ khó">
                            <option value="0">Tất cả độ khó</option>
                            <option value="1">Cơ bản</option>
                            <option value="2">Trung bình</option>
                            <option value="3">Nâng cao</option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                        <select class="js-select2 form-select" id="main-page-loai" name="main-page-loai"
                            data-placeholder="Tất cả loại câu hỏi">
                            <option value="0">Tất cả loại câu hỏi</option>
                            <option value="mcq">Trắc nghiệm</option>
                            <option value="essay">Tự luận</option>
                            <option value="reading">Đọc hiểu</option>
                        </select>
                    </div>
                </div>

                <!-- Thanh tìm kiếm + khoảng cách đẹp -->
                <div class="row mb-5">   <!-- Đây là dòng quan trọng nhất -->
                    <div class="col-12">
                        <div class="input-group input-group-lg shadow-sm">
                            <input type="text" class="form-control form-control-alt rounded-start" 
                                   id="search-input" name="search-input"
                                   placeholder="Tìm kiếm nội dung câu hỏi...">
                            <button type="button" class="btn btn-primary rounded-end border-0 btn-search">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-vcenter table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 100px;">ID</th>
                            <th style="width: 700px;">Nội dung câu hỏi</th>
                            <th class="d-none d-sm-table-cell">Môn học</th>
                            <th class="d-none d-xl-table-cell">Độ khó</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="listQuestion"></tbody>
                </table>
            </div>

            <?php if (isset($data["Plugin"]["pagination"])) {
                require "./mvc/views/inc/pagination.php";
            } ?>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-add-question" tabindex="-1" role="dialog" aria-labelledby="modal-add-question"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <ul class="nav nav-tabs nav-tabs-alt mb-1" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="btabs-alt-static-home-tab" data-bs-toggle="tab"
                        data-bs-target="#btabs-alt-static-home" role="tab" aria-controls="btabs-alt-static-home"
                        aria-selected="true">
                        Thêm thủ công
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabs-alt-static-profile-tab" data-bs-toggle="tab"
                        data-bs-target="#btabs-alt-static-profile" role="tab" aria-controls="btabs-alt-static-profile"
                        aria-selected="false">
                        Thêm từ file
                    </button>
                </li>
                <li class="nav-item ms-auto">
                    <button type="button" class="btn btn-close p-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </li>
            </ul>
            <div class="modal-body block block-transparent bg-white mb-0 block-rounded">
                <div class="block-content tab-content">
                    <div class="tab-pane active" id="btabs-alt-static-home" role="tabpanel"
                        aria-labelledby="btabs-static-home-tab" tabindex="0">
                        <form id="form_add_question" onsubmit="return false;">
                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="form-label" for="mon-hoc">Môn học <span class="text-danger">*</span></label>
                                        <select class="js-select2 form-select data-monhoc" id="mon-hoc" name="mon-hoc"
                                            data-tab="1" data-placeholder="Chọn môn học" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="form-label" for="chuong">Chương <span class="text-danger">*</span></label>
                                        <select class="js-select2 form-select data-chuong" id="chuong" name="chuong"
                                            data-tab="1" data-placeholder="Chọn chương" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="form-label" for="dokho">Độ khó <span class="text-danger">*</span></label>
                                        <select class="js-select2 form-select" id="dokho" name="dokho"
                                            data-placeholder="Chọn mức độ" required>
                                            <option value=""></option>
                                            <option value="1">Cơ bản</option>
                                            <option value="2">Trung bình</option>
                                            <option value="3">Nâng cao</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label class="form-label" for="loai-cau-hoi">Loại câu hỏi <span class="text-danger">*</span></label>
                                        <select class="js-select2 form-select" id="loai-cau-hoi" name="loai-cau-hoi"
                                            data-placeholder="Chọn loại câu hỏi" required>
                                            <option value="mcq">Trắc nghiệm</option>
                                            <option value="essay">Tự luận</option>
                                            <option value="reading">Đọc hiểu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Passage Input for Reading Type -->
                            <div class="mb-4" id="passage-area" style="display: none;">
                                <label class="form-label" for="passage-content">Đoạn ngữ liệu <span class="text-danger">*</span></label>
                                <textarea id="passage-content" name="passage-content" class="form-control"
                                    placeholder="Nhập đoạn ngữ liệu cho câu hỏi đọc hiểu"></textarea>
                                <small class="text-muted">Nhập đoạn văn hoặc nội dung liên quan cho các câu hỏi đọc hiểu.</small>
                            </div>

                            <!-- Question Content for MCQ and Essay -->
                            <div class="mb-4" id="question-content-area">
                                <label class="form-label" for="js-ckeditor">Nội dung câu hỏi <span class="text-danger">*</span></label>
                                <textarea id="js-ckeditor" name="js-ckeditor" class="form-control"></textarea>
                            </div>

                            <!-- MCQ Options -->
                            <div class="mb-4" id="mcq-options-area">
                                <h6 class="mb-3">Danh sách đáp án</h6>
                                <div class="table-responsive">
                                    <table class="table table-vcenter table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 50px;">STT</th>
                                                <th>Nội dung đáp án</th>
                                                <th class="text-center" style="width: 150px;">Đáp án đúng</th>
                                                <th class="text-center" style="width: 150px;">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody id="list-options"></tbody>
                                    </table>
                                </div>
                                <div class="mb-3">
    
                                <button class="btn btn-hero btn-primary mt-3" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#add_option" aria-expanded="false" aria-controls="add_option">
                                    <i class="fa fa-plus me-1"></i> Thêm đáp án
                                </button>
                                </div>
                                <div class="collapse mt-3" id="add_option">
                                    <div class="card card-body border">
                                        <label class="form-label" for="option-content">Nội dung đáp án</label>
                                        <textarea id="option-content" name="option-content" class="form-control"></textarea>
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" value="" id="true-option">
                                            <label class="form-check-label" for="true-option">Đáp án đúng</label>
                                        </div>
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-primary me-2" id="save-option">Lưu đáp án</button>
                                            <button type="button" class="btn btn-primary" id="update-option" style="display: none;">Cập nhật đáp án</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reading Questions -->
                            <div class="mb-4" id="reading-questions-area" style="display: none;">
                                <h6 class="mb-3">Danh sách câu hỏi đọc hiểu</h6>
                                <div class="table-responsive">
    <table class="table table-vcenter table-hover">
        <thead>
            <tr>
                <th style="width: 60px;">STT</th>
                <th style="width: 400px;">Câu hỏi</th>
                <th>Phương án</th>
                <th class="text-center" style="width: 100px;">Đáp án</th>
                <th class="text-center" style="width: 150px;">Hành động</th>
            </tr>
        </thead>
        <tbody id="reading-questions-list"></tbody>
    </table>
</div>

                                
                                <button class="btn btn-hero btn-primary mt-3" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#add_reading_question" aria-expanded="false" aria-controls="add_reading_question">
                                    <i class="fa fa-plus me-1"></i> Thêm câu hỏi đọc hiểu
                                </button>
                                <div class="collapse mt-3" id="add_reading_question">
                                    <div class="card card-body border">
                                        <label class="form-label" for="reading-question-content">Nội dung câu hỏi</label>
                                        <textarea id="reading-question-content" name="reading-question-content" class="form-control" rows="4"></textarea>
                                        <h6 class="mt-3">Danh sách đáp án</h6>
                                        <div id="reading-question-options" class="mb-3"></div>
                                
                                        
                                        <div class="mb-3">
                                        <button type="button" class="btn btn-primary mb-3" id="add-reading-option">Thêm đáp án</button>
                                        </div>
                                   
                                        
                                        <div class="mt-3">
                                           <button type="button" class="btn btn-alt-primary" id="save-reading-question" style="margin-left: 0;">
                                                <i class="fa fa-check me-1"></i> Lưu câu hỏi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Essay Answer -->
                            <div class="mb-4" id="essay-area" style="display: none;">
                                <label class="form-label" for="essay-answer">Gợi ý đáp án (tự luận)</label>
                                <textarea class="form-control" id="essay-answer" name="essay-answer" rows="4"
                                    placeholder="Nhập gợi ý hoặc đáp án mẫu cho câu hỏi tự luận (tùy chọn)"></textarea>
                                <small class="text-muted">Phần này chỉ dành cho câu hỏi tự luận. Nếu để trống, hệ thống sẽ lưu câu hỏi không có đáp án mẫu.</small>
                            </div>

                            <div class="mb-4">
                                <button type="submit" class="btn btn-alt-success" id="add_question">
                                    <i class="fa fa-save me-1"></i> Lưu
                                </button>
                                <button type="button" class="btn btn-alt-primary" id="edit_question" style="display: none;">
                                    <i class="fa fa-pencil-alt me-1"></i> Sửa câu hỏi
                                </button>
                                <input type="hidden" value="" id="question_id">
                            </div>
                        </form>
                    </div>
                    <!-- file -->
                    <div class="tab-pane" id="btabs-alt-static-profile" role="tabpanel"
                        aria-labelledby="btabs-static-profile-tab" tabindex="0">
                       <form id="form-upload" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    <div class="block block-rounded">
        <div class="block-content">

            <!-- Môn học + Chương + Loại câu hỏi cùng 1 hàng -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label" for="monhocfile">Môn học <span class="text-danger">*</span></label>
                    <select id="monhocfile" class="js-select2 form-select data-monhoc" 
                            data-placeholder="Chọn môn học" required>
                        <option value=""></option>
                    </select>
                    <div class="invalid-feedback">Vui lòng chọn môn học</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="chuongfile">Chương <span class="text-danger">*</span></label>
                    <select id="chuongfile" class="js-select2 form-select data-chuong" 
                            data-placeholder="Chọn chương" required>
                        <option value=""></option>
                    </select>
                    <div class="invalid-feedback">Vui lòng chọn chương</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="loai-cau-hoi">Loại câu hỏi <span class="text-danger">*</span></label>
                    <select id="loai-cau-hoi" class="js-select2 form-select" required>
                        <option value="">Chọn loại câu hỏi</option>
                        <option value="mcq">Trắc nghiệm</option>
                        <option value="essay">Tự luận</option>
                        <option value="reading">Đọc hiểu</option>
                    </select>
                    <div class="invalid-feedback">Vui lòng chọn loại câu hỏi</div>
                </div>
            </div>

            <!-- File upload -->
            <div class="mb-4">
                <label class="form-label" for="file-cau-hoi">Tệp câu hỏi (.docx) <span class="text-danger">*</span></label>
                <input class="form-control" type="file" id="file-cau-hoi" 
                       accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document" 
                       required>
                <div class="invalid-feedback">Vui lòng chọn file Word (.docx)</div>
            </div>

            <!-- File mẫu -->
            <div class="mb-4">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fa fa-info-circle fa-2x me-3"></i>
                    <div>
                        Vui lòng soạn theo đúng định dạng trong file mẫu.<br>
                        <a href="./public/filemau/mau_import_cau_hoi.docx" target="_blank" class="alert-link">
                            <i class="fa fa-download me-1"></i> Tải file mẫu
                        </a>
                    </div>
                </div>
            </div>

            <!-- Nút submit -->
            <div class="text-end">
                <button type="submit" class="btn btn-hero btn-primary" id="nhap-file">
                    <i class="fa fa-cloud-arrow-up me-1"></i>
                    Thêm vào hệ thống
                </button>
            </div>

        </div>
    </div>
</form>

                        <div id="content-file" class="mt-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.reading-content {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 300px;
}
.btn-search i {
    color: white;
}
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}
.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.js-select2 {
    width: 100% !important;
}

.select2-container {
    width: 100% !important;
}
.correct-radio {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  width: 20px;
  height: 20px;
  border: 2px solid #6c757d;
  border-radius: 50%;
  outline: none;
  cursor: default;
  position: relative;
  margin: 0;
}

.correct-radio.correct-answer {
  border-color: blue;
}

.correct-radio.correct-answer::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 10px;
  height: 10px;
  background-color: blue;
  border-radius: 50%;
}

.correct-radio:checked:not(.correct-answer) {
  border-color: #6c757d;
}

.correct-radio:checked:not(.correct-answer)::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 10px;
  height: 10px;
  background-color: #6c757d;
  border-radius: 50%;
}



</style>