<?php
class User {
    private $conn;
    private $table = "users"; // Tên bảng trong Database của bạn

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * 1. LẤY DANH SÁCH TẤT CẢ NGƯỜI DÙNG (KÈM BỘ LỌC)
     * @param string $role (admin hoặc user)
     * @param string $fromDate (định dạng Y-m-d)
     * @param string $toDate (định dạng Y-m-d)
     */

    //Tìm kiếm
    // Dùng cho USER: Tìm bạn bè hoặc tìm giảng viên (chỉ lấy thông tin công khai)
    public function searchPublicProfile($keyword) {
        return $this->db->query("SELECT name, avatar FROM users WHERE name LIKE '%$keyword%' AND status = 1");
    }

    // Dùng cho ADMIN: Tìm học viên để phân tích AI (lấy toàn bộ dữ liệu nhạy cảm)
    public function searchUserForAdmin($keyword) {
        return $this->db->query("SELECT * FROM users WHERE (name LIKE '%$keyword%' OR email LIKE '%$keyword%')");
    }

    public function all($role = null, $fromDate = null, $toDate = null, $keyword = null) {
        $sql = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $params = [];

        // 1. Lọc theo từ khóa (Tên hoặc Email) - Cần thiết cho thanh tìm kiếm
    if (!empty($keyword)) {
        $sql .= " AND (name LIKE ? OR email LIKE ?)";
        $params[] = "%$keyword%";
        $params[] = "%$keyword%";
    }
    
        // Lọc theo vai trò (So sánh với chuỗi 'admin' hoặc 'user')
        if (!empty($role)) {
            $sql .= " AND (name LIKE ? OR email LIKE ?)";
            $params[] = $role;
        }

        // Lọc theo khoảng ngày tạo tài khoản
        if (!empty($fromDate)) {
            $sql .= " AND DATE(created_at) >= ?";
            $params[] = $fromDate;
        }

        if (!empty($toDate)) {
            $sql .= " AND DATE(created_at) <= ?";
            $params[] = $toDate;
        }

        $sql .= " ORDER BY id DESC";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Ghi log lỗi nếu cần thiết
            return [];
        }
    }

    /**
     * 2. TÌM NGƯỜI DÙNG THEO EMAIL (Dùng để kiểm tra trùng lặp)
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 3. THÊM NGƯỜI DÙNG MỚI
     */
    public function create($name, $email, $password, $role = 'user') {
        $sql = "INSERT INTO " . $this->table . " (name, email, password, role) VALUES (?, ?, ?, ?)";
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$name, $email, $password, $role]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * 4. CẬP NHẬT THÔNG TIN NGƯỜI DÙNG
     * Đã lược bỏ cột 'status' để tránh lỗi SQLSTATE[42S22]
     */
    public function update($id, $name, $email, $hashedPassword = null, $role = 'user') {
        if ($hashedPassword) {
            // Nếu có đổi mật khẩu mới
            $sql = "UPDATE " . $this->table . " SET name = ?, email = ?, password = ?, role = ? WHERE id = ?";
            $params = [$name, $email, $hashedPassword, $role, $id];
        } else {
            // Nếu giữ nguyên mật khẩu cũ
            $sql = "UPDATE " . $this->table . " SET name = ?, email = ?, role = ? WHERE id = ?";
            $params = [$name, $email, $role, $id];
        }

        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * 5. XÓA NGƯỜI DÙNG
     */
    public function delete($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * 6. ĐẾM TỔNG SỐ NGƯỜI DÙNG (Dùng cho Dashboard)
     */
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['total'] ?? 0;
    }

    // ============================================================
    // CÁC HÀM THỐNG KÊ DÀNH CHO BIỂU ĐỒ (DASHBOARD)
    // ============================================================
    /**
     * Hàm tổng hợp để Controller gọi gọn hơn
     */
    public function getRegistrationStats($type = 'month') {
    if ($type === 'year') {
        $sql = "SELECT YEAR(u.created_at) as label, 
                       COUNT(u.id) as users,
                       (SELECT IFNULL(SUM(total), 0) FROM orders WHERE YEAR(created_at) = label) as revenue
                FROM users u
                GROUP BY label ORDER BY label ASC";
    } elseif ($type === 'week') {
        $sql = "SELECT DATE_FORMAT(u.created_at, '%d/%m') as label, 
                       COUNT(u.id) as users,
                       (SELECT IFNULL(SUM(total), 0) FROM orders WHERE DATE_FORMAT(created_at, '%d/%m') = label) as revenue
                FROM users u
                WHERE u.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY label ORDER BY u.created_at ASC";
    } else {
        // Thống kê theo tháng (Mặc định)
        $sql = "SELECT DATE_FORMAT(u.created_at, '%m/%Y') as label, 
                       COUNT(u.id) as users,
                       (SELECT IFNULL(SUM(total), 0) FROM orders WHERE DATE_FORMAT(created_at, '%m/%Y') = label) as revenue
                FROM users u
                WHERE YEAR(u.created_at) = YEAR(NOW())
                GROUP BY label ORDER BY u.created_at ASC";
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($results as $row) {
        $data[$row['label']] = [
            'users' => (int)$row['users'],
            'revenue' => (float)$row['revenue']
        ];
    }
    return $data;
}

    /**
     * Thống kê theo Tháng: Trả về dạng ['01/2026' => 5, '02/2026' => 10]
     */
    public function getUsersByMonth() {
        $sql = "SELECT DATE_FORMAT(created_at, '%m/%Y') as label, COUNT(*) as count 
                FROM " . $this->table . " 
                WHERE YEAR(created_at) = YEAR(CURDATE())
                GROUP BY label ORDER BY created_at ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [];
        foreach ($results as $row) {
            $data[$row['label']] = (int)$row['count'];
        }
        return $data;
    }

    /**
     * Thống kê theo Tuần: Trả về dạng ['Monday' => 2, 'Tuesday' => 4]
     */
    public function getUsersByWeek() {
        // Lấy dữ liệu trong 7 ngày gần nhất để biểu đồ luôn có dữ liệu
        $sql = "SELECT DATE_FORMAT(created_at, '%d/%m') as label, COUNT(*) as count 
                FROM " . $this->table . " 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY label ORDER BY created_at ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [];
        foreach ($results as $row) {
            $data[$row['label']] = (int)$row['count'];
        }
        return $data;
    }

    /**
     * Thống kê theo Năm: Trả về dạng ['2025' => 100, '2026' => 150]
     */
    public function getUsersByYear() {
        $sql = "SELECT YEAR(created_at) as label, COUNT(*) as count 
                FROM " . $this->table . " 
                GROUP BY label ORDER BY label ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [];
        foreach ($results as $row) {
            $data[$row['label']] = (int)$row['count'];
        }
        return $data;
    }
}