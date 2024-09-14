<?php

require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Grade5Result.php';
require_once __DIR__ . '/../models/OLResult.php';
require_once __DIR__ . '/../models/ALResult.php';
require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';


class AdminController {
    private $studentModel;
    private $grade5ResultModel;
    private $olResultModel;
    private $alResultModel;
    private $pdo;

    public function __construct($pdo) {
        $this->studentModel = new Student($pdo);
        $this->grade5ResultModel = new Grade5Result($pdo);
        $this->olResultModel = new OLResult($pdo);
        $this->alResultModel = new ALResult($pdo);
        $this->pdo = $pdo;
    }

    // Admin login function
    public function adminLogin($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if admin exists
        if ($admin) {
            $storedHashedPassword = $admin['password'];
            $storedSalt = $admin['salt'];

            // Re-hash the provided password using stored salt and compare
            $hashedPassword = hash('sha256', $storedSalt . $password . PEPPER);
            if ($hashedPassword === $storedHashedPassword) {
                // Authentication successful, set session variables
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                return true;
            }
        }
        
        // Authentication failed
        return false;
    }

    // Logout admin
    public function adminLogout() {
        // Clear session data for admin
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        session_destroy();
    }

    // Update existing student
    public function updateStudent($indexNumber, $nicPostalId, $fullName, $email) {
        //Check whether admin is accessing this function
        AuthMiddleware::handle('admin');
        $student = $this->studentModel->read($indexNumber);
        if (!$student) {
            return false;
        }
        return $this->studentModel->update($indexNumber, $nicPostalId, $fullName, $email);
    }

    // Delete student by index number
    public function deleteStudent($indexNumber) {
        AuthMiddleware::handle('admin');
        $student = $this->studentModel->read($indexNumber);
        if (!$student) {
            return false; // Student not found
        }
        return $this->studentModel->delete($indexNumber);
    }

    // View all students
    public function listAllStudents() {
        //Check whether admin is accessing this function
        AuthMiddleware::handle('admin');
        return $this->studentModel->getAllStudents();
    }

    // View specific student by index number
    public function viewStudent($indexNumber) {
        //Check whether admin is accessing this function
        AuthMiddleware::handle('admin');
        return $this->studentModel->read($indexNumber);
    }
}

