<?php
// admin/includes/config.php
// Database settings - change to your values
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'root123';
$db_name = 'real_estate';

// Session timeout (seconds)
define('INACTIVITY_TIMEOUT', 300); // 5 minutes

// Uploads directory (absolute)
$uploads_dir = realpath(__DIR__ . '/../../uploads');
if ($uploads_dir === false) {
    // fallback - create uploads folder
    $uploads_dir = __DIR__ . '/../../uploads';
    if (!is_dir($uploads_dir)) mkdir($uploads_dir, 0755, true);
}

// Start or resume session
if (session_status() === PHP_SESSION_NONE) session_start();

// MySQLi connection
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Utility escape
function esc($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
