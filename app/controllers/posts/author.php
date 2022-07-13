<?php
require_once '/xampp/htdocs/models/post_model.php';
$postModel = new postModel;

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['id'])) echo json_encode($postModel->get_author($_GET['id']));
    else http_response_code(400);
} else http_response_code(404);