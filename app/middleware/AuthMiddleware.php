<?php

require_once __DIR__ . '/../helpers/session_helper.php';

class AuthMiddleware {

    // This function checks if a user is authenticated
    public static function handle($requiredRole = null) {
        // Ensure the session is started securely
        secureSessionStart();

        // Check if user is logged in by verifying session
        if (!isset($_SESSION['user_id'])) {
            // If not logged in, redirect to the login page
            header('Location: /views/auth/login.php');
            exit();
        }

        // If a role is required (e.g., 'admin' or 'student'), check if the user has the correct role
        if ($requiredRole && $_SESSION['user_role'] !== $requiredRole) {
            // If the user's role does not match the required role, redirect to an unauthorized access page or home page
            header('Location: /views/errors/unauthorized.php');
            exit();
        }

        // If authenticated and the role is correct (if required), proceed to the requested page
    }
}
