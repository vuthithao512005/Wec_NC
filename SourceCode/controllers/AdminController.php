<?php
require_once "models/Course.php";
require_once "models/User.php";
require_once "models/Lesson.php";

class AdminController {

    private $course, $user, $lesson, $order; 
    public function __construct($db) {
        $this->course = new Course($db);
        $this->user = new User($db);
        $this->lesson = new Lesson($db);
        $this->order = new Order($db);
    }

    // ============================================================
    // QUẢN LÝ NGƯỜI DÙNG (USERS)
    // ============================================================
    public function users() {
        // --- 0. XỬ LÝ XÓA (Bắt tín hiệu delete_id từ View) ---
        if (isset($_GET['delete_id'])) {
            $this->user->delete($_GET['delete_id']);
            $_SESSION['success'] = "Đã xóa người dùng thành công!";
            header("Location: index.php?page=admin_users");
            exit;
        }

        // --- 1. XỬ LÝ THÊM HOẶC SỬA (POST) ---
        if (isset($_POST['save_user']) || isset($_POST['update_user'])) {
            $isUpdate = isset($_POST['update_user']);
            $result = $isUpdate ? $this->updateUser($_POST) : $this->addUser($_POST);
            
            if ($result) {
                $_SESSION['success'] = $isUpdate ? "Cập nhật thành công!" : "Thêm thành công!";
                header("Location: index.php?page=admin_users");
                exit;
            }
            // Nếu lỗi (result = false), code chạy tiếp xuống dưới để load View kèm thông báo lỗi
        }

        // --- 2. LẤY DỮ LIỆU HIỂN THỊ & BỘ LỌC ---
        $role = $_GET['role'] ?? null;
        $fromDate = $_GET['from_date'] ?? null;
        $toDate = $_GET['to_date'] ?? null;

        return $this->user->all($role, $fromDate, $toDate);
    }

    // Hàm thực thi logic Thêm User
    private function addUser($data) {
        $name = $data['fullname'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $role = $data['role'] ?? 'user';

        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['error'] = "Vui lòng nhập đủ thông tin!";
            return false;
        }
        if (strlen($password) < 6) {
            $_SESSION['error'] = "Mật khẩu phải từ 6 ký tự trở lên!";
            return false;
        }
        if ($this->user->findByEmail($email)) {
            $_SESSION['error'] = "Email '$email' đã tồn tại trên hệ thống!";
            return false;
        }

        return $this->user->create($name, $email, password_hash($password, PASSWORD_DEFAULT), $role);
    }

    // Hàm thực thi logic Sửa User
    private function updateUser($data) {
        $id = $data['id'] ?? '';
        $name = $data['fullname'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $role = $data['role'] ?? 'user';

        if (empty($id) || empty($name) || empty($email)) {
            $_SESSION['error'] = "Không được để trống Tên và Email!";
            return false;
        }
        if (!empty($password) && strlen($password) < 6) {
            $_SESSION['error'] = "Mật khẩu mới phải từ 6 ký tự trở lên!";
            return false;
        }

        $existing = $this->user->findByEmail($email);
        if ($existing && $existing['id'] != $id) {
            $_SESSION['error'] = "Email này đã được người khác sử dụng!";
            return false;
        }

        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;
        return $this->user->update($id, $name, $email, $hashedPassword, $role);
    }

    // ============================================================
    // DASHBOARD & THỐNG KÊ
    // ============================================================
    public function dashboard() {
        // 1. Xử lý AJAX cho biểu đồ (Khi JS gọi fetch)
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            header('Content-Type: application/json');
            $type = $_GET['type'] ?? 'month';
            
            // Gọi hàm đã sửa ở Model User để lấy mảng [users, revenue]
            $chartData = $this->user->getRegistrationStats($type);
            
            echo json_encode($chartData);
            exit;
        }

        // 2. Dữ liệu cho các thẻ Card (Giữ nguyên như cũ)
        $data = [
            'totalUsers'    => $this->user->count(),
            'totalCourses'  => $this->course->count(),
            'totalLessons'  => $this->lesson->count(),
            'totalOrders'   => count($this->order->all()),
            'totalRevenue'  => $this->order->getTotalRevenue(),
            'topCourses' => $this->course->getTopSellingCourses(5)
        ];

        return [
            'data' => $data,
            'view' => 'views/admin/dashboard.php'
        ];
    }

    /// ============================================================
    // KHÓA HỌC (COURSES)
    // ============================================================

    public function adminIndex() {
    // 1. Lấy từ khóa từ URL (do form GET gửi lên)
    $keyword = $_GET['keyword'] ?? null; 

    return [
        // 2. Truyền keyword vào hàm all() của model Course
        'courses' => $this->course->all($keyword), 
        'categories' => $this->course->getCategories(),
        'view' => "views/admin/courses.php"
    ];
    }

    public function courses() { 
        return $this->course->all(); 
    }
    
    // Đã bổ sung price và category_id
    public function addCourse($data) { 
        $this->course->create($data['title'], $data['description'], $data['price'], $data['image'], $data['category_id']); 
    }
    
    public function updateCourse($id, $data) { 
        $this->course->update($id, $data['title'], $data['description'], $data['price'], $data['category_id']); 
    }
    
    public function deleteCourse($id) { 
        $this->course->delete($id); 
    }

    // ============================================================
    // BÀI HỌC (LESSONS)
    // ============================================================
    public function lessons() { 
        return $this->lesson->all(); 
    }
    
    // Đã bổ sung position
    public function addLesson($data) { 
        $this->lesson->create($data['title'], $data['video'], $data['course_id'], $data['position']); 
    }

    // ĐÃ BỔ SUNG HÀM BỊ THIẾU ĐỂ FIX LỖI FATAL ERROR
    public function updateLesson($data) {
        $id = $data['id'] ?? 0;
        
        // 1. Lấy dữ liệu an toàn
        $title    = $data['title'] ?? '';
        $content  = $data['content'] ?? ''; // Check kỹ name="content" ở Form HTML
        $video    = $data['video'] ?? $data['video_url'] ?? ''; 
        $position = $data['position'] ?? 1;
        $course_id = $data['course_id'] ?? 0; // Để quay về đúng khóa học sau khi sửa

        if ($id > 0) {
            // 2. Gọi Model thực thi
            $result = $this->lesson->update($id, $title, $content, $video, $position);

            if ($result) {
                $_SESSION['toast_msg'] = "Cập nhật nội dung bài học thành công!";
                $_SESSION['toast_type'] = "success";
            } else {
                $_SESSION['toast_msg'] = "Lỗi: Không thể lưu dữ liệu vào Database.";
                $_SESSION['toast_type'] = "danger";
            }
        } else {
            $_SESSION['toast_msg'] = "Lỗi: Không tìm thấy ID bài học.";
            $_SESSION['toast_type'] = "danger";
        }

        // 3. BẮT BUỘC PHẢI CÓ: Quay về trang quản lý để xem kết quả
        header("Location: index.php?page=admin_lessons&course_id=" . $course_id);
        exit;
    }
    
    public function deleteLesson($id) { 
        $this->lesson->delete($id); 
    }

   // QUẢN LÝ ĐƠN HÀNG 
    public function orders() {
        // 1. Xử lý Xóa đơn hàng (Bắt delete_id từ URL)
        if (isset($_GET['delete_id'])) {
            // Gọi hàm delete trong Model Order (hàm này đã bao gồm xóa order_items trước)
            if ($this->order->delete($_GET['delete_id'])) {
                $_SESSION['success'] = "Đã xóa đơn hàng thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi xóa đơn hàng!";
            }
            header("Location: index.php?page=admin_orders");
            exit;
        }

        // 2. Lấy danh sách đơn hàng để hiển thị
        // Hàm all() này đã thực hiện JOIN với bảng users để lấy tên khách hàng
        $orders = $this->order->all();
        
        // 3. Đóng gói dữ liệu trả về cho routes.php
        // Phải trả về một mảng chứa cả 'orders' và 'view' để route xử lý
        return [
            'orders' => $orders,
            'view'   => "views/admin/orders.php"
        ];
    }
    //Xem chi tiết
    public function orderDetail($id) {
        header('Content-Type: application/json');
        // Lấy chi tiết các món hàng từ Model Order (Hàm getItemsByOrderId đã viết trong Model)
        $items = $this->order->getItemsByOrderId($id);
        echo json_encode($items);
        exit;
    }
}