<?php
// Database configuration
define('DB_HOST', 'localhost');   // Usually 'localhost'
define('DB_USER', 'root');        // Your DB username
define('DB_PASS', 'root123');     // Your DB password
define('DB_NAME', 'real_estate'); // Your database name

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Set character set to UTF-8 for proper encoding
$conn->set_charset('utf8mb4');
// config.php or at the top of your property.php
define('BUSINESS_WHATSAPP_NUMBER', '923298877421'); // Use your real business number, no "+" sign

?>
