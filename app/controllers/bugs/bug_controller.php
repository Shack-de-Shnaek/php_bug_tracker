<?php
require_once '/xampp/htdocs/php_classes/Router.php';

$bugRouter = new Router('/api/bugs');
$bugRouter->add('/submitter', function() {
    require_once __DIR__ . '/submitter.php';
});
$bugRouter->add('/fixer', function() {
    require_once __DIR__ . '/fixer.php';
});
$bugRouter->add('/fix', function() {
    require_once __DIR__ . '/fix.php';
});
$bugRouter->add('', function() {
    require_once __DIR__ . '/index.php';
});
$bugRouter->run();