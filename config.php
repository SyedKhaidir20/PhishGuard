<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'phishing_simulator');

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'unitteknologimaklumatpsmza@gmail.com');
define('SMTP_PASS', 'gxpjodfsbbuutbcv');
define('SMTP_PORT', 587);
define('SMTP_FROM', 'admin@phisingsimulator.com');
define('SMTP_FROM_NAME', 'Phishing Simulator');


try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("PDO connection failed: " . $e->getMessage());
}


$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die("MySQLi connection failed: " . $mysqli->connect_error);
}

session_start();
?>
