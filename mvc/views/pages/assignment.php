<!-- mvc/views/pages/assignment.php -->
<div class="content py-5 bg-light">
    <div class="block block-rounded shadow-lg border-start border-5 border-teal">
        <div class="block-header bg-gradient-teal text-white p-4 d-flex justify-content-between align-items-center">
            <h3 class="block-title mb-0"><i class="fa fa-chalkboard-teacher me-2"></i> Tất cả phân công</h3>
            <button data-role="phancong" data-action="create" type="button" 
                    class="btn btn-hero-sm btn-light shadow-sm" 
                    data-bs-toggle="modal" data-bs-target="#modal-add-assignment" 
                    id="add_assignment">
                <i class="fa fa-plus-circle me-1 text-teal"></i> Thêm phân công mới
            </button>
        </div>

        <div class="block-content p-4">
            <!-- Search Form -->
            <form id="main-page-search-form" onsubmit="return false;" class="mb-4">
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white border-2 border-teal">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-2 border-teal" 
                           id="search-input" name="search-input" 
                           placeholder="Tìm kiếm giảng viên, môn học, năm học...">
                    <button type="button" class="btn btn-outline-teal btn-search" 
                            data-bs-toggle="tooltip" title="Tìm kiếm">
                        <i class="fa fa-search"></i> Tìm
                    </button>
                </div>
            </form>

            <!-- Assignment Table -->
            <div class="table-responsive rounded shadow-sm">
                <table class="table table-hover table-vcenter table-striped align-middle mb-0">
                    <thead class="bg-teal-light text-dark">
                        <tr>
                            <th class="text-center fw-semibold" style="width: 80px;">ID</th>
                            <th class="fw-semibold">Tên giảng viên</th>
                            <th class="text-center fw-semibold" style="width: 120px;">Mã môn</th>
                            <th class="fw-semibold">Môn học</th>
                            <th class="text-center fw-semibold" style="width: 130px;">Năm học</th>
                            <th class="text-center fw-semibold" style="width: 100px;">Học kỳ</th>
                            <th class="text-center fw-semibold" style="width: 120px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="listAssignment" class="fs-sm">
                        <!-- Dữ liệu được load bằng JS -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container main-page-pagination"></div>
        </div>
    </div>
</div>

<!-- Modal: Thêm phân công mới -->
<div class="modal fade" id="modal-add-assignment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <ul class="nav nav-tabs nav-tabs-alt bg-light border-0" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#btabs-alt-static-home">
                        <i class="fa fa-user-plus me-2"></i> Thêm thủ công
                    </button>
                </li>
                <li class="nav-item ms-auto">
                    <button type="button" class="btn-close p-3" data-bs-dismiss="modal"></button>
                </li>
            </ul>

            <div class="modal-body p-0">
                <div class="block block-rounded block-transparent mb-0 bg-white">
                    <div class="block-content tab-content p-4">
                        <div class="tab-pane fade show active" id="btabs-alt-static-home">
                            <form class="form-phancong">
                                <!-- Giảng viên -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <label for="giang-vien" class="form-label fw-semibold text-teal">
                                            <i class="fa fa-chalkboard-teacher me-2"></i> Giảng viên <span class="text-danger">*</span>
                                        </label>
                                        <select class="js-select2 form-select form-select-lg shadow-sm border-teal" 
                                                id="giang-vien" name="giang-vien" style="width: 100%;" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Năm học + Học kỳ -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-teal">
                                            <i class="fa fa-calendar me-2"></i> Năm học
                                        </label>
                                        <select class="js-select2 form-select shadow-sm border-teal" id="namhoc" name="namhoc">
                                            <option value="">-- Chọn năm học --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-teal">
                                            <i class="fa fa-calendar-week me-2"></i> Học kỳ
                                        </label>
                                        <select class="js-select2 form-select shadow-sm border-teal" id="hocky" name="hocky">
                                            <option value="">-- Chọn học kỳ --</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Tìm kiếm môn học -->
                                <form id="modal-add-assignment-search-form" onsubmit="return false;" class="mb-4">
                                    <div class="input-group input-group-lg shadow-sm">
                                        <span class="input-group-text bg-white border-2 border-teal">
                                            <i class="fa fa-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-2 border-teal" 
                                               placeholder="Tìm kiếm môn học theo tên hoặc mã...">
                                        <button type="button" class="btn btn-outline-teal btn-search">
                                            <i class="fa fa-search"></i> Tìm
                                        </button>
                                    </div>
                                </form>

                                <!-- Bảng môn học -->
                                <div class="table-responsive rounded shadow-sm mb-4">
                                    <table class="table table-hover table-vcenter align-middle mb-0">
                                        <thead class="bg-teal-light text-dark">
                                            <tr>
                                                <th class="text-center" style="width: 80px;"></th>
                                                <th class="text-center fw-semibold">Mã môn</th>
                                                <th class="fw-semibold">Tên môn học</th>
                                                <th class="text-center fw-semibold">Tín chỉ</th>
                                                <th class="text-center fw-semibold">LT</th>
                                                <th class="text-center fw-semibold">TH</th>
                                            </tr>
                                        </thead>
                                        <tbody id="list-subject" class="fs-sm"></tbody>
                                    </table>
                                </div>

                                <div class="pagination-container modal-add-assignment-pagination"></div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="fa fa-times me-1"></i> Hủy
                                    </button>
                                    <button type="button" class="btn btn-teal shadow-sm px-4" id="btn_assignment">
                                        <i class="fa fa-save me-2"></i> Lưu phân công
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Chỉnh sửa phân công -->
<div class="modal fade" id="modal-default-vcenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-gradient-teal text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-edit me-2"></i> Chỉnh sửa phân công</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="form-edit-assignment">
                <div class="modal-body p-4">
                    <div class="row mb-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold text-teal">Giảng viên</label>
                            <select class="js-select2 form-select" id="edit-giang-vien" required></select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <label class="form-label fw-semibold text-teal">Môn học</label>
                            <select class="js-select2 form-select" id="edit-mon-hoc" required></select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-teal">Năm học</label>
                            <select class="js-select2 form-select" id="edit-namhoc"></select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-teal">Học kỳ</label>
                            <select class="js-select2 form-select" id="edit-hocky"></select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-teal shadow-sm">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>