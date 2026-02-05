<?php
class Item {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Create a new item
    public function create($data) {
        $sql = "INSERT INTO items (name, description, category, location, other_location, 
                other_category, building, classroom, status, image_filename, reporter_id, timestamp) 
                VALUES (:name, :description, :category, :location, :other_location, 
                :other_category, :building, :classroom, :status, :image_filename, :reporter_id, :timestamp)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    // Find item by ID
    public function findById($id) {
        $sql = "SELECT i.*, s.first_name as reporter_first_name, s.last_name as reporter_last_name,
                       s.student_id as reporter_student_id
                FROM items i
                LEFT JOIN students s ON i.reporter_id = s.id
                WHERE i.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    // Get all items with filters
    public function getAllWithFilters($search = '', $status = '', $category = '') {
        $sql = "SELECT i.*, s.first_name as reporter_first_name, s.last_name as reporter_last_name,
                       s.student_id as reporter_student_id
                FROM items i
                LEFT JOIN students s ON i.reporter_id = s.id
                WHERE i.is_archived = 0";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (i.name LIKE :search OR i.description LIKE :search)";
            $params['search'] = "%$search%";
        }
        
        if (!empty($status) && $status !== 'all') {
            $sql .= " AND i.status = :status";
            $params['status'] = $status;
        }
        
        if (!empty($category) && $category !== 'all') {
            $sql .= " AND i.category = :category";
            $params['category'] = $category;
        }
        
        $sql .= " ORDER BY i.timestamp DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Get all available items (not claimed, not archived)
    public function getAllAvailableItems() {
        $sql = "SELECT i.*, s.first_name as reporter_first_name, s.last_name as reporter_last_name
                FROM items i
                LEFT JOIN students s ON i.reporter_id = s.id
                WHERE i.status != 'Claimed' AND i.is_archived = 0
                ORDER BY i.timestamp DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Get items by reporter ID
    public function getByReporterId($reporterId) {
        $sql = "SELECT * FROM items 
                WHERE reporter_id = :reporter_id 
                AND is_archived = 0
                ORDER BY timestamp DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['reporter_id' => $reporterId]);
        return $stmt->fetchAll();
    }
    
    // Get archived items
    public function getArchived() {
        $sql = "SELECT i.*, s.first_name as reporter_first_name, s.last_name as reporter_last_name
                FROM items i
                LEFT JOIN students s ON i.reporter_id = s.id
                WHERE i.is_archived = 1
                ORDER BY i.timestamp DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Update item
    public function update($id, $data) {
        $sql = "UPDATE items SET 
                name = :name,
                description = :description,
                category = :category,
                location = :location,
                other_location = :other_location,
                other_category = :other_category,
                building = :building,
                classroom = :classroom,
                status = :status,
                image_filename = COALESCE(:image_filename, image_filename)
                WHERE id = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    // Update item status
    public function updateStatus($id, $status) {
        $sql = "UPDATE items SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
    
    // Delete item
    public function delete($id) {
        $sql = "DELETE FROM items WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    // Archive item
    public function archive($id) {
        $sql = "UPDATE items SET is_archived = 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    // Restore item
    public function restore($id) {
        $sql = "UPDATE items SET is_archived = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    // Count all items
    public function countAll() {
        $sql = "SELECT COUNT(*) as count FROM items WHERE is_archived = 0";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['count'];
    }
    
    // Count items by status
    public function countByStatus($status) {
        $sql = "SELECT COUNT(*) as count FROM items WHERE status = :status AND is_archived = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => $status]);
        return $stmt->fetch()['count'];
    }
    
    // Get recent items
    public function getRecent($limit = 10) {
        $sql = "SELECT i.*, s.first_name as reporter_first_name, s.last_name as reporter_last_name
                FROM items i
                LEFT JOIN students s ON i.reporter_id = s.id
                WHERE i.is_archived = 0
                ORDER BY i.timestamp DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get items available for claim
    public function getAvailableForClaim() {
        $sql = "SELECT i.* FROM items i
                WHERE i.status IN ('Lost', 'Found') 
                AND i.is_archived = 0
                ORDER BY i.name";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Get status counts for charts
    public function getStatusCounts() {
        $sql = "SELECT status, COUNT(*) as count 
                FROM items 
                WHERE is_archived = 0
                GROUP BY status";
        
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll();
        
        $counts = [
            'Lost' => 0,
            'Found' => 0,
            'Claimed' => 0
        ];
        
        foreach ($results as $row) {
            $counts[$row['status']] = (int)$row['count'];
        }
        
        return $counts;
    }
    
    // Get category counts for charts
    public function getCategoryCounts() {
        $sql = "SELECT category, COUNT(*) as count 
                FROM items 
                WHERE is_archived = 0 AND category IS NOT NULL
                GROUP BY category
                ORDER BY count DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Get daily counts for timeline
    public function getDailyCounts($days = 7) {
        $sql = "SELECT DATE(timestamp) as date, COUNT(*) as count
                FROM items
                WHERE timestamp >= DATE_SUB(NOW(), INTERVAL :days DAY)
                AND is_archived = 0
                GROUP BY DATE(timestamp)
                ORDER BY date";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['days' => $days]);
        return $stmt->fetchAll();
    }
    
    // Get weekly counts
    public function getWeeklyCounts($weeks = 4) {
        $sql = "SELECT YEARWEEK(timestamp) as week, COUNT(*) as count
                FROM items
                WHERE timestamp >= DATE_SUB(NOW(), INTERVAL :weeks WEEK)
                AND is_archived = 0
                GROUP BY YEARWEEK(timestamp)
                ORDER BY week";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['weeks' => $weeks]);
        return $stmt->fetchAll();
    }
    
    // Get monthly counts
    public function getMonthlyCounts($months = 6) {
        $sql = "SELECT DATE_FORMAT(timestamp, '%Y-%m') as month, COUNT(*) as count
                FROM items
                WHERE timestamp >= DATE_SUB(NOW(), INTERVAL :months MONTH)
                AND is_archived = 0
                GROUP BY DATE_FORMAT(timestamp, '%Y-%m')
                ORDER BY month";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['months' => $months]);
        return $stmt->fetchAll();
    }
    
    // Get all categories
    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM items WHERE category IS NOT NULL ORDER BY category";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Get all items for export
    public function getAllForExport() {
        $sql = "SELECT i.id, i.name, i.description, i.category, i.location, 
                       i.building, i.classroom, i.status, i.timestamp,
                       CONCAT(s.first_name, ' ', s.last_name) as reporter_name,
                       s.student_id as reporter_id
                FROM items i
                LEFT JOIN students s ON i.reporter_id = s.id
                WHERE i.is_archived = 0
                ORDER BY i.timestamp DESC";
        
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll();
        
        // Format for export
        $exportData = [];
        foreach ($results as $row) {
            $exportData[] = [
                $row['id'],
                $row['name'],
                $row['description'] ?? '',
                $row['category'] ?? '',
                $row['location'] ?? '',
                $row['building'] ?? '',
                $row['classroom'] ?? '',
                $row['status'],
                $row['reporter_name'] ?? 'N/A',
                date('Y-m-d H:i', strtotime($row['timestamp']))
            ];
        }
        
        return $exportData;
    }
    
    // Get archived items for export
    public function getArchivedForExport() {
        $sql = "SELECT i.id, i.name, i.description, i.category, i.location, 
                       i.status, i.timestamp,
                       CONCAT(s.first_name, ' ', s.last_name) as reporter_name
                FROM items i
                LEFT JOIN students s ON i.reporter_id = s.id
                WHERE i.is_archived = 1
                ORDER BY i.timestamp DESC";
        
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll();
        
        // Format for export
        $exportData = [];
        foreach ($results as $row) {
            $exportData[] = [
                $row['id'],
                $row['name'],
                $row['description'] ?? '',
                $row['category'] ?? '',
                $row['location'] ?? '',
                $row['status'],
                $row['reporter_name'] ?? 'N/A',
                date('Y-m-d H:i', strtotime($row['timestamp']))
            ];
        }
        
        return $exportData;
    }
    
    // Search items
    public function search($query) {
        $sql = "SELECT i.*, s.first_name as reporter_first_name, s.last_name as reporter_last_name
                FROM items i
                LEFT JOIN students s ON i.reporter_id = s.id
                WHERE i.is_archived = 0
                AND (i.name LIKE :query OR i.description LIKE :query OR i.category LIKE :query)
                ORDER BY i.timestamp DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll();
    }
}
?>