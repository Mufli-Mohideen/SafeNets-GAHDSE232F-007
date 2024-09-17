<?php

function secureSessionStart() {
    // Start the session if it is not already started
    if (session_status() === PHP_SESSION_NONE) {
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
        session_regenerate_id(true); // Regenerate the session ID
    }
}

function destroySession() {
    $_SESSION = [];
    session_destroy();
}
