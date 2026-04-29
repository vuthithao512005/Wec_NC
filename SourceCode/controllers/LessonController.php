<?php
require_once "models/Lesson.php";
require_once "models/Progress.php";

class LessonController {
    private $lesson;

    public function __construct($db) {
        $this->lesson = new Lesson($db);
    }

    public function list($course_id) {
        return $this->lesson->getByCourse($course_id);
    }

    public function get($id) {
        return $this->lesson->getById($id);
    }
}