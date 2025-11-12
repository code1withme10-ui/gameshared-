<?php
$host = "db"; // or 'localhost' if running without Docker Compose
$user = "root";
$pass = "rootpassword";
$dbname = "littleones_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Database Connection Failed: " . $conn->connect_error);
?>
