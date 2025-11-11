<?php
// db_config.php

// Database Credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'your_db_username'); // CHANGE THIS to your database user
define('DB_PASSWORD', 'your_db_password'); // CHANGE THIS to your database password
define('DB_NAME', 'your_database_name');   // CHANGE THIS to your database name

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect to the database. " . $conn->connect_error);
}

// Ensure the connection is set up for secure transactions
$conn->set_charset("utf8mb4");

// NOTE: In production, it's safer to use PDO for database interactions.
?>s