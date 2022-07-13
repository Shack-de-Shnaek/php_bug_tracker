<?php
session_set_cookie_params(60*60, '/');
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
require_once "./php_classes/Router.php";

$apiRouter = new Router('/api', true);
$apiRouter->add('/users', function() {
    require_once '/xampp/htdocs/controllers/users/user_controller.php';
});
$apiRouter->add('/bugs', function() { 
    require_once '/xampp/htdocs/controllers/bugs/bug_controller.php';
});
$apiRouter->add('/posts', function() { 
    require_once '/xampp/htdocs/controllers/posts/post_controller.php';
});
$apiRouter->add('/projects', function() {
    require_once '/xampp/htdocs/controllers/projects/project_controller.php';
});
$apiRouter->add('/login', function() {
    require_once '/xampp/htdocs/controllers/log_in/log_in_controller.php';
});
$apiRouter->add('/logout', function() {
    require_once '/xampp/htdocs/controllers/log_out/log_out_controller.php';
});
$apiRouter->add('/register', function() {
    require_once '/xampp/htdocs/controllers/register/register_controller.php';
});
$apiRouter->run();