<?php
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '/xampp/htdocs/models/user_model.php';
    $userModel = new userModel;
    if(isset($_POST['sessid']) && $_POST['sessid'] == session_id()) echo json_encode($_SESSION['idUser']);
    else {
        http_response_code(401);
    }
} else http_response_code(404);