<?php

require_once "config/database.php";
require_once "middleware.php";

// Lấy trang hiện tại, mặc định là 'home'
$page = $_GET['page'] ?? 'home';
// Nạp TẤT CẢ các Model ngay tại đây để không bao giờ bị lỗi "Class not found" nữa
require_once "models/Course.php";
require_once "models/User.php";
require_once "models/Lesson.php";
require_once "models/Order.php"; 
require_once "controllers/AuthController.php";
require_once "controllers/AdminController.php";
require_once "controllers/ResultController.php";
require_once "controllers/CourseController.php";
require_once "controllers/LessonController.php";
require_once "controllers/ProgressController.php";
require_once "controllers/CartController.php";
require_once "controllers/QuizController.php";
require_once 'controllers/AdminQuizController.php';
require_once "controllers/CategoryController.php";

switch($page){

    // ============================================================
    // AUTHENTICATION (XÁC THỰC)
    // ============================================================
    case 'login':
        (new AuthController($conn))->login();
    break;

    case 'register':
        (new AuthController($conn))->register();
    break;

    case 'logout':
        session_start();
        $_SESSION = [];
        session_destroy();
        if (ini_get("session.use_cookies")) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        header("Location: index.php?page=login");
        exit;
    break;


    // ============================================================
    // USER - KHÁCH HÀNG
    // ============================================================
    
    // Trang chủ
    case 'home':
        $c = new CourseController($conn);
        $data = $c->index(); 
        $courses = $data['courses'];
        $latest = array_slice($courses, 0, 6);
        $free = array_filter($courses, fn($x) => $x['price'] == 0);
        $paid = array_filter($courses, fn($x) => $x['price'] > 0);

        $view = "views/user/home.php";
        include "views/layout/user_layout.php";
    break;

    // Danh sách tất cả khóa học
    case 'courses':
        checkLogin();
        $c = new CourseController($conn);
        $data = $c->index();
        $courses = $data['courses'];
        $categories = $data['categories'];

        $view = "views/user/courses.php";
        include "views/layout/user_layout.php";
    break;


    // ============================================================
    // HỌC TẬP & TIẾN ĐỘ (LEARNING)
    // ============================================================
    
    // Danh sách bài học của 1 khóa
    case 'lessons':
        checkLogin();
        $l = new LessonController($conn);
        $lessons = $l->list($_GET['course_id'] ?? 0);

        $view = "views/user/lessons.php";
        include "views/layout/user_layout.php";
    break;

    // Chi tiết 1 bài học (Video học)
    case 'lesson':
        checkLogin();
        $l = new LessonController($conn);
        $p = new Progress($conn);
        $cModel = new Course($conn);

        $lesson = $l->get($_GET['id'] ?? 0);
        if(!$lesson) exit('Bài học không tồn tại');

        $course = $cModel->get($lesson['course_id']);
        if(!$course) exit('Khóa học không tồn tại');

        // Chặn nếu chưa mua (trừ khóa 0đ)
        if($course['price'] > 0){
             // Ở đây bạn có thể bổ sung check xem user đã mua chưa
             // exit('Bạn cần mua khóa học!');
        }

        $lessons = $l->list($lesson['course_id']);
        $user_id = $_SESSION['user']['id'];
        $completed = $p->countCompleted($user_id, $lesson['course_id']);
        $total = $p->totalLessons($lesson['course_id']);
        $percent = $total ? round(($completed/$total)*100) : 0;

        $view = "views/user/lesson.php";
        include "views/layout/user_layout.php";
    break;

    // Trang quản lý tiến độ học tập (Dashboard của User)
    case 'progress':
        checkLogin();
        $p = new ProgressController($conn);
        $progressList = $p->index();

        $view = "views/user/progress.php";
        include "views/layout/user_layout.php";
    break;

    // Thêm khóa học miễn phí vào tiến độ
    case 'progress_add':
        checkLogin();
        (new ProgressController($conn))->add();
    break;


    // ============================================================
    // GIỎ HÀNG & THANH TOÁN (CART & CHECKOUT)
    // ============================================================
    
    // Xem giỏ hàng
    case 'cart':
        checkLogin();
        $cartCtrl = new CartController($conn);
        $items = $cartCtrl->index();

        $view = "views/user/cart.php";
        include "views/layout/user_layout.php";
    break;

    // Thêm vào giỏ
    case 'add_cart':
        checkLogin();
        (new CartController($conn))->add();
    break;

    // Xóa khỏi giỏ
    case 'remove_cart':
        checkLogin();
        (new CartController($conn))->remove();
    break;

    // Thanh toán những khóa học ĐƯỢC TÍCH CHỌN (Checkbox)
    case 'checkout_selected':
        checkLogin();
        (new CartController($conn))->checkoutSelected();
    break;

    // Mua ngay (Thanh toán riêng lẻ 1 khóa)
    case 'buy_now':
        checkLogin();
        (new CartController($conn))->buyNow();
    break;

    // Thanh toán toàn bộ giỏ hàng
    case 'checkout':
        checkLogin();
        (new CartController($conn))->checkout();
    break;

    case 'success':
        echo "<div style='text-align:center; margin-top:50px;'><h2>Thanh toán thành công 🎉</h2><a href='index.php?page=progress'>Vào học ngay</a></div>";
    break;


    // ============================================================
    // TÍNH NĂNG KHÁC (QUIZ, BLOG, CONTACT)
    // ============================================================
    case 'quiz':
        checkLogin();
        $q = new QuizController($conn);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = $q->submit($_GET['lesson_id'] ?? 0);
            $view = "views/user/result.php";
            include "views/layout/user_layout.php";
        } else {
            $quizzes = $q->show($_GET['lesson_id'] ?? 0);
            $view = "views/user/quiz.php";
            include "views/layout/user_layout.php";
        }
    break;

    case 'history':
        checkLogin();
        $r = new ResultController($conn);
        $results = $r->index();
        $view = "views/user/history.php";
        include "views/layout/user_layout.php";
    break;

    case 'blog':
        $posts = $conn->query("SELECT * FROM posts")->fetchAll();
        include "views/user/blog.php";
    break;

    case 'contact':
        if($_POST){
            $stmt = $conn->prepare("INSERT INTO contacts(name,email,message) VALUES(?,?,?)");
            $stmt->execute([$_POST['name'],$_POST['email'],$_POST['message']]);
            echo "Gửi thành công!";
        }
        include "views/user/contact.php";
    break;


    // ============================================================
    // ADMIN - QUẢN TRỊ VIÊN
    // ============================================================
    
    // Thống kê Dashboard
    case 'admin':
        checkAdmin();
        $ad = new AdminController($conn);
        
        // 1. Lấy kết quả từ Controller (Mảng này chứa cả 'data' và 'view')
        $result = $ad->dashboard(); 
        
        // 2. Tách dữ liệu ra cho đúng mục đích
        $data = $result['data']; // Đây mới là mảng chứa totalUsers, totalRevenue...
        $view = $result['view']; // Đây là đường dẫn "views/admin/dashboard.php"

        // 3. Include layout
        include "views/layout/admin_layout.php";
    break;

    /// ============================================================
    // Quản lý Danh mục
    // ============================================================
    case 'admin_categories':
        checkAdmin(); 
        $categoryCtrl = new CategoryController($conn); // Khởi tạo với biến $conn của bạn
        $categoryCtrl->adminIndex(); // Hàm này đã bao gồm xử lý Thêm/Sửa/Xóa và gọi View
        break;

    // Quản lý Khóa học
    case 'admin_courses':
        checkAdmin();         
        $c = new CourseController($conn);

        // 1. Bắt các hành động Thêm / Sửa / Xóa trước
        if(isset($_POST['create'])) $c->store();
        if(isset($_POST['update'])) $c->update();
        if(isset($_GET['delete'])) $c->delete($_GET['delete']);

        // 2. NHẬN DỮ LIỆU TỪ CONTROLLER
        $data = $c->adminIndex(); 
        $courses = $data['courses'];       // Lấy danh sách khóa học
        $categories = $data['categories']; // Lấy danh sách danh mục để đổ vào thẻ <select>

        // 3. GỌI GIAO DIỆN (VIEW & LAYOUT)
        $view = "views/admin/courses.php";
        include "views/layout/admin_layout.php";
        break;

    // Quản lý Bài học
    case 'admin_lessons':
        // 1. Kiểm tra quyền Admin & Nhúng các file cần thiết
        checkAdmin(); 
        // 2. Khởi tạo đối tượng
        $ad = new AdminController($conn);
        $courseModel = new Course($conn);

        // 3. XỬ LÝ CÁC HÀNH ĐỘNG (Thêm / Sửa / Xóa)
        
        // A. Thêm bài học mới
        if(isset($_POST['create'])){
            $ad->addLesson($_POST);
            header("Location: index.php?page=admin_lessons");
            exit;
        }

        // B. Cập nhật bài học 
        if(isset($_POST['update'])){
            $ad->updateLesson($_POST); 
            header("Location: index.php?page=admin_lessons");
            exit;
        }

        // C. Xóa bài học
        if(isset($_GET['delete'])){
            $ad->deleteLesson($_GET['delete']);
            header("Location: index.php?page=admin_lessons");
            exit;
        } 

        // 4. LẤY DỮ LIỆU ĐỂ ĐỔ RA GIAO DIỆN (View)
        // Lấy danh sách tất cả bài học
        $lessons = $ad->lessons();
        
        // Lấy danh sách khóa học để hiện vào ô <select> (Đây là biến quan trọng nhất)
        $coursesList = $courseModel->all(); 

        // 5. NHÚNG GIAO DIỆN
        $view = "views/admin/lessons.php";
        include "views/layout/admin_layout.php";
    break;

    // QUIZ

    case 'admin_quiz':
        checkAdmin(); // Chặn ở đây là chuẩn rồi
        $current_page = 'admin_quiz'; // Khai báo danh tính trang
        $adminQuiz = new AdminQuizController($conn);
        $adminQuiz->index(); 
        break;

    // Quản lý Người dùng
    case 'admin_users':
        checkAdmin();
        $ad = new AdminController($conn);
        if(isset($_GET['delete'])) $ad->deleteUser($_GET['delete']);
        
        $users = $ad->users();
        $view = "views/admin/users.php";
        include "views/layout/admin_layout.php";
    break;

    // Quản lý Đơn hàng
    case 'admin_orders':
        checkAdmin(); // Kiểm tra quyền admin        
        // Khởi tạo Controller
        $controller = new AdminController($conn);
        
        // Gọi hàm xử lý và nhận mảng $data
        $data = $controller->orders();
        
        // Trích xuất dữ liệu từ mảng $data để truyền vào View
        $orders = $data['orders']; 
        $view = $data['view']; 
        
        include "views/layout/admin_layout.php";
    break;

    case 'admin_order_detail':
        checkAdmin();
        $controller = new AdminController($conn);
        $controller->orderDetail($_GET['id']);
        break;
    
    // Tìm kiếm
    case 'api_search':
        // Gọi Controller xử lý tìm kiếm
        require_once "controllers/CourseController.php";
        $courseController = new CourseController($db);
        $courseController->liveSearch();
        break;

    // ============================================================
    // MẶC ĐỊNH
    // ============================================================
    default:
        header("Location:index.php?page=login");
        exit;
    break;
}