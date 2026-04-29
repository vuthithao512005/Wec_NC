<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$current = $_GET['page'] ?? 'courses';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?= $title ?? 'E-Learning' ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/E-learning/assets/user.css">

<style>
.nav-link {
    font-weight: 500;
    color: #333 !important;
    /* Tăng khoảng cách bên trong chữ để nút to và thoáng hơn */
    padding: 8px 18px !important; 
}

.nav-link.active {
    color: #0d6efd !important;
    border-bottom: 2px solid #0d6efd;
}

/* =========================================
   CSS THÊM MỚI CHO TÌM KIẾM TRỰC TIẾP
========================================= */
.search-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: #fff;
    max-height: 400px;
    overflow-y: auto;
    z-index: 1050;
    margin-top: 10px;
    border: 1px solid #e2e8f0;
}
.search-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    text-decoration: none;
    color: #0f172a;
    border-bottom: 1px solid #f8fafc;
    transition: 0.2s;
}
.search-item:hover {
    background: #f1f5f9;
}
.search-item img {
    width: 45px;
    height: 45px;
    border-radius: 8px;
    object-fit: cover;
    margin-right: 12px;
}
.search-item-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 4px;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.search-item-price {
    font-size: 13px;
    color: #ef4444;
    font-weight: bold;
}
</style>

</head>

<body>
<?php if (isset($_SESSION['toast_msg'])): ?>
    <div class="position-fixed top-0 end-0 p-4" style="z-index: 9999">
        <div id="liveToast" class="toast align-items-center text-white bg-<?= $_SESSION['toast_type'] ?> border-0 show shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex p-1">
                <div class="toast-body fw-bold" style="font-size: 15px;">
                    <?= $_SESSION['toast_type'] == 'success' ? '✅ ' : '❌ ' ?>
                    <?= $_SESSION['toast_msg'] ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    
    <script>
        setTimeout(function() {
            var toastElement = document.getElementById('liveToast');
            if(toastElement) {
                toastElement.classList.remove('show');
                toastElement.style.display = 'none';
            }
        }, 4000);
    </script>
    
    <?php 
        unset($_SESSION['toast_msg']); 
        unset($_SESSION['toast_type']);
    ?>
<?php endif; ?>
<nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 py-3">


<a class="navbar-brand fw-bold me-5" href="index.php?page=home">
    🎓 E-Learning
</a>

<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="menu">

    <ul class="navbar-nav me-auto gap-3">

        <?php if(isset($_SESSION['user'])): ?>

            <li class="nav-item">
                <a class="nav-link <?= $current == 'home' ? 'active' : '' ?>" href="index.php?page=home">
                    🏠 Trang chủ
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $current == 'courses' ? 'active' : '' ?>" href="index.php?page=courses">
                    📚 Khóa học
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $current == 'progress' ? 'active' : '' ?>" href="index.php?page=progress">
                    📊 Tiến độ
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $current == 'cart' ? 'active' : '' ?>" href="index.php?page=cart">
                    🛒 Giỏ hàng
                </a>
            </li>

        <?php endif; ?>

    </ul>

    <form class="d-flex mx-lg-4 my-3 my-lg-0 position-relative" onsubmit="event.preventDefault();">
        <input class="form-control me-2" type="search" id="live-search-input" placeholder="Tìm khóa học..." style="min-width: 250px;" autocomplete="off">
        <button class="btn btn-outline-primary px-3" type="button">🔍</button>

        <div id="search-results-box" class="search-dropdown shadow-lg rounded-3 d-none">
            </div>
    </form>

    <ul class="navbar-nav gap-3 align-items-center">

        <?php if(isset($_SESSION['user'])): ?>

            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">

                    <img 
                        src="https://i.pravatar.cc/40?u=<?= urlencode($_SESSION['user']['name']) ?>" 
                        style="width:35px;height:35px;border-radius:50%;margin-right:8px;"
                        onerror="this.src='https://via.placeholder.com/40'"
                    >

                    <?= htmlspecialchars($_SESSION['user']['name']) ?>
                </a>

                <ul class="dropdown-menu dropdown-menu-end mt-2">

                    <li>
                        <a class="dropdown-item py-2" href="index.php?page=progress">
                            📊 Tiến độ
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item text-danger py-2" href="index.php?page=logout">
                            🚪 Đăng xuất
                        </a>
                    </li>

                </ul>

            </li>

        <?php else: ?>

            <li class="nav-item">
                <a class="nav-link" href="index.php?page=login">Đăng nhập</a>
            </li>

            <li class="nav-item">
                <a class="btn btn-primary px-4" href="index.php?page=register">
                    Đăng ký
                </a>
            </li>

        <?php endif; ?>

    </ul>

</div>

</nav>

<div class="container mt-4">

<?php
if(isset($view)){
    include $view;
}
?>

</div>

<footer class="text-center mt-5 mb-3 text-muted small">
    © <?= date('Y') ?> E-Learning. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const searchInput = document.getElementById('live-search-input');
    const resultsBox = document.getElementById('search-results-box');

    if (searchInput) {
        // Sự kiện khi người dùng gõ chữ
        searchInput.addEventListener('input', function() {
            let keyword = this.value.trim();
            
            if(keyword.length > 0) {
                // Gọi API bằng Fetch
                fetch(`index.php?page=api_search&keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.json())
                .then(data => {
                    resultsBox.innerHTML = ''; // Xóa kết quả cũ
                    
                    if(data.length > 0) {
                        data.forEach(course => {
                            let priceText = course.price == 0 ? '<span class="text-success">Miễn phí</span>' : new Intl.NumberFormat('vi-VN').format(course.price) + ' đ';
                            let imgUrl = course.image ? course.image : 'https://via.placeholder.com/45'; // Ảnh mặc định nếu lỗi
                            
                            resultsBox.innerHTML += `
                                <a href="index.php?page=lessons&course_id=${course.id}" class="search-item">
                                    <img src="${imgUrl}" alt="">
                                    <div>
                                        <div class="search-item-title">${course.title}</div>
                                        <div class="search-item-price">${priceText}</div>
                                    </div>
                                </a>
                            `;
                        });
                        resultsBox.classList.remove('d-none'); // Hiện box kết quả
                    } else {
                        resultsBox.innerHTML = '<div class="p-3 text-muted small text-center">Không tìm thấy khóa học nào phù hợp.</div>';
                        resultsBox.classList.remove('d-none');
                    }
                })
                .catch(error => console.error('Lỗi search:', error));
            } else {
                resultsBox.classList.add('d-none'); // Ẩn box nếu ô search trống
            }
        });

        // Ẩn box tìm kiếm khi click chuột ra ngoài
        document.addEventListener('click', function(event) {
            if(!searchInput.contains(event.target) && !resultsBox.contains(event.target)) {
                resultsBox.classList.add('d-none');
            }
        });
    }
</script>

</body>
</html>