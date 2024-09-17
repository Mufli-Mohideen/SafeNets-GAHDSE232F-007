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
}
?>
