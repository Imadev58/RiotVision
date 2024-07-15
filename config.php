<?php
$servername = "your_server_name";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Table created successfully or already exists";
} else {
    die("Error creating table: " . $conn->error);
}
?>
