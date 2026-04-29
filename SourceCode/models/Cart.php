<?php
class Cart {
    private $conn;

    // Hàm khởi tạo: nhận kết nối từ Controller truyền sang
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * THÊM KHÓA HỌC VÀO GIỎ HÀNG
     * Fix lỗi: Thay $this->db thành $this->conn
     * Logic: Chặn không cho thêm khóa học đã có sẵn trong giỏ
     */
    public function add($user_id, $course_id) {
        // 1. Kiểm tra xem khóa học này đã tồn tại trong giỏ hàng của user này chưa
        $checkSql = "SELECT id FROM cart WHERE user_id = ? AND course_id = ?";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->execute([$user_id, $course_id]);

        // 2. Nếu CHƯA CÓ (rowCount == 0) thì mới thực hiện thêm mới
        if ($checkStmt->rowCount() == 0) {
            $sql = "INSERT INTO cart (user_id, course_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$user_id, $course_id]);
        }
        
        // Nếu đã có rồi, trả về true để Controller tiếp tục chuyển hướng mà không báo lỗi
        return true; 
    }

    /**
     * LẤY DANH SÁCH GIỎ HÀNG
     * Lấy thêm thông tin title, price và image để hiển thị ở giao diện
     */
    public function get($user_id) {
        $sql = "SELECT c.*, courses.title, courses.price, courses.image 
                FROM cart c 
                JOIN courses ON c.course_id = courses.id
                WHERE c.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * XÓA TOÀN BỘ GIỎ HÀNG
     * Thường dùng sau khi thanh toán thành công
     */
    public function clear($user_id) {
        $sql = "DELETE FROM cart WHERE user_id = ?";
        return $this->conn->prepare($sql)->execute([$user_id]);
    }

    /**
     * XÓA MỘT KHÓA HỌC KHỎI GIỎ HÀNG
     */
    public function remove($user_id, $course_id) {
        $sql = "DELETE FROM cart WHERE user_id = ? AND course_id = ?";
        return $this->conn->prepare($sql)->execute([$user_id, $course_id]);
    }
}