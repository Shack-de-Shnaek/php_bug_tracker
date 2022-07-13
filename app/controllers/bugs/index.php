<?php
require_once '/xampp/htdocs/models/bug_model.php';
$bugModel = new bugModel;

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        function checkColumn($bugModel) {
            foreach($_GET as $key => $value) {
                if(in_array($key, $bugModel->columns)) {
                    echo json_encode($bugModel->get_by_column($key, $value));
                    return true;
                    break;
                }    
            }
            return false;
        }
        if(isset($_GET['id'])) echo json_encode($bugModel->get_by_id($_GET['id']));
        else if(checkColumn($bugModel)) null;
        else echo json_encode($bugModel->get_all());
        break;
    case 'POST':
        if(isset($_POST['name']) && isset($_POST['description']) && isset($_POST['severity']) && isset($_POST['projectId']) && isset($_POST['submitterId'])) {
            $queryRes = $bugModel->create($_POST['name'], $_POST['description'], $_POST['severity'], $_POST['projectId'], $_POST['submitterId']);
            if($queryRes) echo json_encode($queryRes);
            else http_response_code(500);
        } else http_response_code(400);
        break;
    case 'DELETE':
        parse_str(file_get_contents('php://input'), $_DELETE);
        if(isset($_DELETE['id'])) {
            if($bugModel->delete_by_id($_DELETE['id'])) http_response_code(204);
            else http_response_code(500);
        } else http_response_code(400);
        break;
    case 'PUT':
        parse_str(file_get_contents('php://input'), $_PUT);
        if(isset($_PUT['id']) && isset($_PUT['column']) && isset($_PUT['value'])) {
            if($bugModel->update_by_id($_PUT['id'], $_PUT['column'], $_PUT['value'])) http_response_code(204);
            else http_response_code(500);
        } else {
            print_r($_PUT);
            http_response_code(400);
        }
        break;
}