<?php
// Nhúng Model vào để tương tác với Database
require_once 'models/Category.php';

class CategoryController {
    private $db;
    private $categoryModel;

    public function __construct($db) {
        $this->db = $db;
        $this->categoryModel = new Category($db); // Khởi tạo Model ngay khi gọi Controller
    }

    // Hàm xử lý trang quản lý danh mục cho Admin
    public function adminIndex() {
 
        // A. XỬ LÝ XÓA DANH MỤC (SET NULL)

        if (isset($_GET['delete'])) {
            $id = $_GET['delete'];
            
            if ($this->categoryModel->delete($id)) {
                // Đổi câu thông báo để Admin yên tâm
                $_SESSION['success'] = "Đã xóa danh mục! Các khóa học cũ đã được chuyển về trạng thái 'Chưa phân loại'.";
            } else {
                $_SESSION['error'] = "Đã xảy ra lỗi khi xóa danh mục.";
            }
            
            header("Location: index.php?page=admin_categories");
            exit();
        }
        // ========================================================
        // B. XỬ LÝ THÊM DANH MỤC MỚI

        if (isset($_POST['create'])) {
            $name = trim($_POST['name']);
            $description = trim($_POST['description'] ?? '');

            if (!empty($name)) {
                $this->categoryModel->create($name, $description);
                $_SESSION['success'] = "Thêm danh mục mới thành công!";
            } else {
                $_SESSION['error'] = "Tên danh mục không được để trống!";
            }
            header("Location: index.php?page=admin_categories");
            exit();
        }

        // --------------------------------------------------------
        // C. XỬ LÝ CẬP NHẬT

        if (isset($_POST['update'])) {
            $id = $_POST['id'];
            $name = trim($_POST['name']);
            $description = trim($_POST['description'] ?? '');

            if (!empty($id) && !empty($name)) {
                $this->categoryModel->update($id, $name, $description);
                $_SESSION['success'] = "Cập nhật danh mục thành công!";
            } else {
                $_SESSION['error'] = "Dữ liệu không hợp lệ!";
            }
            header("Location: index.php?page=admin_categories");
            exit();
        }

        // --------------------------------------------------------
        // D. LẤY DỮ LIỆU ĐỔ RA VIEW

        $categories = $this->categoryModel->all();
        
        $view = 'views/admin/categories.php';
        require_once 'views/layout/admin_layout.php'; 
    }
}