<?php
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Claim.php';

class ItemController {
    private $itemModel;
    private $studentModel;
    private $claimModel;
    
    public function __construct() {
        $this->itemModel = new Item();
        $this->studentModel = new Student();
        $this->claimModel = new Claim();
    }
    
    // Student: Report lost/found item
    public function report() {
        if (!isset($_SESSION['student_id'])) {
            $_SESSION['error'] = "Please login first";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reporterId = $_SESSION['student_id'];
            
            // Get form data
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category = trim($_POST['category'] ?? 'General');
            $otherCategory = trim($_POST['other_category'] ?? '');
            $location = trim($_POST['location'] ?? 'Unknown');
            $otherLocation = trim($_POST['other_location'] ?? '');
            $building = trim($_POST['building'] ?? '');
            $classroom = trim($_POST['room'] ?? '');
            $status = trim($_POST['status'] ?? 'Lost');
            
            // Validate required fields
            if (empty($name) || empty($status)) {
                $_SESSION['error'] = "Item name and status are required";
                header('Location: ' . APP_URL . '/student/dashboard');
                exit();
            }
            
            // Handle "Others" category
            if ($category === 'Others' && !empty($otherCategory)) {
                $finalCategory = $otherCategory;
            } else {
                $finalCategory = $category;
            }
            
            // Handle "Others" location
            if ($location === 'Others' && !empty($otherLocation)) {
                $finalLocation = $otherLocation;
            } else {
                $finalLocation = $location;
            }
            
            // Handle image upload
            $imageFilename = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageFilename = $this->handleImageUpload($_FILES['image']);
            }
            
            // Prepare item data
            $itemData = [
                'name' => $name,
                'description' => $description,
                'category' => $finalCategory,
                'location' => $finalLocation,
                'other_location' => $otherLocation,
                'other_category' => $otherCategory,
                'building' => ($location === 'Classroom') ? $building : null,
                'classroom' => ($location === 'Classroom') ? $classroom : null,
                'status' => $status,
                'reporter_id' => $reporterId,
                'image_filename' => $imageFilename,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            // Save item
            if ($this->itemModel->create($itemData)) {
                $_SESSION['success'] = "Item reported successfully!";
            } else {
                $_SESSION['error'] = "Failed to report item";
            }
            
            header('Location: ' . APP_URL . '/student/dashboard');
            exit();
        }
        
        // GET request - show report form
        require_once __DIR__ . '/../views/student/report_item.php';
    }
    
    // Admin: Add item
    public function addItem() {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'other_location' => trim($_POST['other_location'] ?? ''),
                'other_category' => trim($_POST['other_category'] ?? ''),
                'building' => trim($_POST['building'] ?? ''),
                'classroom' => trim($_POST['classroom'] ?? ''),
                'status' => trim($_POST['status'] ?? 'Found'),
                'reporter_id' => !empty($_POST['reporter_id']) ? $_POST['reporter_id'] : null
            ];
            
            // Handle "Others" selections
            if ($data['category'] === 'Others' && !empty($data['other_category'])) {
                $data['category'] = $data['other_category'];
            }
            
            if ($data['location'] === 'Others' && !empty($data['other_location'])) {
                $data['location'] = $data['other_location'];
            }
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image_filename'] = $this->handleImageUpload($_FILES['image']);
            }
            
            // Clear building/classroom if not classroom
            if ($data['location'] !== 'Classroom') {
                $data['building'] = null;
                $data['classroom'] = null;
            }
            
            // Save item
            if ($this->itemModel->create($data)) {
                $_SESSION['success'] = "Item added successfully!";
            } else {
                $_SESSION['error'] = "Failed to add item";
            }
            
            header('Location: ' . APP_URL . '/admin/items');
            exit();
        }
        
        // GET request - show add form
        $students = $this->studentModel->getAll();
        require_once __DIR__ . '/../views/admin/items/add.php';
    }
    
    // Admin: Manage items
    public function manage() {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        // Get search parameters
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $category = $_GET['category'] ?? '';
        
        // Get items with filters
        $items = $this->itemModel->getAllWithFilters($search, $status, $category);
        
        // Get archived items
        $archivedItems = $this->itemModel->getArchived();
        
        // Get categories for filter
        $categories = $this->itemModel->getCategories();
        
        require_once __DIR__ . '/../views/admin/items/manage.php';
    }
    
    // Admin: Edit item
    public function edit($itemId) {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $item = $this->itemModel->findById($itemId);
        if (!$item) {
            $_SESSION['error'] = "Item not found";
            header('Location: ' . APP_URL . '/admin/items');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'location' => trim($_POST['location'] ?? ''),
                'other_location' => trim($_POST['other_location'] ?? ''),
                'other_category' => trim($_POST['other_category'] ?? ''),
                'building' => trim($_POST['building'] ?? ''),
                'classroom' => trim($_POST['classroom'] ?? ''),
                'status' => trim($_POST['status'] ?? '')
            ];
            
            // Handle "Others" selections
            if ($data['category'] === 'Others' && !empty($data['other_category'])) {
                $data['category'] = $data['other_category'];
            }
            
            if ($data['location'] === 'Others' && !empty($data['other_location'])) {
                $data['location'] = $data['other_location'];
            }
            
            // Clear building/classroom if not classroom
            if ($data['location'] !== 'Classroom') {
                $data['building'] = null;
                $data['classroom'] = null;
            }
            
            // Handle image update
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Delete old image if exists
                if (!empty($item['image_filename'])) {
                    $oldImage = UPLOAD_PATH . $item['image_filename'];
                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }
                $data['image_filename'] = $this->handleImageUpload($_FILES['image']);
            }
            
            // Update item
            if ($this->itemModel->update($itemId, $data)) {
                $_SESSION['success'] = "Item updated successfully!";
                header('Location: ' . APP_URL . '/admin/items');
                exit();
            } else {
                $_SESSION['error'] = "Failed to update item";
            }
        }
        
        $students = $this->studentModel->getAll();
        require_once __DIR__ . '/../views/admin/items/edit.php';
    }
    
    // Admin: Delete item
    public function delete($itemId) {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $item = $this->itemModel->findById($itemId);
        if (!$item) {
            $_SESSION['error'] = "Item not found";
            header('Location: ' . APP_URL . '/admin/items');
            exit();
        }
        
        // Delete associated image
        if (!empty($item['image_filename'])) {
            $imagePath = UPLOAD_PATH . $item['image_filename'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        if ($this->itemModel->delete($itemId)) {
            $_SESSION['success'] = "Item deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete item";
        }
        
        header('Location: ' . APP_URL . '/admin/items');
        exit();
    }
    
    // Admin: Archive/restore item
    public function archive($itemId) {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $action = $_GET['action'] ?? 'archive';
        
        if ($action === 'archive') {
            if ($this->itemModel->archive($itemId)) {
                $_SESSION['success'] = "Item archived successfully!";
            } else {
                $_SESSION['error'] = "Failed to archive item";
            }
        } elseif ($action === 'restore') {
            if ($this->itemModel->restore($itemId)) {
                $_SESSION['success'] = "Item restored successfully!";
            } else {
                $_SESSION['error'] = "Failed to restore item";
            }
        }
        
        header('Location: ' . APP_URL . '/admin/items');
        exit();
    }
    
    // Handle image upload
    private function handleImageUpload($file) {
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'heic'];
        $maxSize = 10 * 1024 * 1024; // 10MB
        
        // Check file size
        if ($file['size'] > $maxSize) {
            throw new Exception("File size exceeds 10MB limit");
        }
        
        // Get file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Check allowed extensions
        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception("File type not allowed. Allowed: " . implode(', ', $allowedExtensions));
        }
        
        // Generate unique filename
        $filename = uniqid() . '_' . preg_replace('/[^A-Za-z0-9\.\-]/', '_', $file['name']);
        $filepath = UPLOAD_PATH . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Resize image if needed
            $this->resizeImage($filepath);
            return $filename;
        }
        
        throw new Exception("Failed to upload file");
    }
    
    // Resize image to max 1200x1200
    private function resizeImage($filepath) {
        try {
            $info = getimagesize($filepath);
            if (!$info) {
                return false;
            }
            
            list($width, $height) = $info;
            $maxSize = 1200;
            
            // Only resize if larger than max size
            if ($width > $maxSize || $height > $maxSize) {
                $ratio = $width / $height;
                
                if ($ratio > 1) {
                    $newWidth = $maxSize;
                    $newHeight = $maxSize / $ratio;
                } else {
                    $newWidth = $maxSize * $ratio;
                    $newHeight = $maxSize;
                }
                
                // Create new image
                $src = imagecreatefromstring(file_get_contents($filepath));
                $dst = imagecreatetruecolor($newWidth, $newHeight);
                
                // Preserve transparency for PNG/GIF
                if ($info[2] === IMAGETYPE_PNG || $info[2] === IMAGETYPE_GIF) {
                    imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                }
                
                // Resize
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                
                // Save based on type
                switch ($info[2]) {
                    case IMAGETYPE_JPEG:
                        imagejpeg($dst, $filepath, 75);
                        break;
                    case IMAGETYPE_PNG:
                        imagepng($dst, $filepath, 8);
                        break;
                    case IMAGETYPE_GIF:
                        imagegif($dst, $filepath);
                        break;
                }
                
                imagedestroy($src);
                imagedestroy($dst);
            }
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Get items for charts (AJAX)
    public function getChartData() {
        if (!isset($_SESSION['admin'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }
        
        $data = [
            'status_counts' => $this->itemModel->getStatusCounts(),
            'category_counts' => $this->itemModel->getCategoryCounts(),
            'timeline' => [
                'daily' => $this->itemModel->getDailyCounts(7),
                'weekly' => $this->itemModel->getWeeklyCounts(4),
                'monthly' => $this->itemModel->getMonthlyCounts(6)
            ]
        ];
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    // Export items
    public function export($format = 'csv') {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $items = $this->itemModel->getAllForExport();
        
        switch (strtolower($format)) {
            case 'csv':
                $this->exportCSV($items);
                break;
            case 'excel':
                $this->exportExcel($items);
                break;
            case 'pdf':
                $this->exportPDF($items);
                break;
            default:
                $_SESSION['error'] = "Invalid export format";
                header('Location: ' . APP_URL . '/admin/items');
                exit();
        }
    }
    
    private function exportCSV($items) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=items_' . date('Ymd_His') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['ID', 'Name', 'Description', 'Category', 'Location', 'Status', 'Reporter', 'Date']);
        
        // Data
        foreach ($items as $item) {
            fputcsv($output, [
                $item['id'],
                $item['name'],
                $item['description'],
                $item['category'],
                $item['location'],
                $item['status'],
                $item['reporter_name'] ?? 'N/A',
                date('Y-m-d H:i', strtotime($item['timestamp']))
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    private function exportExcel($items) {
        // This would require PHPExcel or PhpSpreadsheet library
        // Simplified version - create CSV with .xls extension
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=items_' . date('Ymd_His') . '.xls');
        
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Category</th><th>Location</th><th>Status</th><th>Reporter</th><th>Date</th></tr>";
        
        foreach ($items as $item) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($item['id']) . "</td>";
            echo "<td>" . htmlspecialchars($item['name']) . "</td>";
            echo "<td>" . htmlspecialchars($item['description']) . "</td>";
            echo "<td>" . htmlspecialchars($item['category']) . "</td>";
            echo "<td>" . htmlspecialchars($item['location']) . "</td>";
            echo "<td>" . htmlspecialchars($item['status']) . "</td>";
            echo "<td>" . htmlspecialchars($item['reporter_name'] ?? 'N/A') . "</td>";
            echo "<td>" . date('Y-m-d H:i', strtotime($item['timestamp'])) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        exit();
    }
    
    private function exportPDF($items) {
        // This would require TCPDF or FPDF library
        // For now, redirect to CSV export
        $this->exportCSV($items);
    }
}
?>