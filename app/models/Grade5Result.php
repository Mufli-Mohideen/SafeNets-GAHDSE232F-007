<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../helpers/encryption.php';

class Grade5Result {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create new Grade 5 result
    public function create($encryptedIndexNumber) {
        $stmt = $this->pdo->prepare("INSERT INTO grade5_results (index_number) VALUES (?)");
        return $stmt->execute([$encryptedIndexNumber]);
    }

    // Update Grade 5 result
    public function update($indexNumber, $marks) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);

        $stmt = $this->pdo->prepare("UPDATE grade5_results SET marks = ? WHERE index_number = ?");
        return $stmt->execute([$marks, $encryptedIndexNumber]);
    }

    // Fetch Grade 5 result
    public function read($indexNumber) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);

        $stmt = $this->pdo->prepare("SELECT * FROM grade5_results WHERE index_number = ?");
        $stmt->execute([$encryptedIndexNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
   
    public function getMarks($indexNumber) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);
        $stmt = $this->pdo->prepare("SELECT marks FROM grade5_results WHERE index_number = ?");
        $stmt->execute([$encryptedIndexNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete Grade 5 result
    public function delete($indexNumber) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);

        $stmt = $this->pdo->prepare("DELETE FROM grade5_results WHERE index_number = ?");
        return $stmt->execute([$encryptedIndexNumber]);
    }

    public function getByIndexNumber($indexNumber) {
        // Prepare the SQL statement to select Grade 5 results
        $stmt = $this->pdo->prepare("SELECT * FROM grade5_results");
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Loop through results and decrypt index numbers for comparison
        foreach ($results as $result) {
            $decryptedIndexNumber = EncryptionHelper::decrypt($result['index_number']);
            
            if ($decryptedIndexNumber === $indexNumber) {
                $result['index_number'] = $decryptedIndexNumber;
                return $result; // Return Grade 5 result if the index matches
            }
        }
    
        return null; // Return null if no matching result is found
    }

    public function updateByIndexNumber($indexNumber, $data) {
        // Prepare the SQL statement to select Grade 5 results
        $stmt = $this->pdo->prepare("SELECT * FROM grade5_results");
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Loop through results and compare decrypted index numbers
        foreach ($results as $result) {
            $decryptedIndexNumber = EncryptionHelper::decrypt($result['index_number']);
            
            if ($decryptedIndexNumber === $indexNumber) {
                // Update the result fields (e.g., marks)
                $updateStmt = $this->pdo->prepare("UPDATE grade5_results SET marks = :marks WHERE index_number = :index_number");
                $updateStmt->execute(['marks' => $data['marks'], 'index_number' => $result['index_number']]);
                return true; // Return true if the update was successful
            }
        }
    
        return false; // Return false if no matching result is found
    }
    
     
}
?>
