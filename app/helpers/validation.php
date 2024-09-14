<?php

function validateNICOrPostalID($nic) {
    if (strlen($nic) == 10) {
        // Last character must be 'v' and first 9 characters must be digits
        return preg_match('/^[0-9]{9}[vV]$/', $nic);
    } elseif (strlen($nic) == 12) {
        // All 12 characters must be digits
        return preg_match('/^[0-9]{12}$/', $nic);
    }
    return false; // Invalid length
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateName($name) {
    return preg_match('/^[A-Z\s]+$/', $name);
}

// Validate index number (numeric)
function validateIndexNumber($index) {
    return preg_match('/^[0-9]+$/', $index);
}

// Validate a 5-digit verification code
function validateVerificationCode($code) {
    return preg_match('/^[0-9]{5}$/', $code);
}

// Validate exam marks (for Grade 5 results) - between 0 and 200
function validateGrade5Marks($marks) {
    return ($marks >= 0 && $marks <= 200);
}

// Validate O/L and A/L grades (must be 'A', 'B', 'C', 'S', or 'W')
function validateGrade($grade) {
    $validGrades = ['A', 'B', 'C', 'S', 'W'];
    return in_array($grade, $validGrades);
}

// General validation for any empty input
function validateNotEmpty($input) {
    return !empty(trim($input));
}

// Validate admin credentials (basic example, enhance this for actual use)
function validateAdminCredentials($username, $password) {
    // Check if username and password meet certain criteria (length, characters, etc.)
    if (strlen($username) >= 5 && strlen($password) >= 6) {
        return true;
    }
    return false;
}


?>