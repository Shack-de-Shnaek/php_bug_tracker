<?php
require_once '/xampp/htdocs/models/bug_model.php';
$bugModel = new bugModel;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['id']) && isset($_POST['fixerId'])) echo json_encode($bugModel->fix($_POST['id'], $_POST['fixerId']));
    else http_response_code(400);
} else http_response_code(404);
