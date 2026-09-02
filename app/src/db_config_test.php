<?php
/**
 * MySQLi Database Connection
 * Legacy file - consider migrating to PDO (config.php)
 */

$DB_HOST = getenv('DB_HOST');
$DB_NAME = getenv('DB_NAME');
$DB_USER = getenv('DB_USER');
$DB_PASS = getenv('DB_PASS');

if (empty($DB_HOST) || empty($DB_NAME) || empty($DB_USER) || empty($DB_PASS)) {
    error_log('Database configuration error: Missing environment variables');
    http_response_code(500);
    die('Database configuration error.');
}

// Create connection
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if (!$conn) {
    error_log('Database connection failed: ' . mysqli_connect_error());
    http_response_code(500);
    die('Database connection error.');
}

// Set charset
mysqli_set_charset($conn, 'utf8mb4');

// Generate CSRF token if session doesn't have one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
