<?php
if($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '/xampp/htdocs/models/user_model.php';
    $userModel = new userModel;

    if(isset($_GET['id'])) echo json_encode($userModel->get_posts($_GET['id']));
    else if(isset($_GET['sessid'])) {
        if($_GET['sessid'] === session_id()) echo json_encode($userModel->get_posts($_SESSION['idUser']));
        else http_response_code(401);
    }
    else http_response_code(400);
}
