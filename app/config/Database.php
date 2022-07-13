<?php
$user = 'root';
$pass = '';
$conString = 'mysql:host=localhost;port=3308;dbname=bug_tracker';
try {
    $database = new PDO($conString, $user, $pass);
} catch(PDOException $e) {
    echo $e;
    exit();
}