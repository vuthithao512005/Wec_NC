<div class="container mt-4 mb-5">

    <div class="hero-modern mb-5 mt-2">
        <div class="row align-items-center">
            
            <div class="col-lg-6 pe-lg-5 mb-5 mb-lg-0">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-primary bg-opacity-10 text-primary fw-bold mb-4" style="font-size: 13px;">
                     Nền tảng E-Learning hàng đầu
                </div>
                
                <h1 class="hero-title-modern">
                    Mở khóa <span class="text-highlight">Tiềm năng</span> <br> của bạn ngay hôm nay
                </h1>
                
                <p class="hero-desc-modern">
                    Học lập trình thực chiến từ cơ bản đến nâng cao. Tự tay xây dựng các dự án thực tế và tự tin bước vào môi trường làm việc chuyên nghiệp.
                </p>
                
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="index.php?page=courses" class="btn-primary-modern">
                        Khám phá khóa học <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                    <a href="index.php?page=courses&type=free" class="btn-outline-modern">
                        ▶ Học thử miễn phí
                    </a>
                </div>
                
                <div class="d-flex align-items-center gap-4 mt-5 pt-3 border-top border-light">
                    <div><h4 class="fw-bold text-dark mb-0">100+</h4><small class="text-muted">Khóa học</small></div>
                    <div><h4 class="fw-bold text-dark mb-0">5K+</h4><small class="text-muted">Học viên</small></div>
                    <div><h4 class="fw-bold text-dark mb-0">4.8/5</h4><small class="text-muted">Đánh giá</small></div>
                </div>
            </div>

            <div class="col-lg-6 position-relative d-none d-lg-block">
                <div class="hero-blob"></div>
                
                <div class="position-relative z-2 text-end">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="E-Learning" class="img-fluid rounded-4 hero-main-img">
                </div>

                <div class="floating-card z-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="fa-solid fa-graduation-cap fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark mb-0" style="font-size: 15px;">Đã tốt nghiệp</div>
                            <div class="text-muted" style="font-size: 12px;">+1,200 Học viên</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="d-flex justify-content-between align-items-end mb-4 mt-5">
        <div>
            <div class="page-header-title mb-1">
                <span class="title-icon">⚡</span> Khóa học mới nhất
            </div>
            <p class="text-muted mb-0 small">Những nội dung vừa được cập nhật trên hệ thống</p>
        </div>
        <a href="index.php?page=courses" class="text-dark fw-bold text-decoration-none" style="font-size: 14px;">
            Xem tất cả
        </a>
    </div>

    <div class="row g-3 mb-5">
        <?php foreach($latest as $c): ?>
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
                        <?= $c['category_name'] ?? 'Lập trình' ?>
                    </div>
                    <div class="course-title">
                        <?= htmlspecialchars($c['title']) ?>
                    </div>
                    <div class="course-rating">
                        ★★★★★ <span>5.0</span>
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
                                🛒 Thêm vào giỏ
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
    </div>


    <div class="d-flex justify-content-between align-items-end mb-4 mt-5">
        <div>
            <div class="page-header-title mb-1">
                <span class="title-icon">🎁</span> Khóa học Miễn phí
            </div>
            <p class="text-muted mb-0 small">Nền tảng vững chắc, hoàn toàn không mất phí</p>
        </div>
        <a href="index.php?page=courses&type=free" class="text-dark fw-bold text-decoration-none" style="font-size: 14px;">
            Xem tất cả
        </a>
    </div>

    <div class="row g-3 mb-5">
        <?php foreach($free as $c): ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="course-card">
                <div class="course-image">
                    <img src="<?= htmlspecialchars($c['image']) ?>" alt="<?= htmlspecialchars($c['title']) ?>">
                    <span class="badge free">Miễn phí</span>
                </div>

                <div class="course-body">
                    <div class="course-category">
                        <?= $c['category_name'] ?? 'Cơ bản' ?>
                    </div>
                    <div class="course-title">
                        <?= htmlspecialchars($c['title']) ?>
                    </div>
                    <div class="course-rating">
                        ★★★★★ <span>4.9</span>
                    </div>
                    <div class="course-desc">
                        <?= mb_substr(strip_tags($c['description']), 0, 80) ?>...
                    </div>

                    <div class="course-footer">
                        <a href="index.php?page=lessons&course_id=<?= $c['id'] ?>" class="btn-view">
                            Xem khóa học
                        </a>
                        <a href="index.php?page=progress_add&id=<?= $c['id'] ?>" class="btn-progress">
                            📈 Thêm vào tiến độ
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>


    <div class="d-flex justify-content-between align-items-end mb-4 mt-5">
        <div>
            <div class="page-header-title mb-1">
                <span class="title-icon">💎</span> Khóa học Chuyên sâu
            </div>
            <p class="text-muted mb-0 small">Nâng tầm kỹ năng với các dự án thực tế cao cấp</p>
        </div>
        <a href="index.php?page=courses&type=paid" class="text-dark fw-bold text-decoration-none" style="font-size: 14px;">
            Xem tất cả
        </a>
    </div>

    <div class="row g-3 mb-5">
        <?php foreach($paid as $c): ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="course-card">
                <div class="course-image">
                    <img src="<?= htmlspecialchars($c['image']) ?>" alt="<?= htmlspecialchars($c['title']) ?>">
                    <span class="badge paid">Trả phí</span>
                </div>

                <div class="course-body">
                    <div class="course-category">
                        <?= $c['category_name'] ?? 'Nâng cao' ?>
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
                        <a href="index.php?page=add_cart&id=<?= $c['id'] ?>" class="btn-buy">
                            🛒 Thêm vào giỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<style>
    body {
        background: #f6f7fb;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* TIÊU ĐỀ SECTION */
    .page-header-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
    }
    .title-icon { font-size: 28px; line-height: 1; }

    /* =========================================
       HERO HIỆN ĐẠI (2 CỘT)
    ========================================= */
    .hero-modern { padding: 20px 0 40px 0; }
    
    .hero-title-modern {
        font-size: 46px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.25;
        letter-spacing: -1px;
        margin-bottom: 20px;
    }

    .text-highlight {
        color: #2563eb;
        position: relative;
    }

    .text-highlight::after {
        content: "";
        position: absolute;
        bottom: 2px;
        left: 0;
        width: 100%;
        height: 8px;
        background: #bfdbfe;
        z-index: -1;
        border-radius: 4px;
    }

    .hero-desc-modern {
        font-size: 17px;
        color: #475569;
        line-height: 1.6;
        max-width: 90%;
    }

    /* NÚT BẤM HERO */
    .btn-primary-modern {
        background: #2563eb;
        color: #fff;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
    }
    .btn-primary-modern:hover {
        background: #1d4ed8;
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(37, 99, 235, 0.35);
    }

    .btn-outline-modern {
        background: #fff;
        color: #0f172a;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid #cbd5e1;
        transition: 0.3s;
    }
    .btn-outline-modern:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    /* HÌNH ẢNH & HIỆU ỨNG HERO */
    .hero-main-img {
        border: 8px solid #ffffff;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        max-height: 400px;
        object-fit: cover;
    }

    .hero-blob {
        position: absolute;
        width: 300px;
        height: 300px;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        filter: blur(80px);
        opacity: 0.2;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    .floating-card {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 15px 20px;
        border-radius: 16px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        animation: float 4s ease-in-out infinite;
    }

    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
        100% { transform: translateY(0px); }
    }

    /* =========================================
       COURSE CARD (FORM CỦA BẠN)
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
        border: 1px solid #f1f3f5;
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
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 6px;
    }

    .course-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-rating {
        font-size: 13px;
        color: #f59e0b;
        margin-bottom: 10px;
    }

    .course-rating span {
        color: #64748b;
        margin-left: 4px;
    }

    .course-desc {
        font-size: 13px;
        color: #475569;
        min-height: 40px;
        margin-bottom: 15px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .course-footer {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .btn-view, .btn-buy, .btn-progress {
        display: block;
        text-align: center;
        padding: 10px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-view { background: #eff6ff; color: #1d4ed8; }
    .btn-view:hover { background: #dbeafe; color: #1e40af; }

    .btn-buy { background: #f59e0b; color: #fff; }
    .btn-buy:hover { background: #d97706; color: #fff; }

    .btn-progress { background: #16a34a; color: #fff; }
    .btn-progress:hover { background: #15803d; color: #fff; }
</style>