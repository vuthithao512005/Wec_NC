<?php
// Sử dụng đường dẫn tuyệt đối để tránh lỗi "Class not found"
require_once dirname(__DIR__) . '/models/Lesson.php';
require_once dirname(__DIR__) . '/models/Quiz.php';
require_once dirname(__DIR__) . '/models/Course.php';

class AdminQuizController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function index() {
        $lessonModel = new Lesson($this->conn);
        $quizModel = new Quiz($this->conn);
        $courseModel = new Course($this->conn);

        // --- 1. XỬ LÝ HÀNH ĐỘNG THÊM / SỬA / IMPORT (POST) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // A. Cập nhật câu hỏi
            if (isset($_POST['update_question'])) {
                $id = $_POST['id'];
                $data = [
                    'lesson_id'      => $_POST['lesson_id'],
                    'question'       => $_POST['question'],
                    'option_a'       => $_POST['option_a'],
                    'option_b'       => $_POST['option_b'],
                    'option_c'       => $_POST['option_c'],
                    'option_d'       => $_POST['option_d'],
                    'correct_answer' => $_POST['is_correct'] // is_correct lấy từ name thẻ input radio
                ];
                if ($quizModel->update($id, $data)) {
                    $_SESSION['success'] = "Đã cập nhật câu hỏi thành công!";
                }
            } 
            
            // B. Thêm mới câu hỏi thủ công
            elseif (isset($_POST['save_question'])) {
                $data = [
                    'lesson_id'      => $_POST['lesson_id'],
                    'question'       => $_POST['question'],
                    'option_a'       => $_POST['option_a'],
                    'option_b'       => $_POST['option_b'],
                    'option_c'       => $_POST['option_c'],
                    'option_d'       => $_POST['option_d'],
                    'correct_answer' => $_POST['is_correct']
                ];
                if ($quizModel->create($data)) {
                    $_SESSION['success'] = "Đã thêm câu hỏi mới!";
                }
            } 
            
            // C. Import Excel (CSV)
            elseif (isset($_POST['import_excel'])) {
                if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
                    $file = $_FILES['excel_file']['tmp_name'];
                    $handle = fopen($file, "r");
                    fgetcsv($handle); // Bỏ qua dòng tiêu đề

                    $count = 0;
                    while (($row_data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        // Chống lỗi font UTF-8
                        $row = array_map(function($item) {
                            return mb_convert_encoding($item, 'UTF-8', 'UTF-8');
                        }, $row_data);

                        if (count($row) >= 7) {
                            $insertData = [
                                'question'       => $row[0],
                                'option_a'       => $row[1],
                                'option_b'       => $row[2],
                                'option_c'       => $row[3],
                                'option_d'       => $row[4],
                                'correct_answer' => $row[5],
                                'lesson_id'      => intval($row[6])
                            ];
                            if ($quizModel->create($insertData)) $count++;
                        }
                    }
                    fclose($handle);
                    $_SESSION['success'] = "Đã nhập thành công $count câu hỏi!";
                }
            }

            // Load lại trang để tránh lỗi Form Resubmission
            header("Location: index.php?page=admin_quiz");
            exit;
        }

        // --- 2. XỬ LÝ XÓA (GET) ---
        if (isset($_GET['delete'])) {
            if ($quizModel->delete($_GET['delete'])) {
                $_SESSION['success'] = "Đã xóa câu hỏi!";
            }
            header("Location: index.php?page=admin_quiz");
            exit;
        }

        // --- 3. LỌC VÀ CHUẨN BỊ DỮ LIỆU HIỂN THỊ ---
        
        // Bắt giá trị lọc từ URL
        $filter_lesson_id = $_GET['lesson_id'] ?? null;
        $filter_course_id = $_GET['course_id'] ?? null;

        $courses = $courseModel->all();
        $all_lessons = $lessonModel->all(); 

        $grouped_data = [];
        foreach ($courses as $course) {
            $course_id = $course['id'];
            
            //Lọc khóa học
            if (!empty($filter_course_id) && $course_id != $filter_course_id) {
                continue;
            }
            // Lấy câu hỏi theo khóa học + Áp dụng bộ lọc bài học
            $questions = $quizModel->getByCourseWithFilter($course_id, $filter_lesson_id);
            
            // Nếu đang dùng bộ lọc, khóa học nào không chứa câu hỏi của bài học đó thì ẩn đi cho gọn
            if (!empty($filter_lesson_id) && empty($questions)) {
                continue; 
            }

            $grouped_data[$course_id] = [
                'course_title' => $course['title'],
                'questions'    => $questions 
            ];
        }

        // Gọi View
        $view = "views/admin/quiz.php";
        $current_page = 'admin_quiz';
        include "views/layout/admin_layout.php";
    }
}
?>