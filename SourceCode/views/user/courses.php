<style>
body {
    background: #f6f7fb;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #000;
}

/* =========================================
   TIÊU ĐỀ TRANG CÓ ICON GIỐNG ẢNH
========================================= */
.page-header-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 28px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 20px;
}

.title-icon {
    font-size: 32px;
    line-height: 1;
}

/* =========================================
   FILTER VÀ BỘ LỌC TÌM KIẾM
========================================= */
.filter-bar {
    background: #fff;
    padding: 12px 16px;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
    border: 1px solid #eee;
}

.filter-left, .filter-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-bar select {
    font-size: 16px; /* Đã tăng cỡ chữ lên 1 xíu */
    padding: 8px 14px;
    border-radius: 8px;
    border: 1px solid #ddd;
    color: #000;
    outline: none;
    min-width: 220px;
    cursor: pointer;
}

.chip {
    font-size: 16px; /* Đã tăng cỡ chữ lên 1 xíu */
    padding: 8px 18px;
    border-radius: 999px;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #000;
    background: #fff;
    transition: 0.2s;
}

.chip:hover, .chip.active {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}

/* =========================================
   THẺ KHÓA HỌC (GRID & CARD)
========================================= */
.course-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    transition: 0.2s;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.12);
}

.course-image {
    position: relative;
    height: 160px;
}

.course-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.badge {
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    color: #fff;
}

.free { background: #16a34a; }
.paid { background: #f59e0b; }

.course-body {
    padding: 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.course-category {
    font-size: 15px;
    font-weight: 600;
    color: #000;
    margin-bottom: 6px;
}

.course-title {
    font-size: 17px;
    font-weight: 700;
    color: #000;
    margin-bottom: 6px;
    line-height: 1.4;
}

.course-rating {
    font-size: 13px;
    color: #f59e0b;
    margin-bottom: 10px;
}

.course-rating span {
    color: #000;
}

.course-desc {
    font-size: 13px;
    color: #000;
    min-height: 40px;
    margin-bottom: 15px;
    line-height: 1.5;
}

.course-footer {
    margin-top: auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-view {
    display: block;
    text-align: center;
    padding: 10px;
    border-radius: 8px;
    background: #eff6ff; 
    color: #1d4ed8; 
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-view:hover { 
    background: #dbeafe; 
    transform: translateY(-2px);
}

.btn-buy {
    display: block;
    text-align: center;
    padding: 10px;
    border-radius: 8px;
    background: #f59e0b;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
    box-shadow: 0 4px 6px rgba(245, 158, 11, 0.2);
}

.btn-buy:hover { 
    background: #d97706; 
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(245, 158, 11, 0.3);
}

.btn-progress {
    display: block;
    text-align: center;
    padding: 10px;
    border-radius: 8px;
    background: #16a34a;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
    box-shadow: 0 4px 6px rgba(22, 163, 74, 0.2);
}

.btn-progress:hover { 
    background: #15803d; 
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(22, 163, 74, 0.3);
}

.empty {
    text-align: center;
    color: #000;
    padding: 20px;
}
</style>

<div class="container mt-4">

<?php 
$currentCat = $_GET['category'] ?? null;
$currentType = $_GET['type'] ?? null;
?>

<div class="page-header-title">
    <span class="title-icon">📚</span> Khám phá khóa học
</div>

<div class="filter-bar">
    
    <div class="filter-left">
        <select onchange="location=this.value">
            <option value="index.php?page=courses<?= $currentType ? '&type='.$currentType : '' ?>">
                Tất cả danh mục
            </option>

            <?php foreach($categories as $cat): ?>
                <option value="index.php?page=courses&category=<?= $cat['id'] ?><?= $currentType ? '&type='.$currentType : '' ?>"
                    <?= $currentCat == $cat['id'] ? 'selected' : '' ?>>
                    <?= $cat['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-right">
        <a class="chip <?= !$currentType ? 'active' : '' ?>"
           href="index.php?page=courses<?= $currentCat ? '&category='.$currentCat : '' ?>">
            Tất cả
        </a>

        <a class="chip <?= $currentType=='free' ? 'active' : '' ?>"
           href="index.php?page=courses<?= $currentCat ? '&category='.$currentCat : '' ?>&type=free">
            Miễn phí
        </a>

        <a class="chip <?= $currentType=='paid' ? 'active' : '' ?>"
           href="index.php?page=courses<?= $currentCat ? '&category='.$currentCat : '' ?>&type=paid">
            Trả phí
        </a>
    </div>

</div>

<div class="row g-3">

<?php if(!empty($courses)): ?>
<?php foreach($courses as $c): ?>

    <?php
        if($currentType == 'free' && $c['price'] > 0) continue;
        if($currentType == 'paid' && $c['price'] == 0) continue;
    ?>

    <div class="col-lg-3 col-md-4 col-sm-6">

        <div class="course-card">

            <div class="course-image">
                <img src="<?= htmlspecialchars($c['image']) ?>" alt="<?= htmlspecialchars($c['title']) ?>">

                <?php if($c['price'] == 0): ?>
                    <span class="badge free">Miễn phí</span>
                <?php else: ?>
                    <span class="badge paid">Trả phí</span>
                <?php endif; ?>
            </div>

            <div class="course-body">

                <div class="course-category">
                    <?= $c['category_name'] ?? 'Chưa phân loại' ?>
                </div>

                <div class="course-title">
                    <?= htmlspecialchars($c['title']) ?>
                </div>

                <div class="course-rating">
                    ★★★★★ <span>4.8</span>
                </div>

                <div class="course-desc">
                    <?= mb_substr(strip_tags($c['description']), 0, 80) ?>...
                </div>

                <div class="course-footer">

                    <a href="index.php?page=lessons&course_id=<?= $c['id'] ?>" class="btn-view">
                        Xem khóa học
                    </a>

                    <?php if($c['price'] > 0): ?>
                        <a href="index.php?page=add_cart&id=<?= $c['id'] ?>" class="btn-buy">
                            🛒 Thêm vào giỏ hàng
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=progress_add&id=<?= $c['id'] ?>" class="btn-progress">
                            📈 Thêm vào tiến độ
                        </a>
                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

<?php endforeach; ?>
<?php else: ?>
    <div class="col-12">
        <div class="empty">Không tìm thấy khóa học nào phù hợp.</div>
    </div>
<?php endif; ?>

</div>

</div>