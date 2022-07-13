<?php
require_once '/xampp/htdocs/models/user_model.php';
$userModel = new userModel;

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['id'])) echo json_encode($userModel->get_fixed_bugs($_GET['id']));
    else http_response_code(400);
}
