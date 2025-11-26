<?php
include __DIR__ . "/includes/auth.php";

// Clear session and logout
logout();

// Redirect to login page
header("Location: login.php");
exit;

