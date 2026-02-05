<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Database.php';

class AuthController {
    private $studentModel;
    
    public function __construct() {
        $this->studentModel = new Student();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? '';
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Validate inputs
            if (empty($role) || empty($username) || empty($password)) {
                $_SESSION['error'] = "All fields are required";
                header('Location: ' . APP_URL . '/login');
                exit();
            }
            
            if ($role === 'student') {
                $user = $this->studentModel->findByUsername($username);
                
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['student_id'] = $user['id'];
                    $_SESSION['student_name'] = $user['first_name'];
                    $_SESSION['student_username'] = $user['username'];
                    $_SESSION['success'] = "Welcome " . htmlspecialchars($user['first_name']) . "!";
                    
                    // Redirect based on intended destination
                    if (isset($_SESSION['redirect_to'])) {
                        $redirect = $_SESSION['redirect_to'];
                        unset($_SESSION['redirect_to']);
                        header('Location: ' . $redirect);
                    } else {
                        header('Location: ' . APP_URL . '/student/dashboard');
                    }
                    exit();
                } else {
                    $_SESSION['error'] = "Invalid username or password";
                }
            } 
            elseif ($role === 'admin') {
                $adminUser = getenv('ADMIN_USER') ?: 'admin';
                $adminPass = getenv('ADMIN_PASS') ?: 'admin123';
                
                // Check if using hashed password
                if (getenv('ADMIN_PASS_HASH')) {
                    $valid = password_verify($password, getenv('ADMIN_PASS_HASH'));
                } else {
                    $valid = ($password === $adminPass);
                }
                
                if ($username === $adminUser && $valid) {
                    $_SESSION['admin'] = true;
                    $_SESSION['admin_name'] = $adminUser;
                    $_SESSION['success'] = "Welcome " . htmlspecialchars($adminUser) . "!";
                    header('Location: ' . APP_URL . '/admin/dashboard');
                    exit();
                } else {
                    $_SESSION['error'] = "Invalid username or password";
                }
            } else {
                $_SESSION['error'] = "Invalid role selected";
            }
            
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        // GET request - show login form
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'student_id' => trim($_POST['student_id']),
                'first_name' => trim($_POST['first_name']),
                'middle_name' => trim($_POST['middle_name'] ?? ''),
                'last_name' => trim($_POST['last_name']),
                'suffix' => trim($_POST['suffix'] ?? ''),
                'course' => trim($_POST['course']),
                'year_section' => trim($_POST['year_section']),
                'contact' => trim($_POST['contact']),
                'username' => trim($_POST['username']),
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
            ];
            
            // Address data
            $addressData = [
                'barangay' => trim($_POST['barangay'] ?? ''),
                'municipality' => trim($_POST['municipality'] ?? ''),
                'province' => trim($_POST['province'] ?? '')
            ];
            
            // Validate required fields
            $required = ['student_id', 'first_name', 'last_name', 'course', 'contact', 'username', 'password'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    $_SESSION['error'] = ucfirst(str_replace('_', ' ', $field)) . " is required";
                    header('Location: ' . APP_URL . '/signup');
                    exit();
                }
            }
            
            // Check for duplicates
            if ($this->studentModel->findByStudentId($data['student_id'])) {
                $_SESSION['error'] = "Student ID already exists";
            } 
            elseif ($this->studentModel->findByUsername($data['username'])) {
                $_SESSION['error'] = "Username already taken";
            } 
            else {
                // Create student
                $studentId = $this->studentModel->create($data);
                
                if ($studentId) {
                    // Create address
                    $addressModel = new Address();
                    $addressData['student_id'] = $studentId;
                    $addressModel->create($addressData);
                    
                    $_SESSION['success'] = "Registration successful! Please login.";
                    header('Location: ' . APP_URL . '/login');
                    exit();
                } else {
                    $_SESSION['error'] = "Registration failed. Please try again.";
                }
            }
            
            header('Location: ' . APP_URL . '/signup');
            exit();
        }
        
        // GET request - show signup form
        require_once __DIR__ . '/../views/auth/signup.php';
    }
    
    public function logout() {
        // Clear all session variables
        $_SESSION = [];
        
        // Destroy session
        session_destroy();
        
        // Redirect to login
        header('Location: ' . APP_URL . '/login');
        exit();
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            
            if (empty($username)) {
                $_SESSION['error'] = "Username is required";
                header('Location: ' . APP_URL . '/forgot-password');
                exit();
            }
            
            $student = $this->studentModel->findByUsername($username);
            
            if (!$student) {
                $_SESSION['error'] = "Username not found";
                header('Location: ' . APP_URL . '/forgot-password');
                exit();
            }
            
            // Generate temporary password
            $tempPassword = bin2hex(random_bytes(4)); // 8 character password
            $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);
            
            // Update password
            if ($this->studentModel->updatePassword($student['id'], $hashedPassword)) {
                $_SESSION['success'] = "Temporary password: <strong>$tempPassword</strong>. Please change after login.";
                header('Location: ' . APP_URL . '/login');
                exit();
            } else {
                $_SESSION['error'] = "Failed to reset password";
            }
        }
        
        require_once __DIR__ . '/../views/auth/forgot_password.php';
    }
    
    public function changePassword() {
        // Check if student is logged in
        if (!isset($_SESSION['student_id'])) {
            $_SESSION['error'] = "Please login first";
            header('Location: ' . APP_URL . '/login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = $_SESSION['student_id'];
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validate inputs
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = "All fields are required";
                header('Location: ' . APP_URL . '/change-password');
                exit();
            }
            
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "New passwords do not match";
                header('Location: ' . APP_URL . '/change-password');
                exit();
            }
            
            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = "Password must be at least 6 characters";
                header('Location: ' . APP_URL . '/change-password');
                exit();
            }
            
            // Get current user
            $student = $this->studentModel->findById($studentId);
            
            // Verify current password
            if (!password_verify($currentPassword, $student['password'])) {
                $_SESSION['error'] = "Current password is incorrect";
                header('Location: ' . APP_URL . '/change-password');
                exit();
            }
            
            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            if ($this->studentModel->updatePassword($studentId, $hashedPassword)) {
                $_SESSION['success'] = "Password changed successfully!";
                header('Location: ' . APP_URL . '/student/dashboard');
                exit();
            } else {
                $_SESSION['error'] = "Failed to change password";
            }
        }
        
        require_once __DIR__ . '/../views/auth/change_password.php';
    }
}
?>