<div class="container-fluid p-0">
    <div class="mb-4">
        <h2 class="fw-bold mb-1 text-dark">🗂️ Quản lý danh mục</h2>
        <p class="text-muted small">Phân loại khóa học giúp hệ thống ngăn nắp và học viên dễ dàng tìm kiếm.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <?php if(isset($_SESSION['success'])): ?>
                <div id="mini-alert" class="alert alert-success shadow-sm border-0 py-2 mb-3" style="border-radius: 10px;">
                    <small><i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?></small>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>
                <div id="mini-alert" class="alert alert-danger shadow-sm border-0 py-2 mb-3" style="border-radius: 10px;">
                    <small><i class="fa-solid fa-triangle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?></small>
                </div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm" style="border-radius: 20px; position: sticky; top: 90px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-primary">
                        <i class="fa-solid fa-folder-plus me-2"></i>Thêm danh mục
                    </h5>
                    <form method="POST" action="index.php?page=admin_categories">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Tên danh mục</label>
                            <input type="text" name="name" class="form-control custom-input" 
                                   placeholder="Ví dụ: Lập trình Web, Ngoại ngữ..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Mô tả (Không bắt buộc)</label>
                            <textarea name="description" class="form-control custom-input" rows="3" 
                                      placeholder="Mô tả ngắn gọn về nhóm này..."></textarea>
                        </div>
                        <button name="create" class="btn btn-primary w-100 py-2 fw-bold shadow-sm" style="border-radius: 12px;">
                            TẠO DANH MỤC
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-0">
                    <div class="p-4 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="fw-bold mb-0">
                            <i class="fa-solid fa-list-ul me-2 text-primary"></i>Danh sách hiện có
                        </h5>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            Tổng cộng: <?= count($categories ?? []) ?>
                        </span>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" width="80">ID</th>
                                    <th>Tên danh mục</th>
                                    <th>Số khóa học</th>
                                    <th class="text-end pe-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($categories)): ?>
                                    <?php foreach($categories as $cat): ?>
                                    <tr>
                                        <td class="ps-4 text-muted fw-bold">#<?= $cat['id'] ?></td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($cat['name']) ?></div>
                                            <div class="text-muted small"><?= mb_strimwidth($cat['description'] ?? 'Không có mô tả', 0, 50, "...") ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-primary text-primary px-3">
                                                <?= $cat['course_count'] ?? 0 ?> khóa học
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-light text-warning fw-bold me-1" 
                                                    onclick='openEdit(<?= json_encode($cat, JSON_HEX_TAG) ?>)'
                                                    style="border-radius: 8px;">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <a href="index.php?page=admin_categories&delete=<?= $cat['id'] ?>" 
                                               class="btn btn-sm btn-light text-danger fw-bold" 
                                               style="border-radius: 8px;"
                                               onclick="return confirm('Xóa danh mục này có thể ảnh hưởng đến các khóa học bên trong. Chắc chắn xóa?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">Chưa có danh mục nào được tạo.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="index.php?page=admin_categories" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">✏️ Cập nhật danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id" id="edit_id">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Tên danh mục</label>
                    <input name="name" id="edit_name" class="form-control custom-input" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Mô tả</label>
                    <textarea name="description" id="edit_description" class="form-control custom-input" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Đóng</button>
                <button name="update" class="btn btn-primary px-5 fw-bold shadow-sm" style="border-radius: 12px;">CẬP NHẬT</button>
            </div>
        </form>
    </div>
</div>

<style>
    .custom-input {
        border-radius: 12px;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        background-color: #fcfcfd;
    }
    .custom-input:focus {
        border-color: #4f46e5;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }
    .bg-soft-primary { background-color: #eef2ff; }
    .table thead th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 15px;
    }
    .table tbody td { padding: 15px; }
</style>

<script>
    // 1. Tự động ẩn thông báo sau 2.5 giây
    setTimeout(() => {
        const alert = document.getElementById('mini-alert');
        if (alert) {
            alert.style.transition = "all 0.5s ease";
            alert.style.opacity = "0";
            alert.style.transform = "translateY(-10px)";
            setTimeout(() => alert.remove(), 500);
        }
    }, 2500);

    // 2. Mở Modal Sửa
    let editModal;
    document.addEventListener('DOMContentLoaded', function() {
        editModal = new bootstrap.Modal(document.getElementById('editCatModal'));
    });

    function openEdit(cat){
        document.getElementById('edit_id').value = cat.id;
        document.getElementById('edit_name').value = cat.name;
        document.getElementById('edit_description').value = cat.description;
        editModal.show();
    }
</script>