<?php
require_once '/xampp/htdocs/models/post_model.php';
$postModel = new postModel;

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        function checkColumn($postModel) {
            foreach($_GET as $key => $value) {
                if(in_array($key, $postModel->columns)) {
                    echo json_encode($postModel->get_by_column($key, $value));
                    return true;
                    break;
                }    
            }
            return false;
        }
        if(isset($_GET['id'])) echo json_encode($postModel->get_by_id($_GET['id']));
        else if(checkColumn($postModel)) null;
        else echo json_encode($postModel->get_all());
        break;
    case 'POST':
        if(isset($_POST['contents']) && isset($_POST['title']) && isset($_POST['projectId']) && isset($_POST['authorId'])) {
            $queryRes = $postModel->create($_POST['contents'], $_POST['title'], $_POST['projectId'], $_POST['authorId']);
            if($queryRes) echo json_encode($queryRes);
            else http_response_code(500);
        } else http_response_code(400);
        break;
    case 'DELETE':
        parse_str(file_get_contents('php://input'), $_DELETE);
        if(isset($_DELETE['id'])) {
            $postModel->delete_by_id($_DELETE['id']);
            http_response_code(204);
        } else http_response_code(400);
        break;
    case 'PUT':
        parse_str(file_get_contents('php://input'), $_PUT);
        if(isset($_PUT['id']) && isset($_PUT['column']) && isset($_PUT['value'])) {
            $_PUTModel->update_by_id($_PUT['id'], $_PUT['column'], $_PUT['value']);
            http_response_code(204);
        } else http_response_code(400);
        break;
}