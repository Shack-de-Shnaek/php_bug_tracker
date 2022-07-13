<?php
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_SESSION['email']) && $_SESSION['email'] == $_POST['email'] && session_id() == $_POST['sessid']) {
        session_destroy();
        http_response_code(204);
    } else http_response_code(400);
    
} else http_response_code(401);