<?php
// config/database.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'lostfound');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
define('APP_URL', 'http://localhost/lostfound-php');
define('UPLOAD_PATH', __DIR__ . '/../uploads/items/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_EXTENSIONS', ['png', 'jpg', 'jpeg', 'gif', 'heic']);

// Timezone for Philippines
date_default_timezone_set('Asia/Manila');

// Environment variables
$env = parse_ini_file(__DIR__ . '/../.env');
if ($env) {
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

// Helper function to get env variables
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

// Admin credentials
define('ADMIN_USER', env('ADMIN_USER', 'admin'));
define('ADMIN_PASS', env('ADMIN_PASS', 'admin123'));
define('ADMIN_PASS_HASH', env('ADMIN_PASS_HASH', null));
?>