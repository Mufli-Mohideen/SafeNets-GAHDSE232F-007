<?php

require_once __DIR__ . '/../app/controllers/StudentController.php';
require_once __DIR__ .'/../app/controllers/AuthController.php';
require_once __DIR__ .'/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/config/db_config.php';


$studentController = new StudentController($pdo);
$authController = new AuthController($pdo);
$adminController = new AdminController($pdo);

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

    case '/student/login':
        if ($requestMethod === 'POST') {
            // Handle signup form submission
            $authController->handleStudentLogin();
        } else {
            // Display the signup form (GET request)
            require __DIR__ . '/../app/views/auth/login.php';
        }
    break;

    case '/student/send-verification':
        if ($requestMethod === 'POST') {
            // Handle sending verification code
            $eid = $_POST['eid'];
            $indexNumber = $_POST['indexNumber'];
            echo $authController->sendVerification($eid, $indexNumber);
        }
    break;

    case '/student/result':
        if (isset($_SESSION['student']) && isset($_SESSION['examResults'])) {
            require __DIR__ . '/../app/views/student/results.php'; // Render the results page
        } else {
            echo "No session found, redirecting to login";
            require __DIR__ . '/../app/views/auth/login.php'; // Redirect to login if not authenticated
        }
        break;

        case '/student/logout':
            if ($requestMethod === 'POST') {
                session_unset();
                session_destroy();
                require __DIR__ . '/../app/views/home.php';
                exit();
            }
        break;
    
    

    case '/admin/login':
        if ($requestMethod === 'POST') {
            $adminController->adminLogin();
        } else {
            // Display the signup form (GET request)
            include __DIR__ . '/../app/views/admin/login.php';
        }
    break;

    case '/admin/login/dashboard':
        if ($requestMethod === 'POST') {
            $adminController->getStudentExamData();
        } else {
            // Display the signup form (GET request)
            include __DIR__ . '/../app/views/admin/dashboard.php';
        }
    break;

    case '/admin/login/update':
        if ($requestMethod === 'POST') {
            $adminController->updateResults();
        }
    break;
    

    case '/':
        include __DIR__ . '/../app/views/home.php';
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
    break;
}
