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
    public function adminLogin() {
        $failedauth = 'Location: /safenets/public/admin/login';
        // Set limits
        $maxAttempts = 5; // Maximum number of allowed attempts
        $lockoutTime = 300; // Lockout time in seconds (5 minutes)
    
        // Initialize session variables if they don't exist
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = null; // Lockout time starts as null
        }
    
        // Check if user is currently locked out
        if ($_SESSION['lockout_time'] !== null && time() < $_SESSION['lockout_time']) {
            $remainingTime = $_SESSION['lockout_time'] - time();
            $_SESSION['message'] = "Too many failed attempts. Please try again in " . ceil($remainingTime) . " seconds.";
            header($failedauth);
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;
    
            // Validate inputs
            if (empty($username) || empty($password)) {
                $_SESSION['message'] = "Please fill in all fields.";
                header($failedauth);
                exit();
            }
    
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
                    // Authentication successful, reset attempts and lockout time
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['login_attempts'] = 0; // Reset attempts on successful login
                    $_SESSION['lockout_time'] = null; // Reset lockout time
                    header('Location: /safenets/public/admin/login/dashboard');
                    exit();
                }
            }
    
            // Authentication failed
            $_SESSION['login_attempts']++;
    
            if ($_SESSION['login_attempts'] >= $maxAttempts) {
                // Set lockout time
                $_SESSION['lockout_time'] = time() + $lockoutTime;
                $_SESSION['message'] = "Too many failed attempts. Please try again later.";
            } else {
                $_SESSION['message'] = "Invalid username or password. Attempt " . $_SESSION['login_attempts'] . " of " . $maxAttempts . ".";
            }
    
            header($failedauth);
            exit();
        }
    }
    
    public function getStudentExamData() {
        // Make sure the request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the raw input data and decode JSON
            $inputData = json_decode(file_get_contents('php://input'), true);
    
            // Validate the received data
            if (!isset($inputData['indexNumber']) || !isset($inputData['exam'])) {
                echo json_encode(['error' => 'Missing required fields.']);
                exit();
            }
    
            $indexNumber = $inputData['indexNumber'];
            $exam = $inputData['exam'];
    
            // Fetch student details
            $student = $this->studentModel->readByIndex($indexNumber);
            if (!$student) {
                // Return a simple error message as JSON
                echo json_encode(['error' => 'Student not found.']);
                exit();
            }
    
            // Fetch exam results based on the exam type
            $result = null;
            if ($exam == '1') {
                $result = $this->grade5ResultModel->getByIndexNumber($indexNumber);
            } else if ($exam == '2') {
                $result = $this->olResultModel->getByIndexNumber($indexNumber);
            } else if ($exam == '3') {
                $result = $this->alResultModel->getByIndexNumber($indexNumber);
            }
    
            if ($result) {
                // Return student and exam results as JSON
                echo json_encode([
                    'student' => $student,
                    'result' => $result
                ]);
            } else {
                echo json_encode(['error' => 'Exam results not found.']);
            }
    
            exit();
        } else {
            // Handle non-POST requests
            http_response_code(405); // Method Not Allowed
            echo json_encode(['error' => 'Method not allowed.']);
        }
    }
    
    public function updateResults() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true); // Get JSON data
            $indexNumber = $data['indexNumber'];
            $exam = $data['exam'];
            $results = $data['results'];
    
            // Validate input data (add your own validation logic)
            if (empty($indexNumber) || empty($exam) || empty($results)) {
                echo json_encode(['error' => 'Invalid input data.']);
                return;
            }
    
            // Update exam results based on the exam type
            $success = false;
            if ($exam == '1') {
                $success = $this->grade5ResultModel->updateByIndexNumber($indexNumber, $results['marks']);
            } else if ($exam == '2') {
                $success = $this->olResultModel->updateByIndexNumber($indexNumber, $results);
            } else if ($exam == '3') {
                $success = $this->alResultModel->updateByIndexNumber($indexNumber, $results);
            }
    
            if ($success) {
                echo json_encode(['message' => 'Results updated successfully.']);
            } else {
                echo json_encode(['error' => 'Failed to update results.']);
            }
    
            exit();
        } else {
            // Handle non-POST requests
            http_response_code(405); // Method Not Allowed
            echo json_encode(['error' => 'Method not allowed.']);
        }
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

