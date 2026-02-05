<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Address.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Claim.php';

class StudentController {
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
        // Check if student is logged in
        if (!isset($_SESSION['student_id'])) {
            $_SESSION['error'] = "Please login first";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $studentId = $_SESSION['student_id'];
        
        // Get student data
        $student = $this->studentModel->findById($studentId);
        if (!$student) {
            session_destroy();
            $_SESSION['error'] = "Student not found. Please login again.";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        // Get student's address
        $address = $this->addressModel->findByStudentId($studentId);
        
        // Get items (excluding claimed and archived)
        $items = $this->itemModel->getAllAvailableItems();
        
        // Get student's reported items
        $reportedItems = $this->itemModel->getByReporterId($studentId);
        
        // Get student's claims
        $claims = $this->claimModel->getByClaimerId($studentId);
        
        require_once __DIR__ . '/../views/student/dashboard.php';
    }
    
    public function profile() {
        if (!isset($_SESSION['student_id'])) {
            $_SESSION['error'] = "Please login first";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $studentId = $_SESSION['student_id'];
        $student = $this->studentModel->findById($studentId);
        $address = $this->addressModel->findByStudentId($studentId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => trim($_POST['first_name']),
                'middle_name' => trim($_POST['middle_name'] ?? ''),
                'last_name' => trim($_POST['last_name']),
                'suffix' => trim($_POST['suffix'] ?? ''),
                'course' => trim($_POST['course']),
                'year_section' => trim($_POST['year_section']),
                'contact' => trim($_POST['contact'])
            ];
            
            // Update student info
            if ($this->studentModel->update($studentId, $data)) {
                // Update address if provided
                $addressData = [
                    'barangay' => trim($_POST['barangay'] ?? ''),
                    'municipality' => trim($_POST['municipality'] ?? ''),
                    'province' => trim($_POST['province'] ?? '')
                ];
                
                if ($address) {
                    $this->addressModel->update($address['id'], $addressData);
                } else {
                    $addressData['student_id'] = $studentId;
                    $this->addressModel->create($addressData);
                }
                
                $_SESSION['success'] = "Profile updated successfully!";
                $_SESSION['student_name'] = $data['first_name'];
                header('Location: ' . APP_URL . '/student/profile');
                exit();
            } else {
                $_SESSION['error'] = "Failed to update profile";
            }
        }
        
        require_once __DIR__ . '/../views/student/profile.php';
    }
    
    public function claimItem($itemId) {
        if (!isset($_SESSION['student_id'])) {
            $_SESSION['redirect_to'] = APP_URL . '/claim/' . $itemId;
            $_SESSION['error'] = "Please login to claim item";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        $studentId = $_SESSION['student_id'];
        $item = $this->itemModel->findById($itemId);
        
        if (!$item) {
            $_SESSION['error'] = "Item not found";
            header('Location: ' . APP_URL . '/student/dashboard');
            exit();
        }
        
        if ($item['status'] === 'Claimed') {
            $_SESSION['error'] = "This item has already been claimed";
            header('Location: ' . APP_URL . '/student/dashboard');
            exit();
        }
        
        // Check if student already claimed this item
        $existingClaim = $this->claimModel->findByItemAndClaimer($itemId, $studentId);
        if ($existingClaim) {
            $_SESSION['error'] = "You have already submitted a claim for this item";
            header('Location: ' . APP_URL . '/student/dashboard');
            exit();
        }
        
        // Create claim
        $claimData = [
            'item_id' => $itemId,
            'claimer_id' => $studentId,
            'status' => 'Pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->claimModel->create($claimData)) {
            $_SESSION['success'] = "Claim submitted successfully! Waiting for admin approval.";
            header('Location: ' . APP_URL . '/student/dashboard');
            exit();
        } else {
            $_SESSION['error'] = "Failed to submit claim";
            header('Location: ' . APP_URL . '/student/dashboard');
            exit();
        }
    }
    
    public function getMyClaims() {
        if (!isset($_SESSION['student_id'])) {
            echo json_encode(['error' => 'Not authenticated']);
            exit();
        }
        
        $studentId = $_SESSION['student_id'];
        $claims = $this->claimModel->getByClaimerId($studentId);
        
        // Format for JSON response
        $formattedClaims = [];
        foreach ($claims as $claim) {
            $formattedClaims[] = [
                'id' => $claim['id'],
                'item_name' => $claim['item_name'] ?? 'N/A',
                'status' => $claim['status'],
                'created_at' => date('M d, Y', strtotime($claim['created_at'])),
                'admin_notes' => $claim['admin_notes'] ?? ''
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($formattedClaims);
    }
    
    public function generateQR($itemId = null) {
        require_once __DIR__ . '/../libs/phpqrcode/qrlib.php';
        
        $websiteUrl = APP_URL;
        if ($itemId) {
            $websiteUrl .= '/claim/' . $itemId;
        }
        
        // Generate QR code
        $qrFolder = __DIR__ . '/../static/qrcodes/';
        if (!is_dir($qrFolder)) {
            mkdir($qrFolder, 0777, true);
        }
        
        $filename = 'app_qr.png';
        $filepath = $qrFolder . $filename;
        
        QRcode::png($websiteUrl, $filepath, QR_ECLEVEL_L, 10, 4);
        
        // Return QR code image
        if (file_exists($filepath)) {
            header('Content-Type: image/png');
            readfile($filepath);
        } else {
            header('Content-Type: text/plain');
            echo "QR Code generation failed";
        }
        exit();
    }
}
?>