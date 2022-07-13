<?php
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['email']) && isset($_POST['password'])) {
        require_once '/xampp/htdocs/models/user_model.php';
        $userModel = new userModel;
        $raw = $userModel->verify_login($_POST['email'], $_POST['password']);
        if(gettype($raw) == 'array') {
            if($raw === []) {
                echo "incorrect login information";
                http_response_code(401);
                session_destroy();
            }
            else {
                $raw = $raw[0];
                $_SESSION['idUser'] = $raw['idUser'];
                $_SESSION['email'] = $raw['email'];
                $_SESSION['isAdmin'] = $raw['isAdmin'];
                http_response_code(204);
            }
        }
        else {
            echo $raw;
            http_response_code(401);
        }
    }
}
else http_response_code(404);