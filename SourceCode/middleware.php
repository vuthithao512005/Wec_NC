<?php

function checkLogin(){
    if(!isset($_SESSION['user'])){
        header("Location: index.php?page=login");
        exit;
    }
}

function checkAdmin(){
    checkLogin();

    if($_SESSION['user']['role'] != 'admin'){
        header("Location: index.php?page=courses");
        exit;
    }
}

function checkUser(){
    checkLogin();

    if($_SESSION['user']['role'] != 'user'){
        header("Location: index.php?page=admin");
        exit;
    }
}

