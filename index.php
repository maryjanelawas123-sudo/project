<?php
/**
 * MCC Lost & Found System - Main Entry Point
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once 'config/database.php';

// Set error reporting based on environment
$env = getenv('APP_ENV') ?: 'development';
if ($env === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Define APP_URL if not defined
if (!defined('APP_URL')) {
    define('APP_URL', getenv('APP_URL') ?: 'http://localhost/lostfound-php');
}

// Define APP_NAME if not defined
if (!defined('APP_NAME')) {
    define('APP_NAME', getenv('APP_NAME') ?: 'MCC Lost & Found');
}

// Simple router - FIXED: Check for existence of 'url' parameter
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$urlParts = explode('/', $url);

// Authentication check function
function requireLogin($role = null) {
    if ($role === 'admin' && !isset($_SESSION['admin'])) {
        $_SESSION['error'] = "Admin access required";
        header('Location: ' . APP_URL . '/login');
        exit();
    }
    
    if ($role === 'student' && !isset($_SESSION['student_id'])) {
        $_SESSION['error'] = "Student access required";
        header('Location: ' . APP_URL . '/login');
        exit();
    }
    
    if (!$role && !isset($_SESSION['student_id']) && !isset($_SESSION['admin'])) {
        $_SESSION['error'] = "Please login first";
        header('Location: ' . APP_URL . '/login');
        exit();
    }
}

// Helper function to load controller
function loadController($controllerName, $action = null, $params = []) {
    $controllerFile = 'controllers/' . $controllerName . '.php';
    
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        
        // Check if class exists
        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            
            if ($action && method_exists($controller, $action)) {
                // Call controller method with parameters
                call_user_func_array([$controller, $action], $params);
            } elseif (method_exists($controller, 'index')) {
                // Default to index method
                $controller->index();
            } else {
                show404();
            }
        } else {
            show404();
        }
    } else {
        show404();
    }
}

// 404 error page
function show404() {
    http_response_code(404);
    if (file_exists('views/errors/404.php')) {
        require_once 'views/errors/404.php';
    } else {
        echo '<h1>404 - Page Not Found</h1>';
        echo '<p>The page you are looking for could not be found.</p>';
        echo '<a href="' . APP_URL . '">Go to Home</a>';
    }
    exit();
}

// 500 error page
function showErrorPage() {
    http_response_code(500);
    if (file_exists('views/errors/500.php')) {
        require_once 'views/errors/500.php';
    } else {
        echo '<h1>500 - Internal Server Error</h1>';
        echo '<p>Something went wrong. Please try again later.</p>';
        echo '<a href="' . APP_URL . '">Go to Home</a>';
    }
    exit();
}

// Error handling for production - FIXED: Use global variable
set_error_handler(function($errno, $errstr, $errfile, $errline) use ($env) {
    if ($env === 'production') {
        error_log("Error [$errno]: $errstr in $errfile on line $errline");
        showErrorPage();
    }
    return false;
});

set_exception_handler(function($exception) use ($env) {
    error_log("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
    if ($env === 'production') {
        showErrorPage();
    } else {
        throw $exception;
    }
});

// Main router logic
try {
    $route = $urlParts[0];
    
    // Route handling
    switch ($route) {
        case '':
        case 'home':
            if (isset($_SESSION['admin'])) {
                header('Location: ' . APP_URL . '/admin/dashboard');
            } elseif (isset($_SESSION['student_id'])) {
                header('Location: ' . APP_URL . '/student/dashboard');
            } else {
                // FIXED: Added proper home page handling
                if (file_exists('views/home.php')) {
                    require_once 'views/home.php';
                } else {
                    // Redirect to login if no home page exists
                    header('Location: ' . APP_URL . '/login');
                }
            }
            exit();
            
        // ================= AUTHENTICATION ROUTES =================
        case 'login':
            loadController('AuthController', 'login');
            break;
            
        case 'signup':
            loadController('AuthController', 'signup');
            break;
            
        case 'forgot-password':
            loadController('AuthController', 'forgotPassword');
            break;
            
        case 'change-password':
            loadController('AuthController', 'changePassword');
            break;
            
        case 'logout':
            loadController('AuthController', 'logout');
            break;
            
        // ================= STUDENT ROUTES =================
        case 'student':
            requireLogin('student');
            $action = $urlParts[1] ?? 'dashboard';
            $params = array_slice($urlParts, 2);
            
            switch ($action) {
                case 'dashboard':
                    loadController('StudentController', 'dashboard');
                    break;
                    
                case 'profile':
                    loadController('StudentController', 'profile');
                    break;
                    
                case 'report-item':
                    loadController('ItemController', 'report');
                    break;
                    
                case 'claim':
                    $itemId = $params[0] ?? null;
                    loadController('StudentController', 'claimItem', [$itemId]);
                    break;
                    
                case 'my-claims':
                    loadController('StudentController', 'getMyClaims');
                    break;
                    
                default:
                    show404();
                    break;
            }
            break;
            
        // ================= ADMIN ROUTES =================
        case 'admin':
            requireLogin('admin');
            $action = $urlParts[1] ?? 'dashboard';
            $params = array_slice($urlParts, 2);
            
            switch ($action) {
                case 'dashboard':
                    loadController('AdminController', 'dashboard');
                    break;
                    
                case 'items':
                    $subAction = $params[0] ?? 'manage';
                    $itemId = $params[1] ?? null;
                    
                    switch ($subAction) {
                        case 'manage':
                            loadController('ItemController', 'manage');
                            break;
                            
                        case 'add':
                            loadController('ItemController', 'addItem');
                            break;
                            
                        case 'edit':
                            loadController('ItemController', 'edit', [$itemId]);
                            break;
                            
                        case 'delete':
                            loadController('ItemController', 'delete', [$itemId]);
                            break;
                            
                        case 'archive':
                            loadController('ItemController', 'archive', [$itemId]);
                            break;
                            
                        case 'restore':
                            loadController('ItemController', 'restore', [$itemId]);
                            break;
                            
                        default:
                            show404();
                            break;
                    }
                    break;
                    
                case 'students':
                    $subAction = $params[0] ?? 'manage';
                    $studentId = $params[1] ?? null;
                    
                    switch ($subAction) {
                        case 'manage':
                            loadController('AdminController', 'manageStudents');
                            break;
                            
                        case 'add':
                            loadController('AdminController', 'addStudent');
                            break;
                            
                        case 'edit':
                            loadController('AdminController', 'editStudent', [$studentId]);
                            break;
                            
                        case 'delete':
                            loadController('AdminController', 'deleteStudent', [$studentId]);
                            break;
                            
                        case 'reset-password':
                            loadController('AdminController', 'resetStudentPassword', [$studentId]);
                            break;
                            
                        default:
                            show404();
                            break;
                    }
                    break;
                    
                case 'claims':
                    $subAction = $params[0] ?? 'manage';
                    $claimId = $params[1] ?? null;
                    
                    switch ($subAction) {
                        case 'manage':
                            loadController('AdminController', 'manageClaims');
                            break;
                            
                        case 'add':
                            loadController('AdminController', 'addClaim');
                            break;
                            
                        case 'edit':
                        case 'update':
                            loadController('AdminController', 'updateClaim', [$claimId]);
                            break;
                            
                        case 'delete':
                            loadController('AdminController', 'deleteClaim', [$claimId]);
                            break;
                            
                        case 'approve':
                            loadController('AdminController', 'approveClaim', [$claimId]);
                            break;
                            
                        case 'reject':
                            loadController('AdminController', 'rejectClaim', [$claimId]);
                            break;
                            
                        default:
                            show404();
                            break;
                    }
                    break;
                    
                case 'chart-data':
                    loadController('AdminController', 'getChartData');
                    break;
                    
                case 'export':
                    $table = $params[0] ?? '';
                    $format = $params[1] ?? 'csv';
                    loadController('AdminController', 'exportData', [$table, $format]);
                    break;
                    
                default:
                    show404();
                    break;
            }
            break;
            
        // ================= PUBLIC ITEM ROUTES =================
        case 'items':
            $action = $urlParts[1] ?? '';
            $itemId = $urlParts[2] ?? null;
            
            switch ($action) {
                case 'view':
                    // Public view of item
                    if ($itemId) {
                        require_once 'controllers/ItemController.php';
                        $itemCtrl = new ItemController();
                        // Call a public view method if it exists
                        if (method_exists($itemCtrl, 'viewPublic')) {
                            $itemCtrl->viewPublic($itemId);
                        } else {
                            show404();
                        }
                    } else {
                        show404();
                    }
                    break;
                    
                default:
                    show404();
                    break;
            }
            break;
            
        // ================= QR CODE ROUTES =================
        case 'qr':
            $itemId = $urlParts[1] ?? null;
            loadController('StudentController', 'generateQR', [$itemId]);
            break;
            
        // ================= API ROUTES =================
        case 'api':
            header('Content-Type: application/json');
            
            $apiType = $urlParts[1] ?? '';
            $action = $urlParts[2] ?? '';
            $params = array_slice($urlParts, 3);
            
            switch ($apiType) {
                case 'chart':
                    if ($action === 'data') {
                        loadController('AdminController', 'getChartData');
                    } else {
                        echo json_encode(['error' => 'Invalid API endpoint']);
                    }
                    break;
                    
                case 'student':
                    if ($action === 'claims') {
                        loadController('StudentController', 'getMyClaims');
                    } else {
                        echo json_encode(['error' => 'Invalid API endpoint']);
                    }
                    break;
                    
                case 'items':
                    if ($action === 'search') {
                        require_once 'controllers/ItemController.php';
                        $itemCtrl = new ItemController();
                        if (method_exists($itemCtrl, 'search')) {
                            $itemCtrl->search();
                        } else {
                            echo json_encode(['error' => 'Method not available']);
                        }
                    } else {
                        echo json_encode(['error' => 'Invalid API endpoint']);
                    }
                    break;
                    
                default:
                    echo json_encode(['error' => 'Invalid API endpoint']);
                    break;
            }
            exit(); // FIXED: Added exit after API response
            
        // ================= PUBLIC CLAIM ROUTES =================
        case 'claim':
            $itemId = $urlParts[1] ?? null;
            if ($itemId) {
                if (!isset($_SESSION['student_id'])) {
                    $_SESSION['redirect_to'] = APP_URL . '/claim/' . $itemId;
                    header('Location: ' . APP_URL . '/login');
                    exit();
                }
                loadController('StudentController', 'claimItem', [$itemId]);
            } else {
                show404();
            }
            break;
            
        // ================= SETUP/INSTALL ROUTES =================
        case 'setup':
            if (file_exists('setup.php')) {
                require_once 'setup.php';
            } else {
                show404();
            }
            break;
            
        // ================= ERROR ROUTES =================
        case '404':
            show404();
            break;
            
        case '403':
            http_response_code(403);
            if (file_exists('views/errors/403.php')) {
                require_once 'views/errors/403.php';
            } else {
                echo '<h1>403 - Forbidden</h1>';
                echo '<p>You do not have permission to access this page.</p>';
                echo '<a href="' . APP_URL . '">Go to Home</a>';
            }
            exit();
            
        case '500':
            http_response_code(500);
            if (file_exists('views/errors/500.php')) {
                require_once 'views/errors/500.php';
            } else {
                echo '<h1>500 - Internal Server Error</h1>';
                echo '<p>Something went wrong. Please try again later.</p>';
                echo '<a href="' . APP_URL . '">Go to Home</a>';
            }
            exit();
            
        // ================= DEFAULT 404 =================
        default:
            show404();
            break;
    }
    
} catch (Exception $e) {
    // Log the error
    error_log('Router Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    
    // Show error page
    if ($env === 'development') {
        // Show detailed error in development
        echo '<div style="padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;">';
        echo '<h3>Router Error:</h3>';
        echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . $e->getFile() . '</p>';
        echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
        echo '<p><strong>Trace:</strong><br><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre></p>';
        echo '</div>';
    } else {
        // Redirect to error page in production
        header('Location: ' . APP_URL . '/500');
        exit();
    }
}