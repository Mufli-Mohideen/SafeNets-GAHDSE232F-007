<?php

require_once __DIR__ . '/../models/Grade5Result.php';
require_once __DIR__ . '/../models/OLResult.php';
require_once __DIR__ . '/../models/ALResult.php';
require_once __DIR__ . '/../helpers/session_helper.php';

class ResultController {
    private $grade5ResultModel;
    private $olResultModel;
    private $alResultModel;

    public function __construct($pdo) {
        $this->grade5ResultModel = new Grade5Result($pdo);
        $this->olResultModel = new OLResult($pdo);
        $this->alResultModel = new ALResult($pdo);
    }

    // View results by exam type and index number
    public function viewResults($indexNumber, $examType) {
        switch ($examType) {
            case 1: // Grade 5 Results
                return $this->grade5ResultModel->getMarks($indexNumber);
            case 2: // O/L Results
                return $this->olResultModel->getGrades($indexNumber);
            case 3: // A/L Results
                return $this->alResultModel->getGrades($indexNumber);
            default:
                return false;
        }
    }

    // Add or update results for a student
    public function addOrUpdateResults($indexNumber, $examType, $data) {
        // Determine which exam type is being processed
        switch ($examType) {
            case 1: // Grade 5 Results
                // Check if result already exists
                $existingGrade5Result = $this->grade5ResultModel->read($indexNumber);
                if ($existingGrade5Result) {
                    // If result exists, update it
                    return $this->grade5ResultModel->update($indexNumber, $data['marks']);
                } else {
                    // If result does not exist, create a new one
                    return $this->grade5ResultModel->create($indexNumber, $data['marks']);
                }
    
            case 2: // O/L Results
                // Check if result already exists
                $existingOlResult = $this->olResultModel->read($indexNumber);
                if ($existingOlResult) {
                    // If result exists, update it
                    return $this->olResultModel->update($indexNumber, $data['science_grade'], $data['math_grade'], $data['sinhala_grade'], $data['english_grade'], $data['history_grade']);
                } else {
                    // If result does not exist, create a new one
                    return $this->olResultModel->create($indexNumber, $data['science_grade'], $data['math_grade'], $data['sinhala_grade'], $data['english_grade'], $data['history_grade']);
                }
    
            case 3: // A/L Results
                // Check if result already exists
                $existingAlResult = $this->alResultModel->read($indexNumber);
                if ($existingAlResult) {
                    // If result exists, update it
                    return $this->alResultModel->update($indexNumber, $data['biology_grade'], $data['chemistry_grade'], $data['physics_grade']);
                } else {
                    // If result does not exist, create a new one
                    return $this->alResultModel->create($indexNumber, $data['biology_grade'], $data['chemistry_grade'], $data['physics_grade']);
                }
    
            default:
                // Invalid exam type
                return false;
        }
    }
    

    // Delete results by exam type and index number
    public function deleteResults($indexNumber, $examType) {
        switch ($examType) {
            case 1: // Grade 5 Results
                return $this->grade5ResultModel->delete($indexNumber);
            case 2: // O/L Results
                return $this->olResultModel->delete($indexNumber);
            case 3: // A/L Results
                return $this->alResultModel->delete($indexNumber);
            default:
                return false;
        }
    }
}
