<?php
// Database configuration
$host = "localhost";   // database host
$user = "root";        // database username
$pass = "";            // database password
$db   = "quantumshield"; // database name

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    // Instead of stopping the website, show a message
    echo "<h3>Database not connected.</h3>";
    echo "This site is running on server but database is not configured yet.";
}
?>
