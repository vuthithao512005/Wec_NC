<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">📝 Bài kiểm tra kiến thức</h3>
        <span class="badge bg-info text-dark">Lưu ý: Chọn đầy đủ các câu hỏi</span>
    </div>

    <form method="POST">
        <?php foreach($quizzes as $index => $q): ?>
        <div class="card mb-4 shadow-sm border-0" style="border-radius: 15px; overflow: hidden; border-left: 5px solid #0d6efd !important;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-dark">
                    Câu <?= $index + 1 ?>: <?= htmlspecialchars($q['question']) ?>
                </h5>

                <div class="options-group">
                    <?php foreach(['a','b','c','d'] as $opt): 
                        $optionId = "q_" . $q['id'] . "_" . $opt; 
                    ?>
                        <div class="form-check custom-option mb-2">
                            <input class="form-check-input" 
                                type="radio" 
                                name="q[<?= $q['id'] ?>]" 
                                id="<?= $optionId ?>"
                                value="<?= $opt ?>" required>

                            <label class="form-check-label w-100 ps-2 py-1 cursor-pointer" for="<?= $optionId ?>">
                                <span class="fw-bold text-uppercase"><?= $opt ?>.</span> 
                                <?= htmlspecialchars($q['option_'.$opt]) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="text-center mt-5 mb-5">
            <button type="submit" class="btn btn-primary btn-lg shadow px-5 py-3" style="border-radius: 50px; font-weight: 600;">
                <i class="fa-solid fa-cloud-arrow-up me-2"></i> Hoàn thành & Nộp bài
            </button>
        </div>
    </form>
</div>

<style>
    .custom-option {
        border: 1px solid #eee;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .custom-option:hover {
        background-color: #f8faff;
        border-color: #0d6efd;
    }
    .cursor-pointer { cursor: pointer; }
    .form-check-input:checked + .form-check-label {
        color: #0d6efd;
        font-weight: 600;
    }
</style>