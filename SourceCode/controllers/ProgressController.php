<?php
require_once "models/Progress.php";
require_once "models/Course.php";

class ProgressController {

    private $progress;
    private $course;

    public function __construct($db){
        $this->progress = new Progress($db);
        $this->course = new Course($db);

        // 1. Khởi động Session giống hệt CartController
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }

    // ===== THÊM VÀO TIẾN ĐỘ =====
    public function add(){
        // Kiểm tra đăng nhập
        if(!isset($_SESSION['user'])){
            header("Location:index.php?page=login");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $course_id = $_GET['id'] ?? 0;

        if($course_id){
            // Gọi hàm lưu vào DB (Đảm bảo model Progress của bạn có hàm add này nhé)
            // Nếu model của bạn dùng tên hàm khác (VD: insert, create...) thì đổi chữ 'add' thành tên đó
            $this->progress->add($user_id, $course_id); 
        }

        // Chuyển về trang tiến độ sau khi thêm thành công
        header("Location:index.php?page=progress");
        exit;
    }

    // ===== XEM DANH SÁCH TIẾN ĐỘ (INDEX) =====
    public function index(){
        // Kiểm tra đăng nhập
        if(!isset($_SESSION['user'])){
            header("Location:index.php?page=login");
            exit;
        }

        $user_id = $_SESSION['user']['id'];

        $courses = $this->course->all();

        $result = [];

        foreach($courses as $c){

            $completed = $this->progress->countCompleted($user_id, $c['id']);
            $total = $this->progress->totalLessons($c['id']);

            $percent = $total > 0 ? round(($completed / $total) * 100) : 0;

            $result[] = [
                'id' => $c['id'],           // Bổ sung ID để View tạo link "Tiếp tục học"
                'title' => $c['title'],
                'image' => $c['image'] ?? 'default.png', // Bổ sung Ảnh để View hiển thị
                'completed' => $completed,
                'total' => $total,
                'percent' => $percent,
                
                // MẸO: Vì View đang cần lọc khóa học, bạn nên truyền thêm trạng thái từ DB vào đây
                // Ví dụ: kiểm tra xem user này đã add khóa học này chưa
                'is_added' => $this->progress->checkAdded($user_id, $c['id']) ?? false,
                'is_paid' => false // Hoặc logic check thanh toán của bạn
            ];
        }

        return $result;
    }
}