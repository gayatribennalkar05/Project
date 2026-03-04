<?php

// Database configuration
$host = "localhost";
$username = "root";     // default XAMPP username
$password = "";         // default XAMPP password (empty)
$database = "quantumshield";  // your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Database Connection Failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");


// ===============================
// 🔐 AES Encryption Configuration
// ===============================

// DO NOT CHANGE these after sending messages
define('SECRET_KEY', 'QuantumShieldSuperSecret123!');
define('SECRET_IV', '1234567891011121');

?>