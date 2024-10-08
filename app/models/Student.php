<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../helpers/encryption.php';

class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Generate a unique examination index number based on the exam type
    private function generateIndexNumber($eid) {
        $prefix = '';
        switch ($eid) {
            case 1:
                $prefix = '2';  // Starts with 2
                break;
            case 2:
                $prefix = '8';  // Starts with 8
                break;
            case 3:
                $prefix = '5';  // Starts with 5
                break;
            default:
                throw new Exception('Invalid exam type');
                
        }

        // Generate the rest of the digits randomly
        $randomDigits = str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT); 
        return $prefix . $randomDigits;
    }

    // Register a new student
    public function create($eid, $nicPostalId, $fullName, $email) {
        // Encrypt sensitive data
        $encryptedNicPostalId = EncryptionHelper::encrypt($nicPostalId);
        $encryptedFullName = EncryptionHelper::encrypt($fullName);
        $encryptedEmail = EncryptionHelper::encrypt($email);
    
        // Generate index number
        $indexNumber = $this->generateIndexNumber($eid);
        
        // Encrypt the index number once and store it
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);
    
        // Insert into the database
        $stmt = $this->pdo->prepare("INSERT INTO students (eid, nic_or_postal_id, full_name, email, index_number) VALUES (?, ?, ?, ?, ?)");
        $isStudentInserted = $stmt->execute([$eid, $encryptedNicPostalId, $encryptedFullName, $encryptedEmail, $encryptedIndexNumber]);
    
        if ($isStudentInserted) {
            // Return the encrypted index number for further use
            return $encryptedIndexNumber;
        }
    
        return false; // Return false if insertion fails
    }
    

    // Update student data (for admin use)
    public function update($indexNumber, $nicPostalId, $fullName, $email) {
        // Encrypt sensitive data
        $encryptedNicPostalId = EncryptionHelper::encrypt($nicPostalId);
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);
        $encryptedFullName = EncryptionHelper::encrypt($fullName);
        $encryptedEmail = EncryptionHelper::encrypt($email);



        // Update the student in the database
        $stmt = $this->pdo->prepare("UPDATE students SET nic_or_postal_id = ?, full_name = ?, email = ?, updated_at = NOW() WHERE index_number = ?");
        return $stmt->execute([$encryptedNicPostalId, $encryptedFullName, $encryptedEmail, $encryptedIndexNumber]);
    }

    // Delete a student (for admin use)
    public function delete($indexNumber) {
        // Delete student record from the database
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);
        $stmt = $this->pdo->prepare("DELETE FROM students WHERE index_number = ?");
        return $stmt->execute([$encryptedIndexNumber]);
    }

    // Read student data (for admin use)
    public function read($indexNumber) {
        $encryptedIndexNumber = EncryptionHelper::encrypt($indexNumber);
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE index_number = ?");
        $stmt->execute([$encryptedIndexNumber]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Decrypt sensitive data before returning
        if ($student) {
            $student['nic_or_postal_id'] = EncryptionHelper::decrypt($student['nic_or_postal_id']);
            $student['full_name'] = EncryptionHelper::decrypt($student['full_name']);

            $student['email'] = EncryptionHelper::decrypt($student['email']);
            $student['index_number'] = EncryptionHelper::decrypt($student['index_number']);
        }
        return $student;
    }

    // Fetch all students
    public function getAllStudents() {
        $stmt = $this->pdo->prepare("SELECT * FROM students");
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Decrypt sensitive data for each student
        foreach ($students as &$student) {
            $student['nic_or_postal_id'] = EncryptionHelper::decrypt($student['nic_or_postal_id']);
            $student['full_name'] = EncryptionHelper::decrypt($student['full_name']);
            $student['email'] = EncryptionHelper::decrypt($student['email']);
            $student['index_number'] = EncryptionHelper::decrypt($student['index_number']);
        }
        return $students;
    }
    
    public function doesIdExist($id) {
        // Prepare the SQL query to fetch all encrypted IDs
        $sql = "SELECT nic_or_postal_id FROM students";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    
        // Fetch all encrypted IDs from the database
        $encryptedIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
        // Loop through each encrypted ID, decrypt it, and compare
        foreach ($encryptedIds as $encryptedId) {
            // Decrypt the ID
            $decryptedId = EncryptionHelper::decrypt($encryptedId);
    
            // Check if the decrypted ID matches the submitted ID
            if ($decryptedId === $id) {
                return true; // ID exists
            }
        }
    
        // If no match is found, return false
        return false;
    }

    public function getIndexNumber($nicPostalId) {
        // Retrieve all students from the database
        $stmt = $this->pdo->query("SELECT index_number, nic_or_postal_id FROM students");
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Iterate through all records, decrypt and compare the NIC/Postal ID
        foreach ($students as $student) {
            $decryptedNicPostalId = EncryptionHelper::decrypt($student['nic_or_postal_id']);
    
            if ($decryptedNicPostalId === $nicPostalId) {
                // If NIC or Postal ID matches, decrypt and return the index number
                return EncryptionHelper::decrypt($student['index_number']);
            }
        }
    
        return null; // Return null if no matching NIC/Postal ID is found
    }

    public function readByIndexAndExam($indexNumber, $exam) {
        // Prepare the SQL statement to select the student based on exam
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE eid = :exam");
        
        // Bind the exam parameter
        $stmt->bindParam(':exam', $exam);
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all student records for the specified exam
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Loop through students and decrypt index numbers for comparison
        foreach ($students as $student) {
            // Decrypt the index number
            $decryptedIndexNumber = EncryptionHelper::decrypt($student['index_number']);
            
            if ($decryptedIndexNumber === $indexNumber) {
                $student['full_name'] = EncryptionHelper::decrypt($student['full_name']);
                $student['email'] = EncryptionHelper::decrypt($student['email']);
                $student['nic_or_postal_id'] = EncryptionHelper::decrypt($student['nic_or_postal_id']);
                $student['index_number'] = $decryptedIndexNumber;
                
                return $student; 
            }
        }
    
        return null;
    }

    public function readByIndex($indexNumber) {
        // Prepare the SQL statement to select all students
        $stmt = $this->pdo->prepare("SELECT * FROM students");
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all student records
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Loop through students and decrypt index numbers for comparison
        foreach ($students as $student) {
            // Decrypt the index number
            $decryptedIndexNumber = EncryptionHelper::decrypt($student['index_number']);
            
            // Compare decrypted index number with the input
            if ($decryptedIndexNumber === $indexNumber) {
                // Decrypt other student details before returning
                $student['nic_or_postal_id'] = EncryptionHelper::decrypt($student['nic_or_postal_id']);
                $student['full_name'] = EncryptionHelper::decrypt($student['full_name']);
                $student['email'] = EncryptionHelper::decrypt($student['email']);
                $student['index_number'] = $decryptedIndexNumber;
    
                return $student; // Return the student record if the index matches
            }
        }
    
        return null; // Return null if no matching student is found
    }
    
    
}
