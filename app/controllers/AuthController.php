<?php
// app/controllers/AuthController.php

require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../helpers/email_helper.php';
require_once __DIR__ . '/../models/Student.php';

class AuthController {
    private $studentModel;

    public function __construct($pdo) {
        $this->studentModel = new Student($pdo);
    }

    public function login($indexNumber) {
        // Use the read method to find the student by the index number
        $student = $this->studentModel->read($indexNumber);
        
        if ($student) {
            // If the student exists, send the verification code to their email
            $this->sendVerificationCode($student['email']);
            $_SESSION['login_index'] = $indexNumber;  // Store index number for verification later
            return true; // Successful initiation of login process
        } else {
            return false; // Student not found
        }
    }
    

    public function verifyLogin($inputCode) {
        // Verify if the code matches
        if (isset($_SESSION['verification_code']) && time() <= $_SESSION['code_expires']) {
            if ($inputCode == $_SESSION['verification_code']) {
                // Successful login, now authenticate the user
                $indexNumber = $_SESSION['login_index'];
                $student = $this->studentModel->read($indexNumber);

                if ($student) {
                    $_SESSION['student_id'] = $student['id'];
                    return true; // Authentication successful
                }
            }
        }

        return false; // Verification failed
    }

    private function sendVerificationCode($email) {
        $verificationCode = random_int(10000, 99999);  // Generate 5-digit code
        $_SESSION['verification_code'] = $verificationCode;
        $_SESSION['code_expires'] = time() + 300;  // Expires in 5 minutes

        // Use email helper to send the code
        sendEmail($email, "Your verification code", "Your code is: $verificationCode");
    }
}
