<?php
class Address {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Create address
    public function create($data) {
        // Build full address
        $parts = [];
        if (!empty($data['barangay'])) $parts[] = $data['barangay'];
        if (!empty($data['municipality'])) $parts[] = $data['municipality'];
        if (!empty($data['province'])) $parts[] = $data['province'];
        $fullAddress = implode(', ', $parts);
        
        $sql = "INSERT INTO addresses (barangay, municipality, province, full_address, student_id) 
                VALUES (:barangay, :municipality, :province, :full_address, :student_id)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'barangay' => $data['barangay'] ?? '',
            'municipality' => $data['municipality'] ?? '',
            'province' => $data['province'] ?? '',
            'full_address' => $fullAddress,
            'student_id' => $data['student_id']
        ]);
    }
    
    // Find address by ID
    public function findById($id) {
        $sql = "SELECT * FROM addresses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    // Find address by student ID
    public function findByStudentId($studentId) {
        $sql = "SELECT * FROM addresses WHERE student_id = :student_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetch();
    }
    
    // Update address
    public function update($id, $data) {
        // Build full address
        $parts = [];
        if (!empty($data['barangay'])) $parts[] = $data['barangay'];
        if (!empty($data['municipality'])) $parts[] = $data['municipality'];
        if (!empty($data['province'])) $parts[] = $data['province'];
        $fullAddress = implode(', ', $parts);
        
        $sql = "UPDATE addresses SET 
                barangay = :barangay,
                municipality = :municipality,
                province = :province,
                full_address = :full_address
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'barangay' => $data['barangay'] ?? '',
            'municipality' => $data['municipality'] ?? '',
            'province' => $data['province'] ?? '',
            'full_address' => $fullAddress,
            'id' => $id
        ]);
    }
    
    // Delete address
    public function delete($id) {
        $sql = "DELETE FROM addresses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    // Get all addresses
    public function getAll() {
        $sql = "SELECT a.*, s.first_name, s.last_name, s.student_id
                FROM addresses a
                JOIN students s ON a.student_id = s.id
                ORDER BY a.id DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Search addresses
    public function search($query) {
        $sql = "SELECT a.*, s.first_name, s.last_name, s.student_id
                FROM addresses a
                JOIN students s ON a.student_id = s.id
                WHERE a.full_address LIKE :query
                OR s.first_name LIKE :query
                OR s.last_name LIKE :query
                ORDER BY a.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll();
    }
    
    // Get addresses for export
    public function getAllForExport() {
        $sql = "SELECT a.id, a.barangay, a.municipality, a.province, a.full_address,
                       s.student_id, s.first_name, s.last_name
                FROM addresses a
                JOIN students s ON a.student_id = s.id
                ORDER BY a.id";
        
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll();
        
        // Format for export
        $exportData = [];
        foreach ($results as $row) {
            $exportData[] = [
                $row['id'],
                $row['barangay'],
                $row['municipality'],
                $row['province'],
                $row['full_address'],
                $row['student_id'],
                $row['first_name'] . ' ' . $row['last_name']
            ];
        }
        
        return $exportData;
    }
}
?>