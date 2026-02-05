<?php
class Claim {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Create a new claim
    public function create($data) {
        $sql = "INSERT INTO claims (item_id, claimer_id, status, admin_notes, verification_method, created_at) 
                VALUES (:item_id, :claimer_id, :status, :admin_notes, :verification_method, :created_at)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    // Find claim by ID
    public function findById($id) {
        $sql = "SELECT c.*, i.name as item_name, s.first_name, s.last_name, s.student_id, s.contact, s.course
                FROM claims c
                JOIN items i ON c.item_id = i.id
                JOIN students s ON c.claimer_id = s.id
                WHERE c.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    // Find claim by item and claimer
    public function findByItemAndClaimer($itemId, $claimerId) {
        $sql = "SELECT * FROM claims WHERE item_id = :item_id AND claimer_id = :claimer_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['item_id' => $itemId, 'claimer_id' => $claimerId]);
        return $stmt->fetch();
    }
    
    // Get claim by ID with details
    public function findByIdWithDetails($id) {
        $sql = "SELECT c.*, 
                       i.name as item_name, i.description as item_description, i.image_filename,
                       s.first_name as claimer_first_name, s.last_name as claimer_last_name,
                       s.student_id, s.contact, s.course, s.year_section,
                       r.first_name as reporter_first_name, r.last_name as reporter_last_name
                FROM claims c
                JOIN items i ON c.item_id = i.id
                JOIN students s ON c.claimer_id = s.id
                LEFT JOIN students r ON i.reporter_id = r.id
                WHERE c.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    // Get claims by claimer ID
    public function getByClaimerId($claimerId) {
        $sql = "SELECT c.*, i.name as item_name, i.image_filename
                FROM claims c
                JOIN items i ON c.item_id = i.id
                WHERE c.claimer_id = :claimer_id
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['claimer_id' => $claimerId]);
        return $stmt->fetchAll();
    }
    
    // Get claims by status
    public function getByStatus($status) {
        $sql = "SELECT c.*, i.name as item_name, 
                       s.first_name as claimer_first_name, s.last_name as claimer_last_name,
                       s.student_id, s.contact
                FROM claims c
                JOIN items i ON c.item_id = i.id
                JOIN students s ON c.claimer_id = s.id
                WHERE c.status = :status
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        return $stmt->fetchAll();
    }
    
    // Get all claims with optional status filter
    public function getAllWithStatus($status = '') {
        $sql = "SELECT c.*, i.name as item_name, 
                       s.first_name as claimer_first_name, s.last_name as claimer_last_name,
                       s.student_id, s.contact, s.course
                FROM claims c
                JOIN items i ON c.item_id = i.id
                JOIN students s ON c.claimer_id = s.id";
        
        $params = [];
        
        if (!empty($status) && $status !== 'all') {
            $sql .= " WHERE c.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Get recent claims
    public function getRecent($limit = 10) {
        $sql = "SELECT c.*, i.name as item_name, 
                       s.first_name as claimer_first_name, s.last_name as claimer_last_name
                FROM claims c
                JOIN items i ON c.item_id = i.id
                JOIN students s ON c.claimer_id = s.id
                ORDER BY c.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Update claim
    public function update($id, $data) {
        $sql = "UPDATE claims SET 
                status = :status,
                admin_notes = :admin_notes,
                verification_method = :verification_method
                WHERE id = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    // Delete claim
    public function delete($id) {
        $sql = "DELETE FROM claims WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    // Count claims by status
    public function countByStatus($status) {
        $sql = "SELECT COUNT(*) as count FROM claims WHERE status = :status";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        return $stmt->fetch()['count'];
    }
    
    // Get all claims for export
    public function getAllForExport() {
        $sql = "SELECT c.id, i.name as item_name, 
                       CONCAT(s.first_name, ' ', s.last_name) as claimer_name,
                       s.contact, s.course, c.status, 
                       c.verification_method, c.admin_notes, c.created_at
                FROM claims c
                JOIN items i ON c.item_id = i.id
                JOIN students s ON c.claimer_id = s.id
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll();
        
        // Format for export
        $exportData = [];
        foreach ($results as $row) {
            $exportData[] = [
                $row['id'],
                $row['item_name'],
                $row['claimer_name'],
                $row['contact'] ?? '',
                $row['course'] ?? '',
                $row['status'],
                $row['verification_method'] ?? '',
                $row['admin_notes'] ?? '',
                date('Y-m-d H:i', strtotime($row['created_at']))
            ];
        }
        
        return $exportData;
    }
    
    // Search claims
    public function search($query) {
        $sql = "SELECT c.*, i.name as item_name, 
                       s.first_name as claimer_first_name, s.last_name as claimer_last_name
                FROM claims c
                JOIN items i ON c.item_id = i.id
                JOIN students s ON c.claimer_id = s.id
                WHERE i.name LIKE :query 
                OR s.first_name LIKE :query 
                OR s.last_name LIKE :query
                OR c.status LIKE :query
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll();
    }
}
?>