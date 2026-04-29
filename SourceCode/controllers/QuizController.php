<?php
require_once "models/Quiz.php";
require_once "models/Progress.php";

class QuizController {
    private $quiz;
    private $conn;

    function __construct($conn){
        $this->conn = $conn;
        $this->quiz = new Quiz($conn);
    }

    // 🎯 Hiển thị quiz
    function show($lesson_id){
        return $this->quiz->getByLesson($lesson_id);
    }

    // 🎯 Xử lý submit
    function submit($lesson_id){
        $questions = $this->quiz->getByLesson($lesson_id);
        if (!$questions) return ['error' => 'Không tìm thấy câu hỏi'];

        $score = 0;
        $results = [];

        foreach($questions as $q){
            $qid = $q['id'];
            
            // --- XỬ LÝ SO SÁNH CHUẨN ---
            // 1. Lấy đáp án đúng từ DB: Xóa khoảng trắng, chuyển về chữ thường
            $correct = strtolower(trim($q['correct_answer']));
            
            // 2. Lấy đáp án người dùng: Nếu không có thì mặc định rỗng, chuẩn hóa như trên
            $userAnswerRaw = $_POST['q'][$qid] ?? '';
            $userAnswerNormalized = strtolower(trim($userAnswerRaw));

            // 3. Thực hiện so sánh
            $isCorrect = ($userAnswerNormalized === $correct && $userAnswerNormalized !== '');

            if($isCorrect){
                $score++;
            }

            // Lưu chi tiết để hiển thị lại kết quả ở View
            $results[] = [
                'question'  => $q['question'],
                'options'   => [
                    'a' => $q['option_a'],
                    'b' => $q['option_b'],
                    'c' => $q['option_c'],
                    'd' => $q['option_d']
                ],
                'correct'   => $q['correct_answer'], // Đáp án đúng gốc (A/B/C/D)
                'user'      => $userAnswerRaw,       // Đáp án User đã chọn gốc
                'isCorrect' => $isCorrect
            ];
        }

        // 💾 Lưu điểm vào CSDL
        $user_id = $_SESSION['user']['id'];
        $this->quiz->saveResult($user_id, $lesson_id, $score);

        // 🔥 Đánh dấu đã hoàn thành bài học
        $progress = new Progress($this->conn);
        $progress->markCompleted($user_id, $lesson_id);

        // 🎯 Trả về dữ liệu cho View
        return [
            'score'     => $score,
            'total'     => count($questions),
            'details'   => $results,
            'lesson_id' => $lesson_id
        ];
    }
}