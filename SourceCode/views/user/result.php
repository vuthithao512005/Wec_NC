<div class="container mt-5">

    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">🎯 Kết quả bài kiểm tra</h2>

        <h4>
            Bạn đúng 
            <span class="badge bg-success fs-5"><?= $data['score'] ?></span> / 
            <span class="badge bg-dark fs-5"><?= $data['total'] ?></span> câu
        </h4>

        <?php 
            $percent = ($data['total'] > 0) ? round($data['score'] / $data['total'] * 100) : 0;
        ?>

        <div class="progress mt-3" style="height: 20px; border-radius: 10px;">
            <div class="progress-bar bg-success progress-bar-striped" 
                 style="width: <?= $percent ?>%">
                <?= $percent ?>%
            </div>
        </div>

        <div class="mt-3">
            <?php if($percent >= 80): ?>
                <span class="badge bg-success">🏆 Giỏi</span>
            <?php elseif($percent >= 50): ?>
                <span class="badge bg-warning text-dark">👍 Khá</span>
            <?php else: ?>
                <span class="badge bg-danger">📉 Cần cố gắng</span>
            <?php endif; ?>
        </div>
    </div>

    <?php foreach($data['details'] as $index => $q): ?>

        <div class="card mb-4 shadow-sm border-0" style="border-radius: 10px;">

            <div class="card-body">

                <h5 class="fw-bold mb-3">
                    Câu <?= $index + 1 ?>: 
                    <?= htmlspecialchars($q['question']) ?>
                </h5>

                <?php foreach($q['options'] as $key => $opt): ?>

                    <?php
                        $class = "border p-2 rounded mb-2";

                        // CHUẨN HÓA ĐỂ SO SÁNH
                        $curr = strtolower(trim($key));
                        $corr = strtolower(trim($q['correct']));
                        $user = strtolower(trim($q['user']));

                        // Đánh dấu màu xanh cho đáp án ĐÚNG
                        if($curr == $corr){
                            $class .= " bg-success text-white fw-bold";
                        }

                        // Đánh dấu màu đỏ cho câu User CHỌN SAI
                        if($curr == $user && !$q['isCorrect']){
                            $class .= " bg-danger text-white fw-bold";
                        }
                    ?>

                    <div class="<?= $class ?>">
                        <strong><?= strtoupper($key) ?>.</strong>
                        <?= htmlspecialchars($opt) ?>
                    </div>

                <?php endforeach; ?>

                <div class="mt-3 text-start">
                    <?php if($q['isCorrect']): ?>
                        <span class="badge bg-success px-3 py-2">✔ Đúng</span>
                    <?php else: ?>
                        <span class="badge bg-danger px-3 py-2">
                            ✘ Sai - Đáp án đúng: <?= strtoupper($q['correct']) ?>
                        </span>
                    <?php endif; ?>
                </div>

            </div>

        </div>

    <?php endforeach; ?>

    <div class="text-center mt-4 mb-5">
        <a href="index.php?page=quiz&lesson_id=<?= $data['lesson_id'] ?>" 
           class="btn btn-warning px-5 py-2 me-2 rounded-pill fw-bold">
            🔁 Làm lại bài
        </a>

        <a href="index.php?page=lesson&id=<?= $data['lesson_id'] ?>" class="btn btn-primary px-5 py-2 rounded-pill fw-bold">
            ← Quay lại khóa học
        </a>
    </div>

</div>

<style>
    .card { transition: 0.3s; }
    .card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>