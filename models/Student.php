<?php
class Student {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Create a new student
    public function create($data) {
        $sql = "INSERT INTO students (student_id, first_name, middle_name, last_name, suffix, 
                course, year_section, contact, username, password) 
                VALUES (:student_id, :first_name, :middle_name, :last_name, :suffix, 
                :course, :year_section, :contact, :username, :password)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        return $this->db->lastInsertId();
    }
    
    // Find student by ID
    public function findById($id) {
        $sql = "SELECT * FROM students WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    // Find student by username
    public function findByUsername($username) {
        $sql = "SELECT * FROM students WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }
    
    // Find student by student_id
    public function findByStudentId($studentId) {
        $sql = "SELECT * FROM students WHERE student_id = :student_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetch();
    }
    
    // Find student by name and contact
    public function findByNameAndContact($firstName, $lastName, $contact) {
        $sql = "SELECT * FROM students WHERE first_name = :first_name 
                AND last_name = :last_name AND contact = :contact";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'contact' => $contact
        ]);
        return $stmt->fetch();
    }
    
    // Update student information
    public function update($id, $data) {
        $sql = "UPDATE students SET 
                student_id = :student_id,
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                suffix = :suffix,
                course = :course,
                year_section = :year_section,
                contact = :contact,
                username = :username
                WHERE id = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    // Update password
    public function updatePassword($id, $hashedPassword) {
        $sql = "UPDATE students SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['password' => $hashedPassword, 'id' => $id]);
    }
    
    // Delete student
    public function delete($id) {
        $sql = "DELETE FROM students WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    // Get all students with optional search
    public function getAllWithSearch($search = '') {
        $sql = "SELECT * FROM students";
        
        if (!empty($search)) {
            $sql .= " WHERE first_name LIKE :search 
                     OR last_name LIKE :search 
                     OR student_id LIKE :search 
                     OR username LIKE :search";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['search' => "%$search%"]);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }
        
        return $stmt->fetchAll();
    }
    
    // Get all students
    public function getAll() {
        $sql = "SELECT * FROM students ORDER BY last_name, first_name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Count all students
    public function countAll() {
        $sql = "SELECT COUNT(*) as count FROM students";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['count'];
    }
    
    // Get all students for export
    public function getAllForExport() {
        $sql = "SELECT s.id, s.student_id, s.first_name, s.middle_name, s.last_name, 
                       s.suffix, s.course, s.year_section, s.contact, s.username,
                       CONCAT(a.barangay, ', ', a.municipality, ', ', a.province) as address
                FROM students s
                LEFT JOIN addresses a ON s.id = a.student_id
                ORDER BY s.last_name, s.first_name";
        
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll();
        
        // Format for export
        $exportData = [];
        foreach ($results as $row) {
            $exportData[] = [
                $row['id'],
                $row['student_id'],
                $row['first_name'],
                $row['middle_name'] ?? '',
                $row['last_name'],
                $row['suffix'] ?? '',
                $row['course'] ?? '',
                $row['year_section'] ?? '',
                $row['contact'] ?? '',
                $row['username'],
                $row['address'] ?? ''
            ];
        }
        
        return $exportData;
    }
    
    // Verify password
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    // Hash password
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    // Get student with address
    public function getWithAddress($id) {
        $sql = "SELECT s.*, a.barangay, a.municipality, a.province, a.full_address
                FROM students s
                LEFT JOIN addresses a ON s.id = a.student_id
                WHERE s.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    // Search students
    public function search($query) {
        $sql = "SELECT * FROM students 
                WHERE first_name LIKE :query 
                OR last_name LIKE :query 
                OR student_id LIKE :query
                OR username LIKE :query
                ORDER BY last_name, first_name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll();
    }
}
?>