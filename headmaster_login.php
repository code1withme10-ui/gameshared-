<?php
// Make sure this file starts EXACTLY at the first character

$password = $_POST['password'];

if ($password === "admin123") {
    header("Location: dashboard.php");
    exit;
} else {
    header("Location: login.php?error=1");
    exit;
}
?>
