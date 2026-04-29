<?php
class Quiz {
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    // ==========================================
    // 👤 PHẦN DÀNH CHO USER (Người học)
    // ==========================================
    public function getByLesson($lesson_id){
        $stmt = $this->conn->prepare("SELECT * FROM quizzes WHERE lesson_id=? ORDER BY RAND()");
        $stmt->execute([$lesson_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveResult($user_id, $lesson_id, $score){
        $stmt = $this->conn->prepare("INSERT INTO results(user_id, lesson_id, score) VALUES(?, ?, ?)");
        $stmt->execute([$user_id, $lesson_id, $score]);
    }

    // ==========================================
    // ⚙️ PHẦN DÀNH CHO ADMIN (Quản lý dữ liệu)
    // ==========================================

    // Lấy tất cả câu hỏi chung (Giữ lại để không lỗi các phần khác nếu có dùng)
    public function getAllQuestions($lesson_id = null, $keyword = null) {
        $query = "SELECT q.*, l.title as lesson_title 
                  FROM quizzes q 
                  LEFT JOIN lessons l ON q.lesson_id = l.id 
                  WHERE 1=1";
        $params = [];

        if (!empty($lesson_id)) {
            $query .= " AND q.lesson_id = ?";
            $params[] = $lesson_id;
        }

        if (!empty($keyword)) {
            $query .= " AND q.question LIKE ?";
            $params[] = '%' . $keyword . '%';
        }

        $query .= " ORDER BY q.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // LẤY CÂU HỎI THEO KHÓA HỌC (TÍCH HỢP BỘ LỌC CHO GIAO DIỆN MỚI)
    public function getByCourseWithFilter($course_id, $lesson_id = null) {
        $sql = "SELECT q.*, l.title as lesson_title, c.title as course_title 
                FROM quizzes q 
                JOIN lessons l ON q.lesson_id = l.id 
                JOIN courses c ON l.course_id = c.id
                WHERE c.id = ?";
        $params = [$course_id];

        // Nếu Admin chọn lọc theo Bài học
        if (!empty($lesson_id)) {
            $sql .= " AND q.lesson_id = ?";
            $params[] = $lesson_id;
        }

        $sql .= " ORDER BY l.id ASC, q.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm câu hỏi mới
    public function create($data) {
        $query = "INSERT INTO quizzes (lesson_id, question, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        // Chuẩn hóa đáp án đúng luôn là chữ in hoa
        $correct = strtoupper(trim($data['correct_answer'] ?? 'A'));
        
        return $stmt->execute([
            $data['lesson_id'], 
            $data['question'], 
            $data['option_a'], 
            $data['option_b'], 
            $data['option_c'], 
            $data['option_d'], 
            $correct
        ]);
    }

    // Cập nhật câu hỏi
    public function update($id, $data) {
        $query = "UPDATE quizzes SET lesson_id=?, question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_answer=? WHERE id=?";
        $stmt = $this->conn->prepare($query);
        
        $correct = strtoupper(trim($data['correct_answer'] ?? 'A'));
        
        return $stmt->execute([
            $data['lesson_id'], 
            $data['question'], 
            $data['option_a'], 
            $data['option_b'], 
            $data['option_c'], 
            $data['option_d'], 
            $correct, 
            $id
        ]);
    }

    // Xóa câu hỏi
    public function delete($id) {
        $query = "DELETE FROM quizzes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    // Lấy chi tiết 1 câu hỏi
    public function getById($id) {
        $query = "SELECT * FROM quizzes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>