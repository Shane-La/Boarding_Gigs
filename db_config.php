<?php
// Database configuration - session start removed to prevent duplicate sessions
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'boarding_gigs';

try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("DB Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>