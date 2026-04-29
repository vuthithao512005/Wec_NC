<div class="container-fluid p-0">
    <div class="mb-4">
        <h2 class="fw-bold mb-1 text-dark">📚 Quản lý bài học</h2>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div id="mini-alert" class="alert alert-success shadow-sm border-0 py-2 mb-3" style="border-radius: 10px;">
            <small><i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?></small>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4 text-primary"><i class="fa-solid fa-plus-circle me-2"></i>Thêm bài học mới</h5>
            <form method="POST" action="index.php?page=admin_lessons" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Tiêu đề bài học</label>
                        <input type="text" name="title" class="form-control custom-input" placeholder="Ví dụ: 01. Giới thiệu về PHP" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted">Thuộc khóa học</label>
                        <select name="course_id" class="form-select custom-input" required>
                            <option value="">-- Chọn khóa học --</option>
                            <?php foreach($coursesList as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold small text-muted">Thứ tự hiển thị</label>
                        <input type="number" name="position" class="form-control custom-input text-center" value="1" min="1">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Video bài học</label>
                        <div class="mb-2">
                            <input type="file" name="video_file" class="form-control custom-input" accept="video/*">
                        </div>
                        <input type="url" name="video" class="form-control custom-input form-control-sm" placeholder="Hoặc dán URL Video (Youtube/Embed)">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Mô tả bài học</label>
                        <textarea name="content" class="form-control custom-input" rows="3" placeholder="Tóm tắt nội dung chính của bài học..."></textarea>
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button name="create" class="btn btn-primary px-5 py-2 fw-bold shadow-sm" style="border-radius: 12px;">
                            LƯU BÀI HỌC
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-5 border-bottom pb-3">
        <h4 class="fw-bold text-dark mb-0">
            <i class="fa-solid fa-layer-group me-2 text-primary"></i>Danh sách bài học
        </h4>
        <form method="GET" class="d-flex gap-2">
            <input type="hidden" name="page" value="admin_lessons">
            <select name="course_id" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()" style="border-radius: 10px; min-width: 200px;">
                <option value="">--- Tất cả khóa học ---</option>
                <?php foreach($coursesList as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= (isset($_GET['course_id']) && $_GET['course_id'] == $c['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-muted small" style="width: 80px;">ID</th>
                        <th class="text-muted small">BÀI HỌC</th>
                        <th class="text-muted small">VIDEO</th>
                        <th class="text-muted small text-center">THỨ TỰ</th>
                        <th class="text-muted small text-end pe-4">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $filtered = $lessons;
                    if(!empty($_GET['course_id'])) {
                        $filtered = array_filter($lessons, fn($l) => $l['course_id'] == $_GET['course_id']);
                    }
                    if(!empty($filtered)): foreach($filtered as $l): 
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold">#<?= $l['id'] ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($l['title']) ?></div>
                            <small class="text-muted">Khóa: <?= $l['course_id'] ?></small>
                        </td>
                        <td>
                            <?php if(!empty($l['video'])): ?>
                                <a href="<?= $l['video'] ?>" target="_blank" class="badge bg-soft-danger text-danger text-decoration-none">
                                    <i class="fa-solid fa-play me-1"></i> Video
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">Trống</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1"><?= $l['position'] ?? 0 ?></span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light text-primary me-1 shadow-sm" onclick='openEditLesson(<?= json_encode($l, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <a href="index.php?page=admin_lessons&delete=<?= $l['id'] ?>" class="btn btn-sm btn-light text-danger shadow-sm" onclick="return confirm('Xóa bài học này?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted small">Chưa có bài học nào được tìm thấy.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditLesson" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="index.php?page=admin_lessons" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-bold m-0">✏️ Cập nhật bài học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id" id="edit_lesson_id">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold small">Tiêu đề bài học</label>
                        <input name="title" id="edit_lesson_title" class="form-control custom-input" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Thứ tự</label>
                        <input type="number" name="position" id="edit_lesson_pos" class="form-control custom-input">
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label fw-bold small text-muted">Mô tả / Nội dung bài học</label>
                        <textarea name="content" id="edit_lesson_content" class="form-control custom-input" rows="4"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold small text-muted">Video mới (Bỏ trống nếu giữ cũ)</label>
                        <input type="file" name="video_file" class="form-control custom-input mb-2" accept="video/*">
                        <input name="video" id="edit_lesson_video" class="form-control custom-input" placeholder="Link Video URL">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="submit" name="update" class="btn btn-primary px-5 fw-bold shadow-sm" style="border-radius: 12px;">CẬP NHẬT</button>
            </div>
        </form>
    </div>
</div>

<style>
    .custom-input { border-radius: 10px; padding: 10px 15px; border: 1px solid #e2e8f0; background-color: #fcfcfd; }
    .custom-input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); background-color: #fff; }
    .bg-soft-danger { background-color: #fef2f2; color: #dc2626; }
    .table thead th { border: none; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
</style>

<script>
    // Tự ẩn thông báo
    setTimeout(() => {
        const alert = document.getElementById('mini-alert');
        if(alert) {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }
    }, 2500);

    // Mở modal sửa
    let modalEdit;
    document.addEventListener('DOMContentLoaded', function() {
        modalEdit = new bootstrap.Modal(document.getElementById('modalEditLesson'));
    });

    function openEditLesson(l) {
        document.getElementById('edit_lesson_id').value = l.id;
        document.getElementById('edit_lesson_title').value = l.title;
        document.getElementById('edit_lesson_video').value = l.video;
        document.getElementById('edit_lesson_pos').value = l.position;
        
        // QUAN TRỌNG: Gán nội dung content vào textarea của Modal
        document.getElementById('edit_lesson_content').value = l.content ? l.content : '';
        
        modalEdit.show();
    }
</script>