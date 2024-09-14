<?php

// *! --Only the functionalities related to the Students are written in here--

require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Grade5Result.php';
require_once __DIR__ . '/../models/OLResult.php';
require_once __DIR__ . '/../models/ALResult.php';
require_once __DIR__ . '/../helpers/session_helper.php';

class StudentController {
    private $studentModel;
    private $grade5ResultModel;
    private $olResultModel;
    private $alResultModel;

    public function __construct($pdo) {
        $this->studentModel = new Student($pdo);
        $this->grade5ResultModel = new Grade5Result($pdo);
        $this->olResultModel = new OLResult($pdo);
        $this->alResultModel = new ALResult($pdo);
    }

    // View student profile
    public function viewProfile($indexNumber) {
        $student = $this->studentModel->read($indexNumber);

        if ($student) {
            return $student;
        } else {
            return false; 
        }
    }

    // View student results by exam type
    public function viewResults($indexNumber, $examType) {
        
        $student = $this->studentModel->read($indexNumber);
    
        if (!$student) {
            return false;
        }
    
        $results = [];
    
        switch ($examType) {
            case 1: 
                $results = $this->grade5ResultModel->getMarks($student['index_number']);
                break;
            case 2: 
                $results = $this->olResultModel->getGrades($student['index_number']);
                break;
            case 3: 
                $results = $this->alResultModel->getGrades($student['index_number']);
                break;
            default:
                return false; 
        }
    
        return $results; 
    }
    

    // Create new student registration
    public function registerStudent($eid, $nicPostalId, $fullName, $email) {
        return $this->studentModel->create($eid, $nicPostalId, $fullName, $email);
    }

}
