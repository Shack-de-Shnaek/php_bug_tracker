<?php
require_once '/xampp/htdocs/models/project_model.php';
$projectModel = new projectModel;

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['id'])) echo json_encode($projectModel->get_posts($_GET['id']));
    else http_response_code(400);
}
