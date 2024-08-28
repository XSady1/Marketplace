<?php
// Database connection settings
$servername = "marketplace"; // Change this if your database is on a different server
$username = "root";        // Your MySQL username
$password = "";            // Your MySQL password
$dbname = "registeredUsers";   // Your MySQL database name

// Create a new mysqli connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // Output an error message and terminate the script if the connection fails
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set to utf8mb4 for better compatibility with various characters
$conn->set_charset("utf8mb4");
?>
