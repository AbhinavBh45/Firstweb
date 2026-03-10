<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cinebook');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Helper function to sanitize output
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Helper function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Helper function to require login
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}
?>