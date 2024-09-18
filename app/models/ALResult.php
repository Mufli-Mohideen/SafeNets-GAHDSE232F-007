<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../helpers/encryption.php';

class ALResult {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create a new AL result entry
    public function create($encryptedIndexNumber) {
        $stmt = $this->pdo->prepare("INSERT INTO al_results (index_number) VALUES (?)");
        return $stmt->execute([$encryptedIndexNumber]);
    }

    // Update AL result entry
    public function update($indexNumber, $biologyGrade, $chemistryGrade, $physicsGrade) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);

        $stmt = $this->pdo->prepare("UPDATE al_results SET biology_grade = ?, chemistry_grade = ?, physics_grade = ? WHERE index_number = ?");
        return $stmt->execute([$biologyGrade, $chemistryGrade, $physicsGrade, $encryptedIndexNumber]);
    }

    // Fetch AL result for a student
    public function read($indexNumber) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);

        $stmt = $this->pdo->prepare("SELECT * FROM al_results WHERE index_number = ?");
        $stmt->execute([$encryptedIndexNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getGrades($indexNumber) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);
        $stmt = $this->pdo->prepare("SELECT biology_grade, chemistry_grade, physics_grade FROM al_results WHERE index_number = ?");
        $stmt->execute([$encryptedIndexNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete AL result
    public function delete($indexNumber) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);

        $stmt = $this->pdo->prepare("DELETE FROM al_results WHERE index_number = ?");
        return $stmt->execute([$encryptedIndexNumber]);
    }
    public function getByIndexNumber($indexNumber) {
        // Prepare the SQL statement to select A/L results
        $stmt = $this->pdo->prepare("SELECT * FROM al_results");
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Loop through results and decrypt index numbers for comparison
        foreach ($results as $result) {
            $decryptedIndexNumber = EncryptionHelper::decrypt($result['index_number']);
            
            if ($decryptedIndexNumber === $indexNumber) {
                $result['index_number'] = $decryptedIndexNumber;
                return $result; // Return A/L result if the index matches
            }
        }
    
        return null; // Return null if no matching result is found
    }
    
    public function updateByIndexNumber($indexNumber, $data) {
        // Prepare the SQL statement to select A/L results
        $stmt = $this->pdo->prepare("SELECT * FROM al_results");
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Loop through results and compare decrypted index numbers
        foreach ($results as $result) {
            $decryptedIndexNumber = EncryptionHelper::decrypt($result['index_number']);
            
            if ($decryptedIndexNumber === $indexNumber) {
                // Update the result fields (e.g., grades)
                $updateStmt = $this->pdo->prepare("UPDATE al_results SET biology_grade = :biology_grade, chemistry_grade = :chemistry_grade, physics_grade = :physics_grade WHERE index_number = :index_number");
                $updateStmt->execute([
                    'biology_grade' => $data['biology_grade'],
                    'chemistry_grade' => $data['chemistry_grade'],
                    'physics_grade' => $data['physics_grade'],
                    'index_number' => $result['index_number']
                ]);
                return true; // Return true if the update was successful
            }
        }
    
        return false; // Return false if no matching result is found
    }
    
}
?>
