<?php
require_once "models/Cart.php";

class CartController {

    private $db;
    private $cart;

    public function __construct($db){
        $this->db = $db;
        $this->cart = new Cart($db);

        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }

    private function checkAuth() {
        if(!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])){
            header("Location: index.php?page=login");
            exit;
        }
        return $_SESSION['user']['id'];
    }

    public function index(){
        $user_id = $this->checkAuth();
        $items = $this->cart->get($user_id);
        return $items;
    }

    // ===== 2. THÊM VÀO GIỎ HÀNG =====
    public function add(){
        $user_id = $this->checkAuth();
        $course_id = $_GET['id'] ?? 0;

        if($course_id){
            // BƯỚC 1: Kiểm tra xem User đã sở hữu khóa học này trong user_courses chưa
            $stmtCheck = $this->db->prepare("SELECT 1 FROM user_courses WHERE user_id = ? AND course_id = ?");
            $stmtCheck->execute([$user_id, $course_id]);
            $already_owned = $stmtCheck->fetchColumn();

            if ($already_owned) {
                // Đã mua rồi -> Báo lỗi bằng Toast và đẩy về trang danh sách khóa học
                $_SESSION['toast_msg'] = "Bạn đã sở hữu khóa học này rồi, hãy vào mục Tiến độ để học nhé!";
                $_SESSION['toast_type'] = "danger"; // Hiện màu đỏ
                header("Location: index.php?page=courses"); // Hoặc trỏ về trang bạn muốn
                exit;
            }

            // BƯỚC 2: Kiểm tra xem khóa học đã có sẵn trong giỏ hàng chưa (để tránh add 2 lần vào giỏ)
            $stmtCartCheck = $this->db->prepare("SELECT 1 FROM cart WHERE user_id = ? AND course_id = ?");
            $stmtCartCheck->execute([$user_id, $course_id]);
            $already_in_cart = $stmtCartCheck->fetchColumn();

            if ($already_in_cart) {
                $_SESSION['toast_msg'] = "Khóa học này đã nằm sẵn trong giỏ hàng của bạn rồi!";
                $_SESSION['toast_type'] = "danger";
                header("Location: index.php?page=cart");
                exit;
            }

            // BƯỚC 3: Vượt qua hết các bài test thì mới cho vào giỏ
            $this->cart->add($user_id, $course_id);
            
            $_SESSION['toast_msg'] = "Thêm vào giỏ hàng thành công!";
            $_SESSION['toast_type'] = "success";
        }

        header("Location: index.php?page=cart");
        exit;
    }

    public function remove(){
        $user_id = $this->checkAuth();
        $course_id = $_GET['id'] ?? 0;

        if($course_id){
            $this->cart->remove($user_id, $course_id);
        }

        header("Location: index.php?page=cart");
        exit;
    }

    // ===== THANH TOÁN CÁC KHÓA ĐƯỢC CHỌN =====
    public function checkoutSelected() {
        $user_id = $this->checkAuth();
        $selected_ids = $_POST['selected_courses'] ?? [];

        if (empty($selected_ids)) {
            header("Location: index.php?page=cart");
            exit;
        }

        try {
            $this->db->beginTransaction();

            $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));
            $sql = "SELECT id, price FROM courses WHERE id IN ($placeholders)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($selected_ids);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($courses)) {
                throw new Exception("Không tìm thấy thông tin khóa học.");
            }

            $total = 0;
            foreach ($courses as $c) {
                $total += $c['price'];
            }

            // 1. Lưu hóa đơn
            $stmtOrder = $this->db->prepare("INSERT INTO orders(user_id, total, status) VALUES(?, ?, 'paid')");
            $stmtOrder->execute([$user_id, $total]);
            $order_id = $this->db->lastInsertId();

            // 2. Chuẩn bị các lệnh Insert
            $stmtItem = $this->db->prepare("INSERT INTO order_items(order_id, course_id, price) VALUES(?, ?, ?)");
            $stmtUnlock = $this->db->prepare("INSERT IGNORE INTO user_courses(user_id, course_id) VALUES(?, ?)");

            foreach ($courses as $c) {
                // Lưu chi tiết hóa đơn
                $stmtItem->execute([$order_id, $c['id'], $c['price']]);
                
                // Mở khóa khóa học (Thêm vào bảng user_courses)
                $stmtUnlock->execute([$user_id, $c['id']]);
                
                // Xóa khỏi giỏ hàng
                $this->cart->remove($user_id, $c['id']);
            }

            $this->db->commit();
            
            // TẠO THÔNG BÁO VÀ QUAY VỀ TRANG GIỎ HÀNG
            $_SESSION['toast_msg'] = "Thanh toán thành công! Khóa học đã được thêm vào tài khoản của bạn.";
            $_SESSION['toast_type'] = "success";
            header("Location: index.php?page=cart");
            exit;

        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['toast_msg'] = "Lỗi thanh toán: " . $e->getMessage();
            $_SESSION['toast_type'] = "danger";
            header("Location: index.php?page=cart");
            exit;
        }
    }

    // ===== THANH TOÁN TOÀN BỘ =====
    public function checkout(){
        $user_id = $this->checkAuth();
        $items = $this->cart->get($user_id);

        if(empty($items)){
            header("Location: index.php?page=cart");
            exit;
        }

        $total = 0;
        foreach($items as $i){
            $total += $i['price'];
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO orders(user_id, total, status) VALUES(?, ?, 'paid')");
            $stmt->execute([$user_id, $total]); 
            $order_id = $this->db->lastInsertId();

            $stmt_item = $this->db->prepare("INSERT INTO order_items(order_id, course_id, price) VALUES(?, ?, ?)");
            $stmtUnlock = $this->db->prepare("INSERT IGNORE INTO user_courses(user_id, course_id) VALUES(?, ?)");

            foreach($items as $i){
                $stmt_item->execute([$order_id, $i['course_id'], $i['price']]);
                $stmtUnlock->execute([$user_id, $i['course_id']]);
            }

            $this->cart->clear($user_id);
            $this->db->commit();

            $_SESSION['toast_msg'] = "Thanh toán thành công toàn bộ giỏ hàng!";
            $_SESSION['toast_type'] = "success";
            header("Location: index.php?page=cart");
            exit;

        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['toast_msg'] = "Lỗi thanh toán: " . $e->getMessage();
            $_SESSION['toast_type'] = "danger";
            header("Location: index.php?page=cart");
            exit;
        }
    }
}