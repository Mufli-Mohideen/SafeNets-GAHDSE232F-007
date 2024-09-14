<?php
require_once __DIR__ . '/../../vendor/autoload.php'; 

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); 
$dotenv->load(); // Load environment variables

// Define database credentials as constants from $_ENV
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('PEPPER', $_ENV['PEPPER']);

// Use PDO for secure database interaction
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Database connection successful!";
} catch (PDOException $e) {
    error_log($e->getMessage(), 3, __DIR__ . '/logs/error.log');
    die("Could not connect to the database. Please try again later.");
}
