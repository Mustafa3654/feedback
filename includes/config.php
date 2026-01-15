<?php
/**
 * Database Configuration
 * Update these settings to match your XAMPP MySQL configuration
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'feedback_portal');
define('DB_USER', 'root');
define('DB_PASS', 'Mostafa2004'); // Default XAMPP has no password

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('UTC');

// CORS headers for API requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
