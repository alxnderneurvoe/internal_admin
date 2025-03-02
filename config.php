<?php
$host = "localhost";
$dbname = "internal_admin";
$username = "root";  // Default MySQL username in XAMPP
$password = "";      // Default MySQL password in XAMPP

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
