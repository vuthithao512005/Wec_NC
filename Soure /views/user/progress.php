<style>
body {
    background: #f6f7fb;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #000;
}

/* TIÊU ĐỀ TRANG */
.page-header-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 28px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 25px;
}

.title-icon {
    font-size: 32px;
    line-height: 1;
}

/* THẺ TIẾN ĐỘ (NẰM NGANG) */
.progress-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    display: flex;
    align-items: center;
    gap: 25px;
    margin-bottom: 15px;
    border: 1px solid #eee;
    transition: all 0.2s ease;
}

.progress-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* Ảnh khóa học thu nhỏ */
.progress-image {
    width: 120px;
    height: 80px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
}

/* Phần thông tin */
.progress-info {
    flex: 1;
}

.progress-title {
    font-size: 18px;
    font-weight: 700;
    color: #000;
    margin-bottom: 12px;
}

/* Thanh Progress Bar custom */
.progress-bar-container {
    background: #e2e8f0;
    height: 8px;
    border-radius: 999px;
    overflow: hidden;
    margin-bottom: 8px;
    width: 100%;
}

.progress-bar-fill {
    background: #10b981; /* Màu xanh lá mượt mà */
    height: 100%;
    border-radius: 999px;
    transition: width 0.5s ease-in-out;
}

.progress-stats {
    font-size: 14px;
    color: #475569;
    display: flex;
    justify-content: space-between;
    font-weight: 500;
}

/* NÚT TIẾP TỤC HỌC */
.btn-continue {
    padding: 10px 24px;
    background: #2563eb;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: 0.2s;
    white-space: nowrap;
}

.btn-continue:hover {
    background: #1d4ed8;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
}

.empty-state {
    text-align: center;
    background: #fff;
    padding: 40px 20px;
    border-radius: 14px;
    border: 1px dashed #cbd5e1;
    color: #64748b;
    font-size: 16px;
}
</style>

<div class="container mt-4">

    <div class="page-header-title">
        <span class="title-icon">📊</span> Tiến độ học tập
    </div>

    <?php 
    $filteredProgress = [];
    
    if(!empty($progressList)) {
        foreach($progressList as $p) {
            // Kiểm tra các điều kiện để hiển thị khóa học
            $isAddedToProgress = isset($p['is_added']) && $p['is_added'] == true;
            $isPaid = isset($p['is_paid']) && $p['is_paid'] == true;

            if($isAddedToProgress || $isPaid) {
                $filteredProgress[] = $p;
            }
        }
    }
    ?>

    <div class="progress-list">
        <?php if(!empty($filteredProgress)): ?>
            
            <?php foreach($filteredProgress as $p): ?>
                <div class="progress-card">
                    
                    <img src="<?= htmlspecialchars($p['image'] ?? 'Uploads/default-course.jpg') ?>" class="progress-image" alt="Course">

                    <div class="progress-info">
                        <div class="progress-title"><?= htmlspecialchars($p['title']) ?></div>
                        
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" style="width: <?= (int)($p['percent'] ?? 0) ?>%"></div>
                        </div>

                        <div class="progress-stats">
                            <span>Đã học: <?= (int)($p['percent'] ?? 0) ?>%</span>
                            <span><?= (int)($p['completed'] ?? 0) ?> / <?= (int)($p['total'] ?? 0) ?> bài</span>
                        </div>
                    </div>

                    <a href="index.php?page=lessons&course_id=<?= $p['id'] ?>" class="btn-continue">
                        Tiếp tục học ➔
                    </a>

                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <div class="empty-state">
                <p>Bạn chưa tham gia khóa học nào.</p>
                <a href="index.php?page=courses" class="btn-continue mt-2" style="display: inline-block;">Khám phá khóa học ngay</a>
            </div>
        <?php endif; ?>
    </div>

</div>