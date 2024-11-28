<?php
// Database connection configuration
$host = 'localhost'; // Database host
$username = 'root'; // Database username
$password = ''; // Database password
$database = 'snipeit_database'; // Database name

// Create a connection to the MySQL database
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debug message (optional)
// echo "Connected successfully!";
?>
