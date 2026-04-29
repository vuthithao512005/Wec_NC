<?php
class Progress {
    private $conn;

    function __construct($db){
        $this->conn = $db;
    }

    // ✅ đánh dấu đã học
    function markCompleted($user_id, $lesson_id){

        $check = $this->conn->prepare("
            SELECT id FROM progress WHERE user_id=? AND lesson_id=?
        ");
        $check->execute([$user_id, $lesson_id]);

        if($check->rowCount() == 0){
            $stmt = $this->conn->prepare("
                INSERT INTO progress(user_id,lesson_id)
                VALUES(?,?)
            ");
            $stmt->execute([$user_id, $lesson_id]);
        }
    }

    // ✅ đếm số bài đã học
    function countCompleted($user_id, $course_id){
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total 
            FROM progress p
            JOIN lessons l ON p.lesson_id = l.id
            WHERE p.user_id=? AND l.course_id=?
        ");
        $stmt->execute([$user_id, $course_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // ✅ tổng số bài
    function totalLessons($course_id){
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total FROM lessons WHERE course_id=?
        ");
        $stmt->execute([$course_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // ✅ check đã học chưa
    function isCompleted($user_id, $lesson_id){
        $stmt = $this->conn->prepare("
            SELECT id FROM progress WHERE user_id=? AND lesson_id=?
        ");
        $stmt->execute([$user_id, $lesson_id]);

        return $stmt->rowCount() > 0;
    }

    // ========================================================
    // CÁC HÀM MỚI BỔ SUNG ĐỂ SỬA LỖI "THÊM VÀO TIẾN ĐỘ"
    // ========================================================

    // ✅ Thêm khóa học vào tiến độ (dành cho khóa học miễn phí)
    public function add($user_id, $course_id) {
        // 🔥 LƯU Ý QUAN TRỌNG: 
        // Bảng 'progress' của bạn ở trên đang lưu 'lesson_id' (tiến độ bài học).
        // Để lưu việc ĐĂNG KÝ/THÊM KHÓA HỌC, bạn nên dùng một bảng khác như 'user_courses' hoặc 'enrollments'.
        // Ở đây tôi tạm để tên bảng là 'user_courses', bạn hãy đổi lại cho khớp với CSDL thực tế nhé!
        
        $check = $this->conn->prepare("SELECT 1 FROM user_courses WHERE user_id = ? AND course_id = ?");
        $check->execute([$user_id, $course_id]);

        if($check->rowCount() == 0){
            $stmt = $this->conn->prepare("INSERT INTO user_courses (user_id, course_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $course_id]);
        }
    }

    // ✅ Kiểm tra xem user đã thêm khóa học này vào tiến độ chưa
    public function checkAdded($user_id, $course_id) {
        // Đã sửa $this->db thành $this->conn
        // Nhớ đổi tên bảng 'user_courses' cho khớp với hàm add ở trên
        $stmt = $this->conn->prepare("SELECT 1 FROM user_courses WHERE user_id = ? AND course_id = ? LIMIT 1");
        $stmt->execute([$user_id, $course_id]);
        
        // Nếu tìm thấy dữ liệu (rowCount > 0), trả về true
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}