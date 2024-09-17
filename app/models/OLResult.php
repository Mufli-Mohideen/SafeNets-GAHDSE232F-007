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
}
?>
