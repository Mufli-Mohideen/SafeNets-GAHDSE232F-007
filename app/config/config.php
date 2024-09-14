<?php
require_once __DIR__ . '/../../vendor/autoload.php'; 

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Define general configuration constants
define('APP_NAME', $_ENV['APP_NAME']);
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development'); 
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? 'false', FILTER_VALIDATE_BOOLEAN)); // true or false

// Define security settings
define('SESSION_NAME', $_ENV['SESSION_NAME'] ?? 'safenets_session');
define('SESSION_TIMEOUT', $_ENV['SESSION_TIMEOUT'] ?? (30 * 60)); // 30 minutes

// Define site URL
define('SITE_URL', $_ENV['SITE_URL']);

// Log settings
define('LOG_PATH', __DIR__ . '/../logs/error.log');
define('LOG_LEVEL', $_ENV['LOG_LEVEL'] ?? 'error'); // Log levels: emergency, alert, critical, error, warning, notice, info, debug

// Enable error logging if in development
if (APP_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    error_log("Application error logging enabled.", 3, LOG_PATH);
}
?>
