<?php
require_once '/xampp/htdocs/models/user_model.php';
$userModel = new userModel;

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($userModel->get_by_column('isAdmin', 1));
}