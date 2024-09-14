<?php

function secureSessionStart() {
    if (session_status() === PHP_SESSION_NONE) {
        // Prevent session hijacking by regenerating the session ID
        session_regenerate_id(true);

        // Set secure session cookie settings
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params([
            'lifetime' => $cookieParams['lifetime'],
            'path' => $cookieParams['path'],
            'domain' => $cookieParams['domain'],
            'secure' => true,  // Ensures the cookie is sent over HTTPS
            'httponly' => true, // Prevents JavaScript access to session cookies
            'samesite' => 'Strict'  // Prevents CSRF attacks
        ]);

        session_start(); // Start the session
    }
}

function destroySession() {
    $_SESSION = [];
    session_destroy();
}
