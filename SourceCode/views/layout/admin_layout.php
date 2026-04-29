<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$current_page = $_GET['page'] ?? 'admin';
$keyword = $_GET['keyword'] ?? '';
$page_titles = [
    'admin' => 'Dashboard',
    'admin_categories' => 'Danh mục',
    'admin_courses' => 'Khóa học',
    'admin_lessons' => 'Bài học',
    'admin_quizzes' => 'Bài kiểm tra',
    'admin_users' => 'Người dùng',
    'admin_orders' => 'Đơn hàng'
];
$current_title = $page_titles[$current_page] ?? 'Quản lý';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $current_title ?> | Quản trị E-Learning</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --sidebar-w: 260px;
            --header-h: 70px;
            --primary: #4f46e5;
            --sidebar-dark: #1e1e2f;
            --bg-body: #f8fafc;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body { font-family: 'Inter', sans-serif; background: var(--bg-body); color: #1e293b; margin: 0; overflow-x: hidden; }
        .admin-wrapper { display: flex; min-height: 100vh; }

        /* --- SIDEBAR --- */
        .sidebar { width: var(--sidebar-w); background: var(--sidebar-dark); color: #fff; position: fixed; height: 100vh; padding: 25px 15px; z-index: 1050; transition: var(--transition); }
        .sidebar-brand { font-weight: 800; font-size: 22px; margin-bottom: 35px; padding-left: 15px; display: flex; align-items: center; gap: 12px; color: #fff; text-decoration: none; }
        .sidebar-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #6e6e8a; margin: 25px 0 10px 15px; font-weight: 700; }
        .sidebar a { display: flex; align-items: center; gap: 12px; color: #a1a1b5; text-decoration: none; padding: 12px 18px; border-radius: 12px; margin-bottom: 6px; transition: var(--transition); }
        .sidebar a:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .sidebar a.active { background: var(--primary); color: #fff; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3); }

        /* --- MAIN PANEL --- */
        .main-content { flex-grow: 1; margin-left: var(--sidebar-w); display: flex; flex-direction: column; min-width: 0; }

        /* --- HEADER --- */
        .header { height: var(--header-h); background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; position: sticky; top: 0; z-index: 1000; }
        .search-box { background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; padding: 8px 16px; width: 100%; max-width: 350px; border: 1px solid transparent; transition: var(--transition); }
        .search-box:focus-within { background: #fff; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
        .search-box input { border: none; background: transparent; outline: none; width: 100%; font-size: 14px; margin-left: 10px; }

        /* --- USER NAV --- */
        .user-nav { display: flex; align-items: center; gap: 20px; }
        .user-profile { display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 5px 10px; border-radius: 12px; transition: 0.2s; position: relative; }
        .user-profile:hover { background: #f8fafc; }
        .avatar { width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, #4f46e5, #9333ea); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; }

        /* --- DROPDOWN --- */
        .dropdown-custom { position: absolute; top: 110%; right: 0; width: 200px; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); display: none; border: 1px solid #f1f5f9; z-index: 1100; }
        .dropdown-custom.show { display: block; animation: slideUp 0.2s ease; }
        .dropdown-custom a { display: flex; align-items: center; gap: 10px; padding: 10px 15px; color: #475569; text-decoration: none; font-size: 14px; }
        .dropdown-custom a:hover { background: #f8fafc; color: var(--primary); }

        @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .page-body { padding: 30px; flex-grow: 1; }
        @media (max-width: 992px) { .sidebar { transform: translateX(-100%); } .main-content { margin-left: 0; } .sidebar.show { transform: translateX(0); } }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar" id="adminSidebar">
        <a href="index.php?page=admin" class="sidebar-brand">
            <i class="fa-solid fa-graduation-cap"></i> <span>E-Learning</span>
        </a>

        <div class="sidebar-label">Điều hành</div>
        <a href="index.php?page=admin" class="<?= $current_page == 'admin' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-pie"></i> Dashboard
        </a>

        <div class="sidebar-label">Nội dung</div>
        <a href="index.php?page=admin_categories" class="<?= $current_page == 'admin_categories' ? 'active' : '' ?>">
            <i class="fa-solid fa-folder-tree"></i> Danh mục
        </a>
        <a href="index.php?page=admin_courses" class="<?= $current_page == 'admin_courses' ? 'active' : '' ?>">
            <i class="fa-solid fa-book"></i> Khóa học
        </a>
        <a href="index.php?page=admin_lessons" class="<?= $current_page == 'admin_lessons' ? 'active' : '' ?>">
            <i class="fa-solid fa-play-circle"></i> Bài học
        </a>
        <a href="index.php?page=admin_quiz" class="<?= $current_page == 'admin_quiz' ? 'active' : '' ?>">
            <i class="fa-solid fa-clipboard-check"></i> Ngân hàng Quiz
        </a>

        <div class="sidebar-label">Hệ thống</div>
        <a href="index.php?page=admin_users" class="<?= $current_page == 'admin_users' ? 'active' : '' ?>">
            <i class="fa-solid fa-users-gear"></i> Người dùng
        </a>
        <a href="index.php?page=admin_orders" class="<?= $current_page == 'admin_orders' ? 'active' : '' ?>">
            <i class="fa-solid fa-file-invoice-dollar"></i> Đơn hàng
        </a>
    </aside>

    <div class="main-content">
        <header class="header">
            <div class="d-flex align-items-center gap-3">
                <form action="index.php" method="GET" class="search-box">
                    <input type="hidden" name="page" value="<?= htmlspecialchars($current_page) ?>">
                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    <input type="text" name="keyword" placeholder="Tìm kiếm trong <?= $current_title ?>..." value="<?= htmlspecialchars($keyword) ?>">
                </form>
            </div>

            <div class="user-nav">
                <div class="icon-btn text-muted position-relative" style="cursor:pointer">
                    <i class="fa-regular fa-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </div>

                <div class="user-profile" id="userTrigger">
                    <div class="avatar"><?= strtoupper(substr($_SESSION['user']['name'] ?? 'A', 0, 1)) ?></div>
                    <div class="d-none d-md-block">
                        <div class="fw-bold small"><?= $_SESSION['user']['name'] ?? 'Quản trị viên' ?></div>
                        <div class="text-muted small" style="font-size: 11px;">Administrator</div>
                    </div>
                    <i class="fa-solid fa-chevron-down text-muted ms-1" style="font-size: 10px;"></i>
                    
                    <div class="dropdown-custom" id="userMenu">
                        <a href="#"><i class="fa-regular fa-user-circle"></i> Hồ sơ cá nhân</a>
                        <a href="index.php?page=home" target="_blank"><i class="fa-solid fa-eye"></i> Xem trang chủ</a>
                        <div class="border-top my-2"></div>
                        <a href="index.php?page=logout" class="text-danger fw-bold"><i class="fa-solid fa-power-off"></i> Đăng xuất</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="page-body">
            <?php 
                if(isset($view) && file_exists($view)){
                    include $view;
                } else {
                    echo "<div class='alert alert-info border-0 shadow-sm'>Vui lòng chọn chức năng quản lý bên trái.</div>";
                }
            ?>
        </main>
    </div>
</div>

<script>
    const trigger = document.getElementById('userTrigger');
    const menu = document.getElementById('userMenu');
    trigger.addEventListener('click', (e) => { e.stopPropagation(); menu.classList.toggle('show'); });
    document.addEventListener('click', () => { menu.classList.remove('show'); });
    menu.addEventListener('click', (e) => e.stopPropagation());
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>