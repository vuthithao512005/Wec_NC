<?php
// Tái cấu trúc dữ liệu: Gom nhóm theo Bài học
$lessons_display = [];
if (!empty($grouped_data)) {
    foreach ($grouped_data as $c_id => $group) {
        if (!empty($group['questions'])) {
            foreach ($group['questions'] as $q) {
                $l_id = $q['lesson_id'];
                if (!isset($lessons_display[$l_id])) {
                    $lessons_display[$l_id] = [
                        'course_title' => $group['course_title'],
                        'lesson_title' => $q['lesson_title'],
                        'questions'    => []
                    ];
                }
                $lessons_display[$l_id]['questions'][] = $q;
            }
        }
    }
}
?>

<div class="container-fluid pt-2 pb-5 px-4 min-vh-100" style="background-color: #f8fafc;">
    
    <div class="d-flex align-items-center mb-3">
        <div class="d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 45px; height: 45px; border-radius: 12px; background: linear-gradient(135deg, #6366f1, #4f46e5); color: white;">
            <i class="fa-solid fa-clipboard-question fs-5"></i>
        </div>
        <div>
            <h2 class="fw-bolder text-dark mb-0" style="letter-spacing: -0.5px; font-size: 1.75rem;">Quản lý Ngân hàng Quiz</h2>
        </div>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert bg-success text-white shadow-sm border-0 py-2 px-3 mb-3 d-inline-flex align-items-center mini-alert" style="border-radius: 12px;">
            <i class="fa-solid fa-circle-check me-2 fs-5"></i>
            <span class="small fw-medium"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: #ffffff;">
        <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="fw-bolder text-dark mb-0" style="letter-spacing: -0.5px;">
                <i class="fa-solid fa-circle-plus text-primary me-2"></i>Thêm câu hỏi mới
            </h5>
            <button class="btn btn-outline-success fw-bold px-3 py-2 bg-white border-2 shadow-sm transition-hover" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalImport">
                <i class="fa-solid fa-file-excel me-1"></i> Nhập từ Excel
            </button>
        </div>

        <div class="card-body p-4 pt-3">
            <form action="index.php?page=admin_quiz" method="POST">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="fw-bold small text-muted mb-2">Thuộc Bài học <span class="text-danger">*</span></label>
                        <select name="lesson_id" class="form-select border-light shadow-none bg-light py-2 px-3 input-modern" required>
                            <option value="">-- Chọn bài học --</option>
                            <?php if(!empty($all_lessons)): foreach($all_lessons as $l): ?>
                                <option value="<?= $l['id'] ?>">Bài <?= $l['id'] ?>: <?= htmlspecialchars($l['title']) ?> (Khóa: <?= htmlspecialchars($l['course_title'] ?? '') ?>)</option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-8">
                        <label class="fw-bold small text-muted mb-2">Nội dung câu hỏi <span class="text-danger">*</span></label>
                        <textarea name="question" class="form-control border-light shadow-none bg-light py-2 px-3 input-modern" rows="1" placeholder="Nhập câu hỏi trắc nghiệm..." required></textarea>
                    </div>

                    <div class="col-12 mt-2">
                        <label class="fw-bold small text-muted mb-3"><i class="fa-solid fa-circle-check text-success me-1"></i> Tích chọn nút tròn bên cạnh để đánh dấu đáp án ĐÚNG</label>
                        <div class="row g-3">
                            <?php foreach(['A', 'B', 'C', 'D'] as $opt): ?>
                            <div class="col-md-6">
                                <div class="input-group border border-light bg-light rounded-3 p-1 answer-group">
                                    <span class="input-group-text bg-transparent border-0 pe-1">
                                        <input type="radio" class="form-check-input mt-0 answer-radio" name="is_correct" value="<?= strtoupper($opt) ?>" required>
                                    </span>
                                    <span class="input-group-text bg-transparent border-0 fw-bold text-muted ps-1"><?= strtoupper($opt) ?>.</span>
                                    <input type="text" name="option_<?= strtolower($opt) ?>" class="form-control border-0 bg-transparent shadow-none px-2" placeholder="Nhập đáp án <?= $opt ?>..." required>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top border-light">
                    <button type="submit" name="save_question" class="btn btn-primary fw-bold py-2 px-5 shadow-sm transition-hover" style="border-radius: 10px; background: #4f46e5; border: none;">
                        LƯU CÂU HỎI
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3" style="border-radius: 16px 16px 0 0;">
            <h5 class="fw-bolder text-dark mb-0" style="letter-spacing: -0.5px;"><i class="fa-solid fa-list-check text-primary me-2"></i>Kho lưu trữ</h5>
            
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <form method="GET" class="m-0 d-flex gap-2">
                    <input type="hidden" name="page" value="admin_quiz">
                    <select name="course_id" class="form-select form-select-sm border-light bg-light rounded-3 shadow-none fw-medium px-3 py-2" onchange="this.form.submit()">
                        <option value="">-- Lọc Khóa học --</option>
                        <?php if(!empty($courses)): foreach($courses as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= (isset($_GET['course_id']) && $_GET['course_id'] == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['title']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    
                    <select name="lesson_id" class="form-select form-select-sm border-light bg-light rounded-3 shadow-none fw-medium px-3 py-2" onchange="this.form.submit()">
                        <option value="">-- Lọc Bài học --</option>
                        <?php 
                        if(!empty($all_lessons)): 
                            $selected_course = $_GET['course_id'] ?? '';
                            foreach($all_lessons as $l): 
                                if ($selected_course !== '' && $l['course_id'] != $selected_course) { continue; }
                        ?>
                            <option value="<?= $l['id'] ?>" <?= (isset($_GET['lesson_id']) && $_GET['lesson_id'] == $l['id']) ? 'selected' : '' ?>>
                                Bài <?= $l['id'] ?>: <?= htmlspecialchars($l['title']) ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>

                    <?php if(!empty($_GET['course_id']) || !empty($_GET['lesson_id'])): ?>
                        <a href="index.php?page=admin_quiz" class="btn btn-sm btn-light border py-2 px-3 rounded-3" title="Bỏ lọc"><i class="fa-solid fa-xmark text-danger"></i></a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        
        <div class="card-body p-4 pt-2 bg-light bg-opacity-50">
            <div class="accordion border-0 shadow-none" id="lessonAccordion">
                <?php if(!empty($lessons_display)): foreach($lessons_display as $l_id => $lesson): ?>
                    
                    <div class="accordion-item border-0 mb-3 bg-white shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-transparent fw-bold py-3 px-4 text-dark shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#lesson-<?= $l_id ?>">
                                <div class="d-flex w-100 align-items-center">
                                    <div class="bg-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; border-radius: 10px;">
                                        <i class="fa-solid fa-book text-opacity-75"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fs-6 fw-bold text-dark mb-1">Bài học: <?= htmlspecialchars($lesson['lesson_title']) ?></div>
                                        <div class="text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">
                                            <i class="fa-solid fa-cube me-1 opacity-50"></i> Khóa học: <?= htmlspecialchars($lesson['course_title']) ?>
                                        </div>
                                    </div>
                                    <div class="ms-3 me-2">
                                        <span class="badge bg-soft-primary text-primary border border-primary border-opacity-10 px-3 py-2" style="border-radius: 8px;">
                                            <?= count($lesson['questions']) ?> câu
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        
                        <div id="lesson-<?= $l_id ?>" class="accordion-collapse collapse">
                            <div class="accordion-body p-3 bg-white border-top border-light">
                                <div class="row g-2">
                                    <?php foreach($lesson['questions'] as $q): ?>
                                        <div class="col-12">
                                            <div class="card border border-light shadow-none rounded-3 question-card transition-all">
                                                <div class="card-header bg-transparent border-0 py-3 px-3 d-flex align-items-center" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#q-ans-<?= $q['id'] ?>">
                                                    <div class="badge bg-light text-secondary border me-3 px-2 py-1">#<?= $q['id'] ?></div>
                                                    <div class="fw-bold text-dark flex-grow-1" style="font-size: 0.95rem; line-height: 1.4;"><?= htmlspecialchars($q['question']) ?></div>
                                                    <i class="fa-solid fa-angle-down text-muted small ms-3 transition-icon"></i>
                                                </div>
                                                
                                                <div id="q-ans-<?= $q['id'] ?>" class="collapse">
                                                    <div class="card-body pt-0 px-4 pb-3">
                                                        <div class="row g-2 mt-1">
                                                            <?php foreach(['A', 'B', 'C', 'D'] as $opt): 
                                                                $is_correct = (strtoupper($opt) == strtoupper($q['correct_answer']));
                                                            ?>
                                                                <div class="col-md-6">
                                                                    <div class="p-2 px-3 border rounded-3 small d-flex align-items-center <?= $is_correct ? 'bg-success bg-opacity-10 border-success text-success fw-bold' : 'bg-light border-transparent text-muted' ?>">
                                                                        <span class="me-2 text-uppercase fw-bold opacity-75"><?= $opt ?>.</span>
                                                                        <span class="flex-grow-1"><?= htmlspecialchars($q['option_'.strtolower($opt)]) ?></span>
                                                                        <?php if($is_correct): ?><i class="fa-solid fa-circle-check ms-auto"></i><?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="mt-3 pt-2 text-end d-flex justify-content-end gap-3 border-top border-light">
                                                            <button class="btn btn-sm text-primary p-0 text-decoration-none fw-bold" onclick='openModalEdit(<?= json_encode($q, JSON_HEX_TAG) ?>)'>
                                                                <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                                                            </button>
                                                            <a href="index.php?page=admin_quiz&delete=<?= $q['id'] ?>" class="btn btn-sm text-danger p-0 text-decoration-none fw-bold" onclick="return confirm('Xóa câu hỏi này?')">
                                                                <i class="fa-solid fa-trash-can me-1"></i> Xóa
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <div class="text-center p-4 rounded-4 bg-white mt-2 border border-dashed border-2">
                        <i class="fa-solid fa-folder-open fs-2 text-muted opacity-50 mb-2"></i>
                        <h6 class="fw-bold text-dark">Chưa có dữ liệu</h6>
                        <p class="small text-muted">Không tìm thấy câu hỏi phù hợp với bộ lọc hiện tại.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="index.php?page=admin_quiz" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-header border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bolder text-dark mb-0"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Cập nhật câu hỏi</h5>
                <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-2">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="fw-bold small text-muted mb-2">Thuộc Bài học <span class="text-danger">*</span></label>
                        <select name="lesson_id" id="edit_lesson" class="form-select border-light shadow-none bg-light py-2 px-3 input-modern" required>
                            <?php foreach($all_lessons as $l): ?>
                                <option value="<?= $l['id'] ?>">Bài <?= $l['id'] ?>: <?= htmlspecialchars($l['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-12">
                        <label class="fw-bold small text-muted mb-2">Nội dung câu hỏi <span class="text-danger">*</span></label>
                        <textarea name="question" id="edit_question" class="form-control border-light shadow-none bg-light py-2 px-3 input-modern" rows="2" required></textarea>
                    </div>

                    <div class="col-12 mt-2">
                        <label class="fw-bold small text-muted mb-3"><i class="fa-solid fa-circle-check text-success me-1"></i> Tích chọn nút tròn bên cạnh để đánh dấu đáp án ĐÚNG</label>
                        <div class="row g-2">
                            <?php foreach(['A', 'B', 'C', 'D'] as $opt): ?>
                            <div class="col-md-6">
                                <div class="input-group border border-light bg-white rounded-3 p-1 answer-group shadow-sm">
                                    <span class="input-group-text bg-transparent border-0 pe-1">
                                        <input type="radio" class="form-check-input mt-0 answer-radio" name="is_correct" id="edit_correct_<?= strtoupper($opt) ?>" value="<?= strtoupper($opt) ?>" required>
                                    </span>
                                    <span class="input-group-text bg-transparent border-0 fw-bold text-muted ps-1"><?= strtoupper($opt) ?>.</span>
                                    <input type="text" name="option_<?= strtolower($opt) ?>" id="edit_option_<?= strtolower($opt) ?>" class="form-control border-0 bg-transparent shadow-none px-2" required>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-light fw-bold py-2 px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Hủy bỏ</button>
                <button type="submit" name="update_question" class="btn btn-primary fw-bold py-2 px-4 shadow-sm" style="border-radius: 10px; background: #4f46e5; border: none;">LƯU THAY ĐỔI</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="index.php?page=admin_quiz" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-bolder text-dark"><i class="fa-solid fa-file-excel text-success me-2"></i>Nhập câu hỏi từ Excel</h5>
                <button type="button" class="btn-close bg-light rounded-circle p-2" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="bg-light p-4 rounded-4 mb-3 text-center border-dashed">
                    <input type="file" name="excel_file" class="form-control border-0 bg-white shadow-sm" style="border-radius: 10px;" accept=".csv" required>
                    <p class="small text-muted mt-3 mb-0">Tải lên file <strong>.csv (UTF-8)</strong>.<br>Thứ tự cột: <code>Câu hỏi, A, B, C, D, Đáp án đúng (A/B/C/D), LessonID</code></p>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="submit" name="import_excel" class="btn btn-success w-100 fw-bold py-2 shadow-sm" style="border-radius: 12px;">BẮT ĐẦU TẢI LÊN</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* CSS GIAO DIỆN HIỆN ĐẠI */
    .bg-soft-primary { background-color: #eef2ff; }
    .border-dashed { border-style: dashed !important; border-color: #cbd5e1 !important; }
    
    .input-modern { border-radius: 10px; transition: 0.3s; border: 1px solid transparent !important; }
    .input-modern:focus { background-color: #fff !important; border-color: #4f46e5 !important; box-shadow: 0 0 0 4px rgba(79,70,229,0.1) !important; }

    .answer-group { transition: 0.2s; border: 1px solid transparent !important; }
    .answer-group:focus-within { border-color: #4f46e5 !important; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important; background: #fff !important;}
    .answer-group:has(.answer-radio:checked) { border-color: #10b981 !important; background-color: #f0fdf4 !important; }
    .answer-group:has(.answer-radio:checked) * { color: #047857 !important; font-weight: bold; }
    
    .accordion-button:not(.collapsed) { background-color: transparent; color: inherit; box-shadow: none; border-bottom: 1px solid #f1f5f9; }
    .accordion-button::after { display: none; }
    .question-card:hover { background-color: #f8fafc; border-color: #e2e8f0 !important; }
    
    .transition-all { transition: all 0.2s ease; }
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); opacity: 0.95; }
    
    .collapsed .transition-icon { transform: rotate(0deg); transition: 0.3s; }
    .transition-icon { transform: rotate(180deg); transition: 0.3s; color: #4f46e5 !important; }
    
    .mini-alert { animation: slideDown 0.4s ease; transition: opacity 0.5s; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
// Tự ẩn thông báo xanh lá sau 3s
setTimeout(() => {
    const alerts = document.querySelectorAll('.mini-alert');
    alerts.forEach(a => { a.style.opacity = "0"; setTimeout(() => a.remove(), 500); });
}, 3000);

// Hàm mở Modal Sửa và đổ dữ liệu vào Form popup
function openModalEdit(q) {
    document.getElementById('edit_id').value = q.id;
    document.getElementById('edit_lesson').value = q.lesson_id;
    document.getElementById('edit_question').value = q.question;
    document.getElementById('edit_option_a').value = q.option_a;
    document.getElementById('edit_option_b').value = q.option_b;
    document.getElementById('edit_option_c').value = q.option_c;
    document.getElementById('edit_option_d').value = q.option_d;
    
    // Đánh dấu radio đáp án đúng
    let rb = document.getElementById('edit_correct_' + q.correct_answer.toUpperCase());
    if(rb) rb.checked = true;
    
    // Hiển thị Modal popup
    var editModal = new bootstrap.Modal(document.getElementById('modalEdit'));
    editModal.show();
}
</script>