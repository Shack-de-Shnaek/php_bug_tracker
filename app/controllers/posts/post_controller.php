<?php
require_once '/xampp/htdocs/php_classes/Router.php';

$postRouter = new Router('/api/posts');
$postRouter->add('/author', function() {
    require_once __DIR__ . '/author.php';
});
$postRouter->add('', function() {
    require_once __DIR__ . '/index.php';
});
$postRouter->run();