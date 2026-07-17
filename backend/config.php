<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'kpr_college');

// Email Configuration
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_ENCRYPTION', 'tls');
define('MAIL_USERNAME', 'your-email@gmail.com');
define('MAIL_PASSWORD', 'your-app-password'); // Use Gmail App Password
define('MAIL_FROM_ADDRESS', 'your-email@gmail.com');
define('MAIL_FROM_NAME', 'KPR College - Computer Applications');

// Application Configuration
define('APP_NAME', 'KPR Certificate System');
define('APP_URL', 'https://yoursite.com');
define('CERTIFICATE_DIR', '../certificates/');

// Security Configuration
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50MB
define('ALLOWED_EXTENSIONS', ['pdf', 'jpg', 'jpeg', 'png']);

// Enable CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Error logging
ini_set('log_errors', 1);
ini_set('error_log', '../logs/error.log');

// Create necessary directories
if (!is_dir('../certificates')) {
    mkdir('../certificates', 0755, true);
}
if (!is_dir('../logs')) {
    mkdir('../logs', 0755, true);
}
?>
