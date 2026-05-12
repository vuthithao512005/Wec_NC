<?php
require_once "models/Lesson.php";
require_once "models/Progress.php";
require_once "models/Course.php";

class LessonController {
    private $lesson;
    private $course;

    public function __construct($db) {
        $this->lesson = new Lesson($db);
        $this->course = new Course($db);
    }

    public function list($course_id) {
        return $this->lesson->getByCourse($course_id);
    }

    public function get($id) {
        // 1. Lấy dữ liệu bài học hiện tại
        $lessonData = $this->lesson->getById($id);
        
        if (!$lessonData) {
            return false; // Trả về false nếu không tìm thấy bài học
        }

        // 2. Lấy thông tin khóa học chứa bài học này để check giá
        $course_id = $lessonData['course_id'];
        $courseData = $this->course->get($course_id);

        // 3. LOGIC BẢO VỆ NỘI DUNG (Content Protection)
        // Kiểm tra xem khóa học có tồn tại và có phải là khóa học mất phí không (price > 0)
        if ($courseData && $courseData['price'] > 0) {
            
            // 3.1. Kiểm tra trạng thái đăng nhập (Dùng mảng $_SESSION['user']['id'])
            if (!isset($_SESSION['user']['id'])) {
                echo "<script>
                        alert('Vui lòng đăng nhập để xem nội dung khóa học này!');
                        window.location.href = 'index.php?page=login';
                      </script>";
                exit();
            }

            $user_id = $_SESSION['user']['id'];

            // 3.2. Kiểm tra quyền sở hữu (Học viên đã mua khóa học chưa)
            $hasPurchased = $this->course->checkOwnership($user_id, $course_id);

            // 3.3. Kiểm tra xem có phải là Admin không (Admin được quyền xem mọi khóa học để kiểm thử)
            $isAdmin = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';

            // 3.4. Nếu CƯA MUA và KHÔNG PHẢI ADMIN -> Chặn và đẩy về trang danh sách khóa học
            if (!$hasPurchased && !$isAdmin) {
                echo "<script>
                        alert('Bạn chưa sở hữu khóa học này. Vui lòng thanh toán để mở khóa!');
                        window.location.href = 'index.php?page=courses';
                      </script>";
                exit();
            }
        }

        // 4. HỢP LỆ (Là khóa Free, hoặc Học viên đã mua, hoặc là Admin) -> Trả về dữ liệu bài học
        return $lessonData;
    }
}
?>
