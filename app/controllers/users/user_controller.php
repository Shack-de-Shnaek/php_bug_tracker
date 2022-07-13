<?php
require_once '/xampp/htdocs/php_classes/Router.php';

$userRouter = new Router('/api/users');
$userRouter->add('/leaders', function() {
    require_once __DIR__ . '/leaders.php';
});
$userRouter->add('/projects', function() {
    require_once __DIR__ . '/projects.php';
});
$userRouter->add('/posts', function() {
    require_once __DIR__ . '/posts.php';
});
$userRouter->add('/current', function() {
    require_once __DIR__ . '/current_user.php';
});
$userRouter->add('/bugs/submitted', function() {
    require_once __DIR__ . '/submitted_bugs.php';
});
$userRouter->add('/bugs/fixed', function() {
    require_once __DIR__ . '/fixed_bugs.php';
});
$userRouter->add('', function() {
    require_once __DIR__ . '/index.php';
});
$userRouter->run();