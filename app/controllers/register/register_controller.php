<?php
session_set_cookie_params(30*60, '/');
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '/xampp/htdocs/models/user_model.php';
    $userModel = new userModel;
    foreach(array_slice($userModel->columns, 1) as $key => $value) {
        if(isset($_POST[$key])) continue;
        else {
            break;
            exit();
        }
    }
    $raw = $userModel->create($_POST['isAdmin'], $_POST['name'], $_POST['surname'], $_POST['email'], $_POST['password']);
    if($raw) {
        $verify = $userModel->verify_login($_POST['email'], $_POST['password']);
        if(gettype($verify) == 'array') {
            if($verify === []) {
                echo "incorrect login information";
                http_response_code(401);
                session_destroy();
            }
            else {
                $verify = $verify[0];
                $_SESSION['idUser'] = $verify['idUser'];
                $_SESSION['email'] = $verify['email'];
                $_SESSION['isAdmin'] = $verify['isAdmin'];
                http_response_code(201);
            }
        }
    }
    else http_response_code(408);
} else http_response_code(404);