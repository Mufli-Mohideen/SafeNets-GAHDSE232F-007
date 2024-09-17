<?php

require_once __DIR__ . '/../app/controllers/StudentController.php';
require_once __DIR__ . '/../app/config/db_config.php';

$studentController = new StudentController($pdo);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Adjust for subdirectory if necessary
if (strpos($uri, '/safenets') === 0) {
    $uri = str_replace('/safenets/public', '', $uri);
}


// Define routing logic
switch ($uri) {
    case '/student/signup':
        if ($requestMethod === 'POST') {
            // Handle signup form submission
            $studentController->handleStudentSignup();
        } else {
            // Display the signup form (GET request)
            include __DIR__ . '/../app/views/auth/signup.php';
        }
        break;

    case '/':
        include __DIR__ . '/../app/views/home.php';
        break;

    default:
        // If no matching route is found, return a 404 response
        http_response_code(404);
        echo "404 Not Found";
        break;
}
