<?php
$servername = "db"; // Use "localhost" if running on XAMPP
$username = "root";
$password = "root";
$dbname = "littleones";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
