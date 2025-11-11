
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Tất cả phân công</h3>
            <div class="block-options">
                <button data-role="phancong" data-action="create" type="button" class="btn btn-hero btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modal-add-assignment" id="add_assignment"><i class="fa-regular fa-plus"></i> Thêm
                    phân công mới</button>
            </div>
        </div>
        <div class="block-content">
            <form action="#" id="main-page-search-form" onsubmit="return false;">
                <div class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-alt" id="search-input" name="search-input"
                            placeholder="Tìm kiếm giảng viên, môn học...">
                        <button class="input-group-text bg-body border-0 btn-search">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">ID</th>
                            <th>Tên giảng viên</th>
                            <th class="text-center">Mã môn</th>
                            <th>Môn học</th>
                            <th class="text-center col-header-action">Action</th>
                        </tr>
                    </thead>
                    <tbody id="listAssignment">

                    </tbody>
                </table>
            </div>
            <?php if(isset($data["Plugin"]["pagination"])) require "./mvc/views/inc/pagination.php"?>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-add-assignment" tabindex="-1" role="dialog" aria-labelledby="modal-add-assignment"
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
                <li class="nav-item ms-auto">
                    <button type="button" class="btn btn-close p-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </li>
            </ul>
            <div class="modal-body block block-transparent bg-white mb-0 block-rounded">
                <div class="block-content tab-content">
                    <div class="tab-pane active" id="btabs-alt-static-home" role="tabpanel"
                        aria-labelledby="btabs-static-home-tab" tabindex="0">
                        <form class="mb-4 form-phancong">
                            <div class="row">
                                <div class="col-6 d-flex flex-row w-100">
                                    <div class="d-flex align-items-center">
                                        <label for="giang-vien" class="form-label" style="width: 100px">
                                            Giảng viên
                                        </label>
                                    </div>
                                    <select class="js-select2 form-select data-monhoc" data-tab="1" id="giang-vien"
                                        name="giang-vien" style="width: 100%;" data-placeholder="Chọn giảng viên cần phân công"
                                        required>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <form action="#" id="modal-add-assignment-search-form" onsubmit="return false;">
                            <div class="mb-4">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-alt" id="search-input" name="search-input"
                                        placeholder="Tìm kiếm môn học...">
                                    <button class="input-group-text bg-body border-0 btn-search">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="mb-4 row">
                            <div class="table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 100px;">Chọn</th>
                                            <th class="text-center">Mã môn học</th>
                                            <th>Tên môn học</th>
                                            <th class="text-center">Số tín chỉ</th>
                                            <th class="text-center">Số tiết lý thuyết</th>
                                            <th class="text-center">Số tiết thực hành</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list-subject">

                                    </tbody>
                                </table>
                            </div>
                            <?php if(isset($data["Plugin"]["pagination"])) require "./mvc/views/inc/pagination.php"?>
                        </div>
                        <div class="mb-4 d-flex flex-row-reverse">
                            <button type="submit" class="btn btn-alt-primary" id="btn_assignment"><i
                                    class="fa fa-fw fa-plus me-1"></i> Lưu phân công</button>
                            <!-- <button class="btn btn-alt-primary" id="edit_assignment"><i class="fa fa-fw fa-plus me-1"></i> Sửa phân công</button> -->
                            <input type="hidden" value="" id="question_id">
                        </div>
                        <!-- <form method="POST" onsubmit="return false;">
                        </form> -->
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
                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="edit-giang-vien" class="form-label fw-semibold text-teal">
                                <i class="fa fa-chalkboard-teacher me-2"></i> Giảng viên
                            </label>
                            <select class="js-select2 form-select form-select-lg shadow-sm border-teal" 
                                    id="edit-giang-vien" name="edit-giang-vien" 
                                    style="width: 100%;" data-placeholder="Chọn giảng viên" required disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <label for="edit-mon-hoc" class="form-label fw-semibold text-teal">
                                <i class="fa fa-book-open me-2"></i> Môn học
                            </label>
                            <select class="js-select2 form-select form-select-lg shadow-sm border-teal" 
                                    id="edit-mon-hoc" name="edit-mon-hoc" 
                                    style="width: 100%;" data-placeholder="Chọn môn học" required>
                                <option value=""></option>
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
.border-teal {
    border-color: #14b8a6 !important;
}
.shadow-lg {
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}
.table-hover tbody tr:hover {
    background-color: #f1f5f9;
}
.form-control:focus, .form-select:focus {
    border-color: #14b8a6;
    box-shadow: 0 0 0 0.2rem rgba(20, 184, 166, 0.25);
}
@media (max-width: 576px) {
    .block-header h3 { font-size: 1.25rem; }
    .btn-hero-sm, .btn-sm { font-size: 0.875rem; padding: 0.5rem 1rem; }
    .table th, .table td { font-size: 0.875rem; }
}
</style>