<?php
require_once '/xampp/htdocs/models/project_model.php';
$projectModel = new projectModel;

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        function checkColumn($projectModel) {
            foreach($_GET as $key => $value) {
                if(in_array($key, $projectModel->columns)) {
                    echo json_encode($projectModel->get_by_column($key, $value));
                    return true;
                    break;
                }    
            }
            return false;
        }
        if(isset($_GET['id'])) echo json_encode($projectModel->get_by_id($_GET['id']));
        else if(checkColumn($projectModel)) null;
        else echo json_encode($projectModel->get_all());
        break;
    case 'POST':
        if(isset($_POST['name']) && isset($_POST['description']) && isset($_POST['leaderId'])) {
            $op = $projectModel->create($_POST['name'], $_POST['description'], $_POST['leaderId']);
            if($op !== false) echo json_encode($op);
            else http_response_code(500);
        } else http_response_code(401);
        break;
    case 'DELETE':
        parse_str(file_get_contents('php://input'), $_DELETE);
        if(isset($_DELETE['id'])) {
            $projectModel->delete_by_id($_DELETE['id']);
            http_response_code(204);
        } else http_response_code(400);
        break;
    case 'PUT':
        parse_str(file_get_contents('php://input'), $_PUT);
        echo json_encode($projectModel->update_by_id($_PUT['id'], $_PUT['column'], $_PUT['value']));
        break;
}