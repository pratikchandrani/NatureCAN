<?php
/**
 * Database Configuration
 *
 * Uses environment variables for credentials.
 * Never commit actual credentials to version control.
 */

// Database connection settings from environment
$DB_HOST = getenv('DB_HOST');
$DB_NAME = getenv('DB_NAME');
$DB_USER = getenv('DB_USER');
$DB_PASS = getenv('DB_PASS');
$DB_CHARSET = 'utf8mb4';

// Validate required environment variables
if (empty($DB_HOST) || empty($DB_NAME) || empty($DB_USER) || empty($DB_PASS)) {
    error_log('Database configuration error: Missing required environment variables');
    http_response_code(500);
    die('Database configuration error. Please contact administrator.');
}

// PDO connection options
$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHARSET}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_PERSISTENT         => false,
];

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    die('Database connection error. Please contact administrator.');
}
