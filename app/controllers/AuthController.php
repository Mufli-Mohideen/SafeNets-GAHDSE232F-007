<?php
// app/controllers/AuthController.php
session_start(); // Start the session

require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../helpers/email_helper.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../helpers/email_helper.php';
require_once __DIR__ . '/../../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class AuthController {
    private $studentModel;

    public function __construct($pdo) {
        $this->studentModel = new Student($pdo);
    }


    public function sendVerification($eid, $indexNumber) {
        $student = $this->studentModel->readByIndexAndExam($indexNumber, $eid);
    
        // Check if a student was found
        if ($student) {
            // Prepare the email
            $to = $student['email'];
            $subject = "Your Verification Code";
            $verificationCode = rand(10000, 99999);
            $message = "Your verification code is: " . $verificationCode;
    
            $_SESSION['verification_code'] = $verificationCode; 
            $_SESSION['code_expires'] = time() + 300;
    
            // Send the email using the helper function
            if (sendEmail($to, $subject, $message)) {
                // Optionally, you can return a success response or message
                return "Verification code sent to your email.";
            } else {
                // Handle email sending failure
                return "Failed to send verification code.";
            }
        } else {
            // Handle case where no student was found
            return "Invalid index number or exam ID.";
        }
    }
    
    
    
    

    public function handleStudentLogin() {
        // Get POST data from the form
        $exam = $_POST["exam"] ?? null;
        $indexNumber = $_POST['indexNumber'] ?? null;
        $otp = $_POST['otp'] ?? null;

        $failedauth = 'Location: /safenets/public/student/login';
    
        // Check if index number and OTP are provided

        if (!$indexNumber || !$otp || !$exam) {
            $_SESSION['message'] = "Please complete all the fields before continuing!";
            header($failedauth); // Adjust the redirect as necessary
            exit();
        }
    
        // Check if OTP is set in the session
        if (!isset($_SESSION['verification_code']) || !isset($_SESSION['code_expires'])) {
            $_SESSION['message'] = "No OTP found or OTP has expired.";
            header($failedauth);
            exit();
        }
    
        // Check if the OTP has expired
        if (time() > $_SESSION['code_expires']) {
            unset($_SESSION['verification_code']);
            unset($_SESSION['code_expires']);
            $_SESSION['message'] = "The OTP has expired. Please request a new one.";
            header($failedauth);
            exit();
        }
    
        // Check if the OTP matches
        if ($_SESSION['verification_code'] != $otp) {
            $_SESSION['message'] = "Invalid OTP. Please try again.";
            header($failedauth);
            exit();
        }
    
        // If OTP matches and is still valid, perform login logic
        $student = $this->studentModel->readByIndexAndExam($indexNumber,$exam); // Assuming you have a method to get student details by index number
    
        if ($student) {
            unset($_SESSION['verification_code']);
            unset($_SESSION['code_expires']);
            
            $_SESSION['student'] = $student;
    
            header('Location: /safenets/public/');
            exit();
        } else {
            echo "Invalid index number."; // Debugging
        }
    }
    
    
    
}
