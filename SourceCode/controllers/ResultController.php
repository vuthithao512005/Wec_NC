<?php
require_once "models/Result.php";

class ResultController {
    private $conn;

    function __construct($db){
        $this->conn = $db;
    }

    function index(){
        $user_id = $_SESSION['user']['id'];
        return (new Result($this->conn))->getByUser($user_id);
    }
}