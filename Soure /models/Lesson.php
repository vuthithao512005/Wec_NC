<?php
class Lesson {
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    // 📚 Lấy tất cả
    public function all(){
        $stmt = $this->conn->query("SELECT * FROM lessons ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ➕ Thêm bài học
    public function create($title,$video,$course_id){
        $stmt = $this->conn->prepare("
            INSERT INTO lessons(title,video,course_id)
            VALUES(?,?,?)
        ");
        $stmt->execute([$title,$video,$course_id]);
    }

    // ❌ Xóa
    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM lessons WHERE id=?");
        $stmt->execute([$id]);
    }

    // ===== CẬP NHẬT BÀI HỌC =====
    public function update($id, $title, $content, $video, $position) {
        // Đã sửa lại tên cột video cho khớp với hàm create() của bạn
        $sql = "UPDATE lessons 
                SET title = ?, content = ?, video = ?, position = ? 
                WHERE id = ?";
                
        // Đã đổi $this->db thành $this->conn
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$title, $content, $video, $position, $id]);
    }
    // 📚 Lấy theo khóa học
    public function getByCourse($course_id){
        $stmt = $this->conn->prepare("
            SELECT * FROM lessons WHERE course_id=?
        ");
        $stmt->execute([$course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 📄 Lấy 1 bài
    public function getById($id){
        $stmt = $this->conn->prepare("
            SELECT * FROM lessons WHERE id=?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔢 Đếm
    public function count(){
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM lessons");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}