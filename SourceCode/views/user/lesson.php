<div class="container-fluid">
<div class="row">

    <!-- SIDEBAR -->
    <div class="col-md-3 bg-dark text-white p-3" style="min-height:100vh;">
        <h5 class="mb-3">📚 Nội dung khóa học</h5>

        <?php foreach($lessons as $l): ?>
            <?php
                $isDone = $p->isCompleted($_SESSION['user']['id'], $l['id']);
            ?>

            <a href="index.php?page=lesson&id=<?= $l['id'] ?>" 
               class="d-block p-2 mb-2 rounded text-decoration-none
               <?= ($lesson['id'] == $l['id']) ? 'bg-primary text-white' : 'text-light' ?>">

                <?= $isDone ? '✔ ' : '' ?>
                <?= htmlspecialchars($l['title']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- CONTENT -->
    <div class="col-md-9 p-4">

        <!-- PROGRESS -->
        <div class="mb-4">
            <label class="fw-bold">Tiến độ học:</label>

            <div class="progress">
                <div class="progress-bar bg-success" 
                     style="width: <?= $percent ?>%">
                    <?= $percent ?>%
                </div>
            </div>

            <small><?= $completed ?> / <?= $total ?> bài</small>
        </div>

        <?php if($lesson): ?>

            <!-- TITLE -->
            <h3 class="mb-3"><?= htmlspecialchars($lesson['title']) ?></h3>

            <!-- VIDEO -->
            <?php if(!empty($lesson['video'])): ?>
                <div class="ratio ratio-16x9 mb-4 shadow">
                    <iframe src="<?= htmlspecialchars($lesson['video']) ?>" allowfullscreen></iframe>
                </div>
            <?php endif; ?>

            <!-- CONTENT -->
            <div class="card p-3 mb-3 shadow-sm">
                <h5>Nội dung bài học</h5>
                <p><?= nl2br(htmlspecialchars($lesson['content'] ?? '')) ?></p>
            </div>

            <!-- ACTION -->
            <div class="d-flex gap-2">

                <!-- QUIZ -->
                <a href="index.php?page=quiz&lesson_id=<?= $lesson['id'] ?>" 
                   class="btn btn-success">
                   📝 Làm bài kiểm tra
                </a>

                <!-- NEXT -->
                <?php
                $currentIndex = array_search($lesson['id'], array_column($lessons, 'id'));
                $nextLesson = $lessons[$currentIndex + 1] ?? null;
                ?>

                <?php if($nextLesson): ?>
                    <a href="index.php?page=lesson&id=<?= $nextLesson['id'] ?>" 
                       class="btn btn-primary">
                       ▶ Bài tiếp theo
                    </a>
                <?php endif; ?>

            </div>

        <?php else: ?>
            <p class="text-danger">Không tìm thấy bài học</p>
        <?php endif; ?>

    </div>

</div>
</div>