<?php
// Database connection configuration
$host = 'localhost'; // Database host
$db = 'group6'; // Replace with your actual database name
$user = 'root'; // Replace with your actual database username
$pass = ''; // Replace with your actual database password
$charset = 'utf8mb4'; // Character set for database connection

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Error handling
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch mode
    PDO::ATTR_EMULATE_PREPARES   => false, // Disable emulation of prepared statements
];

// Attempt to establish a connection to the database
try {
    $pdo = new PDO($dsn, $user, $pass, $options); // Create a PDO instance
} catch (PDOException $e) {
    // Handle connection errors
    die("Connection failed: " . $e->getMessage());
}

// Optional: Set the character set to UTF-8
$pdo->exec("SET NAMES '$charset'");
?>
