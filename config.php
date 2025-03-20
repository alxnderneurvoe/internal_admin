<?php
$host = "localhost";
$dbname = "u838660829_user";
$username = "u838660829_user";  // Default MySQL username in XAMPP
$password = "Solusindo15!";      // Default MySQL password in XAMPP

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
