<?php
session_start();
require_once '/xampp/htdocs/models/user_model.php';
$userModel = new userModel;

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        function checkColumn($userModel) {
            foreach($_GET as $key => $value) {
                if(in_array($key, $userModel->columns)) {
                    echo json_encode($userModel->get_by_column($key, $value));
                    return true;
                    break;
                }    
            }
            return false;
        }
        if(isset($_GET['id'])) echo json_encode($userModel->get_by_id($_GET['id']));
        else if(checkColumn($userModel)) null;
        else if(isset($_GET['sessid'])) echo json_encode($userModel->get_by_id($_SESSION['idUser']));
        else echo json_encode($userModel->get_all());
        break;
    case 'POST':
        $userModel = new userModel;
        foreach(array_slice($userModel->columns, 1) as $key => $value) {
            if(isset($_POST[$key])) continue;
            else {
                break;
                exit();
            }
        }
        $raw = $userModel->create($_POST['isAdmin'], $_POST['name'], $_POST['surname'], $_POST['email'], $_POST['password']);
        if($raw === []) http_response_code(201);
        else http_response_code(408);
        break;
    case 'DELETE':
        parse_str(file_get_contents('php://input'), $_DELETE);
        $userModel->delete_by_id($_DELETE['id']);
        http_response_code(204);
        break;
    case 'PUT':
        parse_str(file_get_contents('php://input'), $_PUT);
        if(isset($_PUT['id']) && isset($_PUT['column']) && isset($_PUT['value'])) {
            $userModel->update_by_id($_PUT['id'], $_PUT['column'], $_PUT['value']);
            http_response_code(204);
        }
        else if(isset($_PUT['sessid']) && isset($_PUT['column']) && isset($_PUT['value'])) {
            if($_PUT['sessid'] === session_id()) {
                $userModel->update_by_id($_SESSION['idUser'], $_PUT['column'], $_PUT['value']);
                http_response_code(204);
            }
            else http_response_code(401);
        } else http_response_code(404);
        break;
}