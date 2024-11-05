<?php
// connect.php

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it's not already started
}

$servername = "127.0.0.1"; // Server address
$username = "root";         // MySQL username
$password = "";             // MySQL password
$database = "users_courses_project"; // Database name
$port = 4306;               // Port number

try {
    // Create a PDO connection
    $dsn = "mysql:host=$servername;port=$port;dbname=$database"; // DSN for connection
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Default fetch mode
        PDO::ATTR_EMULATE_PREPARES => false, // Disable emulation
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Handle connection error
    die("Connection failed: " . $e->getMessage());
}
?>
