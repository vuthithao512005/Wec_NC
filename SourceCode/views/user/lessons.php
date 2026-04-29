<div class="container mt-4">
    
    <!-- TITLE -->
    <h2 class="mb-3">📚 Danh sách bài học</h2>

    <!-- PROGRESS -->
    <?php if(isset($percent)): ?>
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
    <?php endif; ?>

    <!-- LIST LESSON -->
    <div class="row">

        <?php if(!empty($lessons)): ?>

            <?php foreach($lessons as $l): ?>

                <?php
                    $isDone = false;
                    if(isset($p)){
                        $isDone = $p->isCompleted($_SESSION['user']['id'], $l['id']);
                    }
                ?>

                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm h-100 p-3">

                        <!-- TITLE -->
                        <h5>
                            <?= $isDone ? '✔ ' : '' ?>
                            <?= htmlspecialchars($l['title']) ?>
                        </h5>

                        <!-- DESC -->
                        <p class="text-muted">
                            <?= htmlspecialchars(substr($l['content'] ?? '', 0, 80)) ?>...
                        </p>

                        <!-- ACTION -->
                        <a href="index.php?page=lesson&id=<?= $l['id'] ?>" 
                           class="btn btn-primary mt-auto">
                           ▶ Học bài
                        </a>

                    </div>
                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p class="text-danger">Không có bài học nào</p>

        <?php endif; ?>

    </div>

</div>