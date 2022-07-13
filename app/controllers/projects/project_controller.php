<?php
require_once '/xampp/htdocs/php_classes/Router.php';

$projectRouter = new Router('/api/projects');
$projectRouter->add('/members', function() {
    require_once __DIR__ . '/members.php';
});
$projectRouter->add('/posts', function() {
    require_once __DIR__ . '/posts.php';
});
$projectRouter->add('/bugs/fixed', function() {
    require_once __DIR__ . '/fixed_bugs.php';
});
$projectRouter->add('/bugs/unfixed', function() {
    require_once __DIR__ . '/unfixed_bugs.php';
});
$projectRouter->add('/bugs', function() {
    require_once __DIR__ . '/bugs.php';
});
$projectRouter->add('', function() {
    require_once __DIR__ . '/index.php';
});
$projectRouter->run();