<?php
// Helper functions for the application

// Sanitize input
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Generate random string
function generateRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Format date
function formatDate($date, $format = 'F j, Y, g:i a') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

// Get status badge class
function getStatusBadge($status) {
    $classes = [
        'Lost' => 'bg-warning',
        'Found' => 'bg-info',
        'Claimed' => 'bg-success',
        'Pending' => 'bg-secondary',
        'Approved' => 'bg-success',
        'Rejected' => 'bg-danger'
    ];
    
    return $classes[$status] ?? 'bg-secondary';
}

// Redirect with message
function redirectWithMessage($url, $type, $message) {
    $_SESSION[$type] = $message;
    header('Location: ' . APP_URL . $url);
    exit();
}

// Check if file is an image
function isImage($file) {
    $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/heic'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    return in_array($mime, $allowed);
}

// Resize image
function resizeImage($file, $maxWidth = 1200, $maxHeight = 1200) {
    list($width, $height) = getimagesize($file);
    
    if ($width <= $maxWidth && $height <= $maxHeight) {
        return true;
    }
    
    $ratio = $width / $height;
    
    if ($width > $height) {
        $newWidth = $maxWidth;
        $newHeight = $maxWidth / $ratio;
    } else {
        $newHeight = $maxHeight;
        $newWidth = $maxHeight * $ratio;
    }
    
    // Create new image
    $src = imagecreatefromstring(file_get_contents($file));
    $dst = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preserve transparency
    imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    
    // Resize
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    // Save
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($dst, $file, 85);
            break;
        case 'png':
            imagepng($dst, $file, 9);
            break;
        case 'gif':
            imagegif($dst, $file);
            break;
    }
    
    imagedestroy($src);
    imagedestroy($dst);
    
    return true;
}
?>