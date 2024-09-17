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
}
?>
