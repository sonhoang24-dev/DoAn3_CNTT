<div class="content" data-id="<?php echo $data["user_id"] ?>">
    <!-- Thanh tìm kiếm và lọc trạng thái -->
<!-- Thanh tìm kiếm & Bộ lọc trạng thái -->
<div class="row mb-5 justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">
        <div class="search-filter-wrapper p-3 bg-white rounded-4 shadow-lg border-0" 
             style="backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
            <form id="search-form" onsubmit="return false;" class="d-flex flex-column flex-md-row gap-3 align-items-center">
                <!-- Bộ lọc trạng thái -->
                <div class="dropdown flex-shrink-0">
                    <button class="btn btn-outline-primary dropdown-toggle px-4 py-2 fw-600 rounded-pill d-flex align-items-center gap-2"
                            id="dropdown-filter-state" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-filter fa-sm"></i>
                        <span class="filter-text">Tất cả</span>
                        <i class="fas fa-chevron-down ms-2 fa-xs"></i>
                    </button>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3" aria-labelledby="dropdown-filter-state">
                        <li><a class="dropdown-item py-2 filtered-by-state" href="#" data-value="4">
                            <i class="fas fa-globe me-2"></i> Tất cả
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 filtered-by-state" href="#" data-value="0">
                            <span class="badge bg-secondary rounded-pill me-2">•</span> Chưa làm
                        </a></li>
                        <li><a class="dropdown-item py-2 filtered-by-state" href="#" data-value="1">
                            <span class="badge bg-danger rounded-pill me-2">!</span> Quá hạn
                        </a></li>
                        <li><a class="dropdown-item py-2 filtered-by-state" href="#" data-value="2">
                            <span class="badge bg-warning rounded-pill me-2">●</span> Chưa mở
                        </a></li>
                        <li><a class="dropdown-item py-2 filtered-by-state" href="#" data-value="3">
                            <span class="badge bg-success rounded-pill me-2">✓</span> Đã hoàn thành
                        </a></li>
                    </ul>
                </div>

                <!-- Thanh tìm kiếm -->
                <div class="flex-grow-1 position-relative">
                    <input type="text" 
                           class="form-control form-control-lg rounded-pill ps-5 py-3 shadow-sm border-0" 
                           placeholder="Tìm kiếm đề thi, tên môn học, mã đề..." 
                           id="search-input" 
                           autocomplete="off">
                    <i class="fas fa-search position-absolute top-50 start-3 translate-middle-y text-muted"></i>
                </div>

                <!-- Nút làm mới (tùy chọn) -->
              
            </form>
        </div>
    </div>
</div>

<!-- Bảng danh sách đề thi - ĐẸP & HIỆN ĐẠI -->
<div class="table-responsive rounded-4 overflow-hidden shadow-xl" style="border-radius: 20px;">
    <table class="table table-hover align-middle mb-0 modern-table">
        <thead>
            <tr class="text-white text-uppercase fw-bold" 
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <th class="py-4 px-4">Tên đề thi</th>
                <th class="py-4 px-4">Môn học</th>
                <th class="py-4 px-4">Thời gian bắt đầu</th>
                <th class="py-4 px-4">Kết thúc</th>
                <th class="py-4 px-4">Nhóm</th>
                <th class="py-4 px-4">Điểm</th>
                <th class="py-4 px-4 text-center">Trạng thái</th>
                <th class="py-4 px-4 text-center">Hành động</th>
            </tr>
        </thead>
        <tbody class="list-test bg-white fw-medium">
            <!-- Ví dụ 1 đề thi -->
            <tr class="border-bottom">
                <td class="py-4 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-file-alt text-primary fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Đề thi giữa kỳ - Toán Cao cấp</h6>
                            <small class="text-muted">Mã đề: MATH2025-GK01</small>
                        </div>
                    </div>
                </td>
                <td class="py-4 px-4">
                    <span class="badge bg-info bg-opacity-20 text-info px-3 py-2 rounded-pill fw-600">
                        Toán Cao cấp
                    </span>
                </td>
                <td class="py-4 px-4">
                    <i class="far fa-clock text-primary me-2"></i>
                    14:00 - 02/12/2025
                </td>
                <td class="py-4 px-4">
                    <i class="far fa-calendar-times text-danger me-2"></i>
                    16:00 - 02/12/2025
                </td>
                <td class="py-4 px-4">
                    <div class="avatar-group">
                        <span class="avatar avatar-sm rounded-circle bg-success text-white">L1</span>
                        <span class="avatar avatar-sm rounded-circle bg-warning text-white">L2</span>
                    </div>
                </td>
                <td class="py-4 px-4 fw-bold text-success fs-5">--</td>
                <td class="py-4 px-4 text-center">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-600">
                        <i class="fas fa-hourglass-start me-1"></i> Chưa mở
                    </span>
                </td>
                <td class="py-4 px-4 text-center">
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" disabled>
                        <i class="fas fa-play me-1"></i> Làm bài
                    </button>
                </td>
            </tr>

            <!-- Thêm các dòng khác tương tự... -->
        </tbody>
    </table>
</div>

<!-- Loading Skeleton (hiển thị khi đang tải dữ liệu) -->
<div class="table-skeleton d-none mt-4">
    <div class="placeholder-glow">
        <div class="placeholder col-12 rounded-4" style="height: 80px;"></div>
        <div class="placeholder col-12 rounded-4 mt-3" style="height: 80px;"></div>
        <div class="placeholder col-12 rounded-4 mt-3" style="height: 80px;"></div>
    </div>
</div>

    <!-- Phân trang -->
    <div class="row my-3">
        <?php if (isset($data["Plugin"]["pagination"])) {
            require "./mvc/views/inc/pagination.php";
        } ?>
    </div>
</div>
<style>
.modern-table tr:hover {
    background-color: #f8f9ff !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.1);
}

.avatar-group .avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    font-size: 0.8rem;
    font-weight: bold;
}

.search-filter-wrapper {
    background: rgba(255, 255, 255, 0.95) !important;
}

@media (max-width: 768px) {
    .modern-table thead {
        display: none;
    }
    .modern-table tr {
        display: block;
        margin-bottom: 1.5rem;
        border: 1px solid #eee;
        border-radius: 16px;
        padding: 1rem;
    }
    .modern-table td {
        display: block;
        text-align: right !important;
        padding: 0.5rem 0 !important;
        border: none;
    }
    .modern-table td::before {
        content: attr(data-label);
        float: left;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.8rem;
        color: #667eea;
    }
}
</style>
