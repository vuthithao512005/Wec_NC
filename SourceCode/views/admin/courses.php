<div class="container-fluid p-0">
    <div class="mb-4">
        <h2 class="fw-bold mb-1 text-dark">📚 Quản lý khóa học</h2>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div id="mini-alert" class="alert alert-success shadow border-0 py-2" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 250px; border-radius: 10px;">
            <small><i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?></small>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div id="mini-alert" class="alert alert-danger shadow border-0 py-2" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 250px; border-radius: 10px;">
            <small><i class="fa-solid fa-triangle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?></small>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4 text-primary"><i class="fa-solid fa-plus-circle me-2"></i>Thêm khóa học mới</h5>
            <form method="POST" action="index.php?page=admin_courses" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Tên khóa học</label>
                        <input type="text" name="title" class="form-control custom-input" placeholder="Ví dụ: Lập trình PHP toàn tập" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">Loại khóa học</label>
                        <select name="category_id" class="form-select custom-input" required>
                            <option value="">-- Chọn loại --</option>
                            <?php if(!empty($categories)): ?>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">Giá tiền (VNĐ)</label>
                        <div class="input-group">
                            <input type="number" name="price" class="form-control custom-input border-end-0" placeholder="0 = Miễn phí" min="0">
                            <span class="input-group-text bg-white border-start-0 text-muted">đ</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Hình ảnh khóa học</label>
                        <input type="file" name="image_file" class="form-control custom-input mb-2">
                        <input type="text" name="image" class="form-control custom-input form-control-sm" placeholder="Hoặc dán URL ảnh tại đây">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Mô tả ngắn gọn</label>
                        <textarea name="desc" class="form-control custom-input" rows="3" placeholder="Tóm tắt nội dung chính của khóa học..."></textarea>
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button name="create" class="btn btn-primary px-5 py-2 fw-bold shadow-sm" style="border-radius: 12px;">
                            LƯU KHÓA HỌC
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-5 border-bottom pb-3">
        <h4 class="fw-bold text-dark mb-0">
            <i class="fa-solid fa-layer-group me-2 text-primary"></i>Danh sách khóa học
        </h4>
        <span class="badge bg-light text-dark border px-3 py-2 fs-6 shadow-sm" style="border-radius: 10px;">
            Tổng cộng: <?= count($courses ?? []) ?>
        </span>
    </div>

    <div class="row g-4">
        <?php if(!empty($courses)): ?>
            <?php foreach($courses as $c): ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm course-card-admin">
                        <div class="position-relative">
                            <img src="<?= !empty($c['image']) ? htmlspecialchars($c['image']) : 'https://via.placeholder.com/300x160' ?>" 
                                 class="card-img-top" style="height: 160px; object-fit: cover; border-radius: 15px 15px 0 0;">
                            
                            <span class="badge bg-white text-dark position-absolute top-0 start-0 m-2 shadow-sm fw-bold">
                                <?= htmlspecialchars($c['category_name'] ?? 'Chưa phân loại') ?>
                            </span>

                            <div class="position-absolute bottom-0 end-0 m-2">
                                <?php if($c['price'] == 0): ?>
                                    <span class="badge bg-success shadow-sm px-3 py-2">MIỄN PHÍ</span>
                                <?php else: ?>
                                    <span class="badge bg-primary shadow-sm px-3 py-2"><?= number_format($c['price']) ?> đ</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="fw-bold text-dark mb-2 text-truncate-2"><?= htmlspecialchars($c['title']) ?></h6>
                            <p class="text-muted small mb-4 flex-grow-1">
                                <?= mb_strimwidth(strip_tags($c['description'] ?? ''), 0, 80, "...") ?>
                            </p>

                            <div class="d-flex gap-2 border-top pt-3">
                                <button class="btn btn-outline-warning btn-sm flex-grow-1 fw-bold" 
                                        onclick='openEdit(<?= json_encode($c, JSON_HEX_TAG) ?>)'>
                                    <i class="fa-solid fa-pen-to-square me-1"></i>Sửa
                                </button>
                                <a href="index.php?page=admin_courses&delete=<?= $c['id'] ?>" 
                                   class="btn btn-outline-danger btn-sm px-3" 
                                   onclick="return confirm('Xóa khóa học sẽ mất hết bài học liên quan. Bạn chắc chắn?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">Không có khóa học nào được tìm thấy.</p>
                <a href="index.php?page=admin_courses" class="btn btn-link">Tải lại danh sách</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="index.php?page=admin_courses" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">✏️ Cập nhật thông tin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id" id="edit_id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">Tên khóa học</label>
                        <input name="title" id="edit_title" class="form-control custom-input" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Giá tiền (đ)</label>
                        <input type="number" name="price" id="edit_price" class="form-control custom-input">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Loại</label>
                        <select name="category_id" id="edit_category_id" class="form-select custom-input" required>
                            <?php if(!empty($categories)): ?>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">Mô tả</label>
                        <textarea name="desc" id="edit_desc" class="form-control custom-input" rows="4"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small">Ảnh khóa học</label>
                        <input type="file" name="image_file" class="form-control custom-input mb-2">
                        <input name="image" id="edit_image" class="form-control custom-input form-control-sm">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Đóng</button>
                <button name="update" class="btn btn-primary px-5 fw-bold shadow-sm" style="border-radius: 12px;">LƯU THAY ĐỔI</button>
            </div>
        </form>
    </div>
</div>

<style>
    .course-card-admin {
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    .course-card-admin:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    .custom-input {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
    }
    .custom-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.8em;
    }
    .badge { border-radius: 8px; }
</style>

<script>
    // 1. Tự động ẩn thông báo sau 2.5 giây (Đồng bộ với trang danh mục)
    setTimeout(function() {
        let alertBox = document.getElementById('mini-alert');
        if (alertBox) {
            alertBox.style.transition = "opacity 0.5s ease";
            alertBox.style.opacity = "0"; 
            setTimeout(() => alertBox.remove(), 500); 
        }
    }, 2500);

    // 2. Mở Modal
    let editModalInstance;
    document.addEventListener('DOMContentLoaded', function() {
        editModalInstance = new bootstrap.Modal(document.getElementById('editModal'));
    });

    function openEdit(c){
        document.getElementById('edit_id').value = c.id;
        document.getElementById('edit_title').value = c.title;
        document.getElementById('edit_desc').value = c.description;
        document.getElementById('edit_price').value = c.price;
        document.getElementById('edit_category_id').value = c.category_id;
        document.getElementById('edit_image').value = c.image;
        editModalInstance.show();
    }
</script>