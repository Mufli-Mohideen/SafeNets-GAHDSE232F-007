<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../helpers/encryption.php';

class OLResult {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create a new O/L result
    public function create($encryptedIndexNumber) {
        $stmt = $this->pdo->prepare("INSERT INTO ol_results (index_number) VALUES (?)");
        return $stmt->execute([$encryptedIndexNumber]);
    }

    // Update O/L result
    public function update($indexNumber, $scienceGrade, $mathGrade, $sinhalaGrade, $englishGrade, $historyGrade) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);

        $stmt = $this->pdo->prepare("UPDATE ol_results SET science_grade = ?, math_grade = ?, sinhala_grade = ?, english_grade = ?, history_grade = ? WHERE index_number = ?");
        return $stmt->execute([$scienceGrade, $mathGrade, $sinhalaGrade, $englishGrade, $historyGrade, $encryptedIndexNumber]);
    }

    // Fetch O/L result
    public function read($indexNumber) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);

        $stmt = $this->pdo->prepare("SELECT * FROM ol_results WHERE index_number = ?");
        $stmt->execute([$encryptedIndexNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getGrades($indexNumber) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);
        $stmt = $this->pdo->prepare("SELECT science_grade, math_grade, sinhala_grade, english_grade, history_grade FROM ol_results WHERE index_number = ?");
        $stmt->execute([$encryptedIndexNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete O/L result
    public function delete($indexNumber) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);

        $stmt = $this->pdo->prepare("DELETE FROM ol_results WHERE index_number = ?");
        return $stmt->execute([$encryptedIndexNumber]);
    }

    public function getByIndexNumber($indexNumber) {
        // Prepare the SQL statement to select O/L results
        $stmt = $this->pdo->prepare("SELECT * FROM ol_results");
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Loop through results and decrypt index numbers for comparison
        foreach ($results as $result) {
            $decryptedIndexNumber = EncryptionHelper::decrypt($result['index_number']);
            
            if ($decryptedIndexNumber === $indexNumber) {
                $result['index_number'] = $decryptedIndexNumber;
                return $result; // Return O/L result if the index matches
            }
        }
    
        return null; // Return null if no matching result is found
    }
    
    public function updateByIndexNumber($indexNumber, $data) {
        // Prepare the SQL statement to select O/L results
        $stmt = $this->pdo->prepare("SELECT * FROM ol_results");
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Loop through results and compare decrypted index numbers
        foreach ($results as $result) {
            $decryptedIndexNumber = EncryptionHelper::decrypt($result['index_number']);
            
            if ($decryptedIndexNumber === $indexNumber) {
                // Update the result fields (e.g., grades)
                $updateStmt = $this->pdo->prepare("UPDATE ol_results SET science_grade = :science_grade, math_grade = :math_grade, sinhala_grade = :sinhala_grade, english_grade = :english_grade, history_grade = :history_grade WHERE index_number = :index_number");
                $updateStmt->execute([
                    'science_grade' => $data['science_grade'],
                    'math_grade' => $data['math_grade'],
                    'sinhala_grade' => $data['sinhala_grade'],
                    'english_grade' => $data['english_grade'],
                    'history_grade' => $data['history_grade'],
                    'index_number' => $result['index_number']
                ]);
                return true; // Return true if the update was successful
            }
        }
    
        return false; // Return false if no matching result is found
    }
    

}
?>
