<?php
class Order {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * 1. LẤY TOÀN BỘ ĐƠN HÀNG (BẢNG CHA - orders)
     * Kết hợp JOIN với bảng users để lấy tên người mua thay vì chỉ hiện ID
     */
    public function all() {
        // o.* là lấy tất cả cột trong bảng orders
        // u.name là lấy tên từ bảng users
        $sql = "SELECT o.*, u.name as user_name 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Trả về mảng rỗng nếu có lỗi SQL
            return [];
        }
    }

    /**
     * 2. LẤY CHI TIẾT CỦA MỘT ĐƠN HÀNG (BẢNG CON - order_items)
     * Dùng khi bạn muốn xem trong đơn hàng đó có những khóa học nào
     */
    public function getItemsByOrderId($id) {
        // JOIN bảng order_items với courses để lấy Tên khóa học (title)
        $sql = "SELECT oi.*, c.title as course_name 
                FROM order_items oi
                JOIN courses c ON oi.course_id = c.id
                WHERE oi.order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3. XÓA ĐƠN HÀNG (QUAN TRỌNG)
     * Phải xóa các mục trong order_items trước để tránh lỗi ràng buộc CSDL
     */
    public function delete($id) {
        try {
            // Bước A: Xóa sạch các món hàng thuộc hóa đơn này trong bảng con
            $sql_items = "DELETE FROM order_items WHERE order_id = ?";
            $stmt_items = $this->conn->prepare($sql_items);
            $stmt_items->execute([$id]);

            // Bước B: Sau đó mới xóa hóa đơn chính trong bảng cha
            $sql_order = "DELETE FROM orders WHERE id = ?";
            $stmt_order = $this->conn->prepare($sql_order);
            return $stmt_order->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * 4. THỐNG KÊ DOANH THU (Dành cho Dashboard)
     */
    public function getTotalRevenue() {
        $sql = "SELECT SUM(total) as revenue FROM orders WHERE status = 'paid'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['revenue'] ?? 0;
    }
}