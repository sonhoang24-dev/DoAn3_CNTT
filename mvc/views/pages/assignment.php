<!-- Main Content -->
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
                           placeholder="Tìm kiếm giảng viên, môn học...">
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
                            <th class="text-center fw-semibold" style="width: 80px;"><i class="fa fa-list-ol me-1"></i> ID</th>
                            <th class="fw-semibold"><i class="fa fa-user me-1"></i> Tên giảng viên</th>
                            <th class="text-center fw-semibold" style="width: 120px;"><i class="fa fa-book me-1"></i> Mã môn</th>
                            <th class="fw-semibold"><i class="fa fa-book-open me-1"></i> Môn học</th>
                            <th class="text-center fw-semibold" style="width: 120px;"><i class="fa fa-cogs me-1"></i> Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="listAssignment" class="fs-sm">
    <?php if(!empty($assignments)) : ?>
        <?php foreach($assignments as $index => $a) : ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($a['giangvien_name']) ?></td>
                <td class="text-center"><?= htmlspecialchars($a['monhoc_code']) ?></td>
                <td><?= htmlspecialchars($a['monhoc_name']) ?></td>
                <td class="text-center">
                    <!-- Nút sửa với icon FontAwesome -->
                    <button class="btn btn-sm btn-outline-teal me-1 btn-edit" 
                            data-id="<?= $a['assignment_id'] ?>" 
                            title="Chỉnh sửa">
                        <i class="fa fa-edit"></i>
                    </button>
                    <!-- Nút xóa với icon FontAwesome -->
                    <button class="btn btn-sm btn-outline-danger btn-delete" 
                            data-id="<?= $a['assignment_id'] ?>" 
                            title="Xóa">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center text-muted">Chưa có phân công nào</td>
        </tr>
    <?php endif; ?>
</tbody>

                </table>
            </div>

            <!-- Pagination -->
            <?php if(isset($data["Plugin"]["pagination"])) require "./mvc/views/inc/pagination.php"; ?>
        </div>
    </div>
</div>

<!-- Modal: Thêm phân công mới -->
<div class="modal fade" id="modal-add-assignment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <!-- Tab header + Close button -->
            <ul class="nav nav-tabs nav-tabs-alt bg-light border-0" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-semibold" data-bs-toggle="tab" 
                            data-bs-target="#btabs-alt-static-home">
                        <i class="fa fa-user-plus me-2"></i> Thêm thủ công
                    </button>
                </li>
                <li class="nav-item ms-auto">
                    <button type="button" class="btn-close p-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </li>
            </ul>

            <div class="modal-body p-0">
                <div class="block block-rounded block-transparent mb-0 bg-white">
                    <div class="block-content tab-content p-4">
                        <div class="tab-pane fade show active" id="btabs-alt-static-home">
                            <!-- Lecturer Selection -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="giang-vien" class="form-label fw-semibold text-teal">
                                        <i class="fa fa-chalkboard-teacher me-2"></i> Giảng viên
                                    </label>
                                    <select class="js-select2 form-select form-select-lg shadow-sm border-teal" 
                                            data-tab="1" id="giang-vien" name="giang-vien" 
                                            style="width: 100%;" data-placeholder="Chọn giảng viên cần phân công" required>
                                        <option value=""></option>
                                        <!-- Options loaded by JS -->
                                    </select>
                                </div>
                            </div>

                            <!-- Subject Search -->
                            <form id="modal-add-assignment-search-form" onsubmit="return false;" class="mb-4">
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-2 border-teal">
                                        <i class="fa fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-2 border-teal" 
                                           id="search-input" name="search-input" 
                                           placeholder="Tìm kiếm môn học theo tên hoặc mã...">
                                    <button type="button" class="btn btn-outline-teal btn-search" 
                                            data-bs-toggle="tooltip" title="Tìm kiếm">
                                        <i class="fa fa-search"></i> Tìm
                                    </button>
                                </div>
                            </form>

                            <!-- Subject Table -->
                            <div class="table-responsive rounded shadow-sm mb-4">
                                <table class="table table-hover table-vcenter align-middle mb-0">
                                    <thead class="bg-teal-light text-dark">
                                        <tr>
                                            <th class="text-center" style="width: 80px;">
                                                <i class="fa fa-check-square me-1"></i>
                                            </th>
                                            <th class="text-center fw-semibold"><i class="fa fa-book me-1"></i> Mã môn</th>
                                            <th class="fw-semibold"><i class="fa fa-book-open me-1"></i> Tên môn học</th>
                                            <th class="text-center fw-semibold"><i class="fa fa-graduation-cap me-1"></i> Tín chỉ</th>
                                            <th class="text-center fw-semibold"><i class="fa fa-chalkboard me-1"></i> LT</th>
                                            <th class="text-center fw-semibold"><i class="fa fa-laptop-code me-1"></i> TH</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list-subject" class="fs-sm">
                                        <!-- Load by JS -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination in Modal -->
                            <?php if(isset($data["Plugin"]["pagination"])) require "./mvc/views/inc/pagination.php"; ?>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fa fa-times me-1"></i> Hủy
                                </button>
                                <button type="submit" class="btn btn-teal shadow-sm px-4" id="btn_assignment">
                                    <i class="fa fa-save me-2"></i> Lưu phân công
                                </button>
                            </div>
                            <input type="hidden" value="" id="question_id">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Chỉnh sửa phân công -->
<div class="modal fade" id="modal-default-vcenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-gradient-teal text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa fa-edit me-2"></i> Chỉnh sửa phân công
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-assignment">
                <div class="modal-body p-4">
                    <input type="hidden" name="assignment_id" id="assignment_id">
                    <!-- Lecturer Selection -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="edit-giang-vien" class="form-label fw-semibold text-teal">
                                <i class="fa fa-chalkboard-teacher me-2"></i> Giảng viên
                            </label>
                            <select class="js-select2 form-select form-select-lg shadow-sm border-teal" 
                                    id="edit-giang-vien" name="edit-giang-vien" 
                                    style="width: 100%;" data-placeholder="Chọn giảng viên" required>
                                <option value=""></option>
                                <!-- Options loaded by JS -->
                            </select>
                        </div>
                    </div>
                    <!-- Subject Selection -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="edit-mon-hoc" class="form-label fw-semibold text-teal">
                                <i class="fa fa-book-open me-2"></i> Môn học
                            </label>
                            <select class="js-select2 form-select form-select-lg shadow-sm border-teal" 
                                    id="edit-mon-hoc" name="edit-mon-hoc" 
                                    style="width: 100%;" data-placeholder="Chọn môn học" required>
                                <option value=""></option>
                                <!-- Options loaded by JS -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Hủy
                    </button>
                    <button type="submit" class="btn btn-teal shadow-sm" id="btn-save-edit">
                        <i class="fa fa-save me-1"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Custom CSS -->
<style>
/* Gradient Backgrounds */
.bg-gradient-teal {
    background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
}
.bg-teal-light {
    background-color: #ccfbf1 !important;
}

/* Custom Button Styles */
.btn-teal {
    background-color: #14b8a6;
    border-color: #14b8a6;
    color: #fff;
}
.btn-teal:hover {
    background-color: #0d9488;
    border-color: #0d9488;
}
.btn-outline-teal {
    border-color: #14b8a6;
    color: #14b8a6;
}
.btn-outline-teal:hover {
    background-color: #14b8a6;
    color: #fff;
}

/* Border and Shadow Enhancements */
.border-teal {
    border-color: #14b8a6 !important;
}
.shadow-lg {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

/* Table Hover Effect */
.table-hover tbody tr:hover {
    background-color: #f1f5f9;
}

/* Form Control Focus */
.form-control:focus, .form-select:focus {
    border-color: #14b8a6;
    box-shadow: 0 0 0 0.2rem rgba(20, 184, 166, 0.25);
}

/* Responsive Adjustments */
@media (max-width: 576px) {
    .block-header h3 {
        font-size: 1.25rem;
    }
    .btn-hero-sm, .btn-sm {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
    .table th, .table td {
        font-size: 0.875rem;
    }
}
</style>