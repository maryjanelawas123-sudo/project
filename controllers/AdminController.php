<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Claim.php';
require_once __DIR__ . '/../models/Address.php';

class AdminController {
    private $studentModel;
    private $itemModel;
    private $claimModel;
    private $addressModel;
    
    public function __construct() {
        $this->studentModel = new Student();
        $this->itemModel = new Item();
        $this->claimModel = new Claim();
        $this->addressModel = new Address();
    }
    
    public function dashboard() {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        // Get search parameters
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        
        // Get counts for dashboard
        $stats = [
            'total_items' => $this->itemModel->countAll(),
            'lost_items' => $this->itemModel->countByStatus('Lost'),
            'found_items' => $this->itemModel->countByStatus('Found'),
            'claimed_items' => $this->itemModel->countByStatus('Claimed'),
            'pending_claims' => $this->claimModel->countByStatus('Pending'),
            'total_students' => $this->studentModel->countAll()
        ];
        
        // Get recent items (excluding claimed)
        $recentItems = $this->itemModel->getRecent(10);
        
        // Get recent claims
        $recentClaims = $this->claimModel->getRecent(10);
        
        // Get pending claims
        $pendingClaims = $this->claimModel->getByStatus('Pending');
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
    
    // Student management
    public function manageStudents() {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $search = $_GET['search'] ?? '';
        $students = $this->studentModel->getAllWithSearch($search);
        
        require_once __DIR__ . '/../views/admin/students/manage.php';
    }
    
    public function addStudent() {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'student_id' => trim($_POST['student_id'] ?? ''),
                'first_name' => trim($_POST['first_name'] ?? ''),
                'middle_name' => trim($_POST['middle_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'suffix' => trim($_POST['suffix'] ?? ''),
                'course' => trim($_POST['course'] ?? ''),
                'year_section' => trim($_POST['year_section'] ?? ''),
                'contact' => trim($_POST['contact'] ?? ''),
                'username' => trim($_POST['username'] ?? $_POST['student_id']),
                'password' => password_hash('password123', PASSWORD_DEFAULT)
            ];
            
            // Check for duplicates
            if ($this->studentModel->findByStudentId($data['student_id'])) {
                $_SESSION['error'] = "Student ID already exists";
            } 
            elseif ($this->studentModel->findByUsername($data['username'])) {
                $_SESSION['error'] = "Username already taken";
            } 
            else {
                $studentId = $this->studentModel->create($data);
                
                if ($studentId) {
                    // Add address if provided
                    $addressData = [
                        'barangay' => trim($_POST['barangay'] ?? ''),
                        'municipality' => trim($_POST['municipality'] ?? ''),
                        'province' => trim($_POST['province'] ?? ''),
                        'student_id' => $studentId
                    ];
                    
                    if (!empty($addressData['barangay']) || !empty($addressData['municipality']) || !empty($addressData['province'])) {
                        $this->addressModel->create($addressData);
                    }
                    
                    $_SESSION['success'] = "Student added successfully! Default password: password123";
                    header('Location: ' . APP_URL . '/admin/students');
                    exit();
                } else {
                    $_SESSION['error'] = "Failed to add student";
                }
            }
        }
        
        require_once __DIR__ . '/../views/admin/students/add.php';
    }
    
    public function editStudent($studentId) {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $student = $this->studentModel->findById($studentId);
        if (!$student) {
            $_SESSION['error'] = "Student not found";
            header('Location: ' . APP_URL . '/admin/students');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'student_id' => trim($_POST['student_id'] ?? ''),
                'first_name' => trim($_POST['first_name'] ?? ''),
                'middle_name' => trim($_POST['middle_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'suffix' => trim($_POST['suffix'] ?? ''),
                'course' => trim($_POST['course'] ?? ''),
                'year_section' => trim($_POST['year_section'] ?? ''),
                'contact' => trim($_POST['contact'] ?? ''),
                'username' => trim($_POST['username'] ?? '')
            ];
            
            // Check if student_id changed and if new one exists
            if ($data['student_id'] !== $student['student_id'] && $this->studentModel->findByStudentId($data['student_id'])) {
                $_SESSION['error'] = "Student ID already exists";
            }
            // Check if username changed and if new one exists
            elseif ($data['username'] !== $student['username'] && $this->studentModel->findByUsername($data['username'])) {
                $_SESSION['error'] = "Username already taken";
            }
            else {
                if ($this->studentModel->update($studentId, $data)) {
                    // Update address
                    $addressData = [
                        'barangay' => trim($_POST['barangay'] ?? ''),
                        'municipality' => trim($_POST['municipality'] ?? ''),
                        'province' => trim($_POST['province'] ?? '')
                    ];
                    
                    $existingAddress = $this->addressModel->findByStudentId($studentId);
                    if ($existingAddress) {
                        $this->addressModel->update($existingAddress['id'], $addressData);
                    } else {
                        $addressData['student_id'] = $studentId;
                        $this->addressModel->create($addressData);
                    }
                    
                    $_SESSION['success'] = "Student updated successfully!";
                    header('Location: ' . APP_URL . '/admin/students');
                    exit();
                } else {
                    $_SESSION['error'] = "Failed to update student";
                }
            }
        }
        
        $student = $this->studentModel->findById($studentId);
        $address = $this->addressModel->findByStudentId($studentId);
        
        require_once __DIR__ . '/../views/admin/students/edit.php';
    }
    
    public function deleteStudent($studentId) {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        if ($this->studentModel->delete($studentId)) {
            $_SESSION['success'] = "Student deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete student";
        }
        
        header('Location: ' . APP_URL . '/admin/students');
        exit();
    }
    
    // Claim management
    public function manageClaims() {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $status = $_GET['status'] ?? '';
        $claims = $this->claimModel->getAllWithStatus($status);
        
        require_once __DIR__ . '/../views/admin/claims/manage.php';
    }
    
    public function addClaim() {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemId = $_POST['item_id'] ?? 0;
            $claimerName = trim($_POST['claimer_name'] ?? '');
            $studentId = trim($_POST['student_id'] ?? '');
            $course = trim($_POST['course'] ?? '');
            $contact = trim($_POST['contact'] ?? '');
            $adminNotes = trim($_POST['admin_notes'] ?? '');
            $status = trim($_POST['status'] ?? 'Pending');
            $verificationMethod = trim($_POST['verification_method'] ?? '');
            
            // Check if item exists and is not already claimed
            $item = $this->itemModel->findById($itemId);
            if (!$item) {
                $_SESSION['error'] = "Item not found";
                header('Location: ' . APP_URL . '/admin/claims');
                exit();
            }
            
            if ($item['status'] === 'Claimed') {
                $_SESSION['error'] = "Item is already claimed";
                header('Location: ' . APP_URL . '/admin/claims');
                exit();
            }
            
            // Find or create student
            $claimerStudent = null;
            if (!empty($studentId)) {
                $claimerStudent = $this->studentModel->findByStudentId($studentId);
            }
            
            if (!$claimerStudent && !empty($claimerName)) {
                // Try to find by name and contact
                $parts = explode(' ', $claimerName);
                $firstName = $parts[0] ?? '';
                $lastName = $parts[count($parts) - 1] ?? '';
                $middleName = (count($parts) > 2) ? implode(' ', array_slice($parts, 1, -1)) : '';
                
                $claimerStudent = $this->studentModel->findByNameAndContact($firstName, $lastName, $contact);
            }
            
            // Create student if not found
            if (!$claimerStudent) {
                $studentData = [
                    'student_id' => $studentId ?: uniqid('STU'),
                    'first_name' => $firstName ?? '',
                    'middle_name' => $middleName ?? '',
                    'last_name' => $lastName ?? '',
                    'course' => $course,
                    'contact' => $contact,
                    'username' => $studentId ?: uniqid('user'),
                    'password' => password_hash('password123', PASSWORD_DEFAULT)
                ];
                
                $claimerStudentId = $this->studentModel->create($studentData);
                $claimerStudent = $this->studentModel->findById($claimerStudentId);
            }
            
            // Check for existing claim
            $existingClaim = $this->claimModel->findByItemAndClaimer($itemId, $claimerStudent['id']);
            if ($existingClaim) {
                $_SESSION['error'] = "This student already has a claim for this item";
                header('Location: ' . APP_URL . '/admin/claims');
                exit();
            }
            
            // Create claim
            $claimData = [
                'item_id' => $itemId,
                'claimer_id' => $claimerStudent['id'],
                'status' => $status,
                'admin_notes' => $adminNotes,
                'verification_method' => $verificationMethod,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            if ($this->claimModel->create($claimData)) {
                // Update item status if approved
                if ($status === 'Approved') {
                    $this->itemModel->updateStatus($itemId, 'Claimed');
                }
                
                $_SESSION['success'] = "Claim added successfully!";
                header('Location: ' . APP_URL . '/admin/claims');
                exit();
            } else {
                $_SESSION['error'] = "Failed to add claim";
            }
        }
        
        // GET request - show form
        $items = $this->itemModel->getAvailableForClaim();
        require_once __DIR__ . '/../views/admin/claims/add.php';
    }
    
    public function updateClaim($claimId) {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $claim = $this->claimModel->findById($claimId);
        if (!$claim) {
            $_SESSION['error'] = "Claim not found";
            header('Location: ' . APP_URL . '/admin/claims');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $adminNotes = trim($_POST['admin_notes'] ?? '');
            $verificationMethod = trim($_POST['verification_method'] ?? '');
            
            switch ($action) {
                case 'approve':
                    $newStatus = 'Approved';
                    break;
                case 'reject':
                    $newStatus = 'Rejected';
                    break;
                case 'pending':
                    $newStatus = 'Pending';
                    break;
                default:
                    $_SESSION['error'] = "Invalid action";
                    header('Location: ' . APP_URL . '/admin/claims');
                    exit();
            }
            
            // Update claim
            $updateData = [
                'status' => $newStatus,
                'admin_notes' => $adminNotes,
                'verification_method' => $verificationMethod
            ];
            
            if ($this->claimModel->update($claimId, $updateData)) {
                // Update item status if claim approved
                if ($newStatus === 'Approved') {
                    $this->itemModel->updateStatus($claim['item_id'], 'Claimed');
                } elseif ($claim['status'] === 'Approved' && $newStatus !== 'Approved') {
                    // If previously approved but now changed, revert item status
                    $item = $this->itemModel->findById($claim['item_id']);
                    $this->itemModel->updateStatus($claim['item_id'], $item['original_status'] ?? 'Found');
                }
                
                $_SESSION['success'] = "Claim updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update claim";
            }
            
            header('Location: ' . APP_URL . '/admin/claims');
            exit();
        }
        
        // GET request - show edit form
        $claim = $this->claimModel->findByIdWithDetails($claimId);
        require_once __DIR__ . '/../views/admin/claims/edit.php';
    }
    
    public function deleteClaim($claimId) {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        if ($this->claimModel->delete($claimId)) {
            $_SESSION['success'] = "Claim deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete claim";
        }
        
        header('Location: ' . APP_URL . '/admin/claims');
        exit();
    }
    
    // Export functionality
    public function exportData($table, $format) {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        switch ($table) {
            case 'students':
                $data = $this->studentModel->getAllForExport();
                $filename = 'students';
                $headers = ['ID', 'Student ID', 'First Name', 'Middle Name', 'Last Name', 'Suffix', 'Course', 'Year/Section', 'Contact', 'Username'];
                break;
            case 'items':
                $data = $this->itemModel->getAllForExport();
                $filename = 'items';
                $headers = ['ID', 'Name', 'Description', 'Category', 'Location', 'Building', 'Classroom', 'Status', 'Reporter', 'Date'];
                break;
            case 'claims':
                $data = $this->claimModel->getAllForExport();
                $filename = 'claims';
                $headers = ['ID', 'Item Name', 'Claimer Name', 'Contact', 'Course', 'Status', 'Verification Method', 'Admin Notes', 'Date'];
                break;
            case 'archived':
                $data = $this->itemModel->getArchivedForExport();
                $filename = 'archived_items';
                $headers = ['ID', 'Name', 'Description', 'Category', 'Location', 'Status', 'Reporter', 'Date Archived'];
                break;
            default:
                $_SESSION['error'] = "Invalid table specified";
                header('Location: ' . APP_URL . '/admin/dashboard');
                exit();
        }
        
        $timestamp = date('Ymd_His');
        $fullFilename = $filename . '_' . $timestamp;
        
        switch (strtolower($format)) {
            case 'csv':
                $this->exportToCSV($data, $headers, $fullFilename . '.csv');
                break;
            case 'excel':
                $this->exportToExcel($data, $headers, $fullFilename . '.xlsx');
                break;
            case 'pdf':
                $this->exportToPDF($data, $headers, $fullFilename . '.pdf');
                break;
            case 'print':
                $this->printView($data, $headers, $filename);
                break;
            default:
                $_SESSION['error'] = "Invalid format specified";
                header('Location: ' . APP_URL . '/admin/dashboard');
                exit();
        }
    }
    
    private function exportToCSV($data, $headers, $filename) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit();
    }
    
    private function exportToExcel($data, $headers, $filename) {
        // Simple HTML table as Excel (for basic functionality)
        // For better Excel export, consider using PhpSpreadsheet library
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo '<table border="1">';
        echo '<tr>';
        foreach ($headers as $header) {
            echo '<th>' . htmlspecialchars($header) . '</th>';
        }
        echo '</tr>';
        
        foreach ($data as $row) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td>' . htmlspecialchars($cell) . '</td>';
            }
            echo '</tr>';
        }
        
        echo '</table>';
        exit();
    }
    
    private function exportToPDF($data, $headers, $filename) {
        // For PDF export, you would need TCPDF or FPDF library
        // This is a simplified version
        $_SESSION['error'] = "PDF export requires additional libraries. Using CSV instead.";
        $this->exportToCSV($data, $headers, str_replace('.pdf', '.csv', $filename));
    }
    
    private function printView($data, $headers, $title) {
        require_once __DIR__ . '/../views/admin/export/print.php';
        exit();
    }
    
    // Get chart data (AJAX)
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
    
    // Reset student password
    public function resetStudentPassword($studentId) {
        if (!isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Admin access required";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $tempPassword = bin2hex(random_bytes(4));
        $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);
        
        if ($this->studentModel->updatePassword($studentId, $hashedPassword)) {
            $_SESSION['success'] = "Password reset successfully! New password: <strong>$tempPassword</strong>";
        } else {
            $_SESSION['error'] = "Failed to reset password";
        }
        
        header('Location: ' . APP_URL . '/admin/students');
        exit();
    }
}
?>