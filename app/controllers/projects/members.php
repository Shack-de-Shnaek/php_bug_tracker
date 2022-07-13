<?php
require_once '/xampp/htdocs/models/project_model.php';
$projectModel = new projectModel;

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if(isset($_GET['id']) && isset($_GET['memberid'])) echo json_encode($projectModel->get_member_by_id($_GET['id'], $_GET['memberid']));
        else if(isset($_GET['id'])) echo json_encode($projectModel->get_members($_GET['id']));
        else http_response_code(400);
        break;
    case 'POST':
        if(isset($_POST['id']) && isset($_POST['email'])) {
            $queryRes = $projectModel->add_member_by_email($_POST['id'], $_POST['email']);
            if($queryRes) echo json_encode($queryRes);
            else http_response_code(500);
        } else http_response_code(400);
        break;
    case 'DELETE':
        parse_str(file_get_contents('php://input'), $_DELETE);
        if(isset($_DELETE['id']) && isset($_DELETE['memberid'])) {
            $projectModel->remove_member($_DELETE['id'], $_DELETE['memberid']);
            http_response_code(204);
        }
        else http_response_code(400);
        break;
}