<?php
require_once '/xampp/htdocs/models/bug_model.php';
$bugModel = new bugModel;

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['id'])) echo json_encode($bugModel->get_submitter($_GET['id']));
    else http_response_code(400);
} else http_response_code(404);