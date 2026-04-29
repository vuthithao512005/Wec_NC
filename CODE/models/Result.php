<?php
class Result {
    private $conn;

    function __construct($db){
        $this->conn = $db;
    }

    // 💾 Lưu điểm
    function save($user_id,$lesson_id,$score){
        $stmt = $this->conn->prepare("
            INSERT INTO results(user_id,lesson_id,score)
            VALUES(?,?,?)
        ");
        $stmt->execute([$user_id,$lesson_id,$score]);
    }

    // 📊 Lấy kết quả theo user
    function getByUser($user_id){
        $stmt = $this->conn->prepare("
            SELECT * FROM results WHERE user_id=?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}