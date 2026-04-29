<?php
// Không cần require_once 'models/User.php' ở đây nếu bạn đã nạp ở đầu file routes.php

class AuthController {
    private $db;

    // Bước 1: Khởi tạo để nhận biến kết nối CSDL
    public function __construct($db) {
        $this->db = $db;
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            $errors = [];

            // Validate dữ liệu
            if (empty($name)) { $errors[] = "Tên không được để trống"; }
            if (empty($email)) { 
                $errors[] = "Email không được để trống"; 
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không hợp lệ";
            }

            if (empty($password)) {
                $errors[] = "Mật khẩu không được để trống";
            } elseif (strlen($password) < 6) {
                $errors[] = "Mật khẩu phải >= 6 ký tự";
            }

            if ($password !== $confirm) {
                $errors[] = "Mật khẩu không khớp";
            }

            // Bước 2: Truyền $this->db vào Model User để hết lỗi Fatal Error
            $userModel = new User($this->db);

            if ($userModel->findByEmail($email)) {
                $errors[] = "Email đã tồn tại";
            }

            // Nếu không có lỗi thì tiến hành tạo tài khoản
            if (empty($errors)) {
                $hashed = password_hash($password, PASSWORD_BCRYPT);
                $userModel->create($name, $email, $hashed);

                $_SESSION['success'] = "Đăng ký thành công!";
                header("Location: index.php?page=login");
                exit;
            }
        }

        require 'views/auth/register.php';
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $errors = [];

            if (empty($email)) {
                $errors[] = "Email không được để trống";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không hợp lệ";
            }

            if (empty($password)) {
                $errors[] = "Mật khẩu không được để trống";
            }

            if (empty($errors)) {
                // Bước 2: Truyền $this->db vào đây để khởi tạo User chuẩn
                $userModel = new User($this->db);
                $user = $userModel->findByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    session_regenerate_id(true);

                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'role' => $user['role']
                    ];

                    if ($user['role'] === 'admin') {
                        header("Location: index.php?page=admin");
                    } else {
                        header("Location: index.php?page=home");
                    }
                    exit;
                } else {
                    $errors[] = "Sai email hoặc mật khẩu";
                }
            }
        }

        require 'views/auth/login.php';
    }

    public function logout() {
        // Xóa sạch session
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header("Location: index.php?page=login");
        exit;
    }
}