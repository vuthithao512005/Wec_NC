<?php
require_once "models/Course.php";

class CourseController {

    private $course;

    public function __construct($db){
        $this->course = new Course($db);
        
        // Khởi động session nếu cần dùng cho thông báo lỗi/thành công
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }

    // ============================================================
    // USER SIDE: HIỂN THỊ CHO NGƯỜI DÙNG
    // ============================================================
    
    /**
     * Danh sách khóa học cho User
     */
    public function index(){
        $keyword = $_GET['search'] ?? ''; 
        $category_id = $_GET['category'] ?? null;

        if(!empty($keyword)){
            $courses = $this->course->search($keyword);
        } else {
            $courses = $this->course->getAllWithCategory($category_id);
        }

        $categories = $this->course->getCategories();

        return [
            'courses' => $courses,
            'categories' => $categories
        ];
    }

    /**
     * Xem chi tiết 1 khóa học
     */
    public function detail($id){
        if(!$id) return null;
        return $this->course->find($id);
    }


    // ============================================================
    // ADMIN SIDE: QUẢN TRỊ VIÊN
    // ============================================================

    /**
     * Danh sách khóa học cho Admin
     */
    public function adminIndex() {
        $keyword = $_GET['keyword'] ?? null;
        
        // Trả về cả Khóa học VÀ Danh mục (Để hiển thị vào ô Select của Form)
        $data['courses'] = $this->course->all($keyword); 
        $data['categories'] = $this->course->getCategories(); // <-- Đã thêm cái này để Form không bị trống
        
        return $data;
    }

    /**
     * Xử lý thêm khóa học mới (ĐÃ NÂNG CẤP: category_id, price)
     */
    public function store(){
        if($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $title = trim($_POST['title'] ?? '');
        $category_id = $_POST['category_id'] ?? null;
        $price = !empty($_POST['price']) ? $_POST['price'] : 0;
        $desc  = trim($_POST['desc'] ?? '');
        $image = $_POST['image'] ?? ''; // Link ảnh trực tiếp

        // Xử lý upload file ảnh từ máy tính
        if(isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0){
            $fileName = time() . "_" . $_FILES['image_file']['name'];
            $path = "uploads/" . $fileName;

            // Đảm bảo thư mục uploads tồn tại
            if (!file_exists("uploads/")) mkdir("uploads/", 0777, true);

            if(move_uploaded_file($_FILES['image_file']['tmp_name'], $path)){
                $image = $path;
            }
        }

        if(empty($title) || empty($desc) || empty($category_id)){
            $_SESSION['error'] = "Vui lòng nhập đầy đủ tiêu đề, danh mục và mô tả";
            header("Location: index.php?page=admin_courses");
            exit;
        }

        // Gọi hàm create đã nâng cấp ở Model (5 tham số)
        if($this->course->create($title, $category_id, $price, $desc, $image)){
            $_SESSION['success'] = "Thêm khóa học thành công";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi lưu vào database";
        }

        header("Location: index.php?page=admin_courses");
        exit;
    }

    /**
     * Xử lý cập nhật khóa học (ĐÃ NÂNG CẤP: category_id, price)
     */
    public function update(){
        if($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id    = $_POST['id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $category_id = $_POST['category_id'] ?? null;
        $price = !empty($_POST['price']) ? $_POST['price'] : 0;
        $desc  = trim($_POST['desc'] ?? '');
        $image = $_POST['image'] ?? null; // Ảnh cũ hoặc link mới

        if(!$id) return;

        // Xử lý upload ảnh mới (nếu có)
        if(isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0){
            $fileName = time() . "_" . $_FILES['image_file']['name'];
            $path = "uploads/" . $fileName;
            
            if (!file_exists("uploads/")) mkdir("uploads/", 0777, true);

            if(move_uploaded_file($_FILES['image_file']['tmp_name'], $path)){
                $image = $path;
            }
        }

        if(empty($title) || empty($desc) || empty($category_id)){
            $_SESSION['error'] = "Tiêu đề, danh mục và mô tả không được để trống";
            header("Location: index.php?page=admin_courses");
            exit;
        }

        // Gọi hàm update đã nâng cấp ở Model (6 tham số)
        $this->course->update($id, $title, $category_id, $price, $desc, $image);
        $_SESSION['success'] = "Cập nhật khóa học thành công";

        header("Location: index.php?page=admin_courses");
        exit;
    }

    /**
     * Xử lý xóa khóa học (ĐÃ NÂNG CẤP: Bắt lỗi khóa ngoại)
     */
    public function delete($id){
        if(!$id){
            $_SESSION['error'] = "ID không hợp lệ";
            header("Location: index.php?page=admin_courses");
            exit;
        }

        try {
            $this->course->delete($id);
            $_SESSION['success'] = "Đã xóa khóa học";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Không thể xóa! Khóa học này đang chứa Bài học hoặc Hóa đơn.";
        }

        header("Location: index.php?page=admin_courses");
        exit;
    }

    /**
     * Thống kê số lượng
     */
    public function count(){
        return $this->course->count();
    }
    
    //Tìm kiếm
    public function liveSearch() {
        $keyword = trim($_GET['keyword'] ?? '');
        
        if(empty($keyword)){
            echo json_encode([]); // Trả về mảng rỗng nếu không nhập gì
            exit;
        }

        // Dùng lại hàm searchCourses ở Model mà lúc nãy mình hướng dẫn bạn viết
        $courses = $this->course->searchCourses($keyword);
        
        // Trả dữ liệu về dạng JSON để JavaScript đọc được
        echo json_encode($courses);
        exit;
    }
}