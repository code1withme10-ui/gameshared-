<?php
$password = $_POST['password'];

if ($password === "admin123") {
    header("Location: dashboard.php");
    exit;
} else {
    echo "Incorrect password.";
}
?>
