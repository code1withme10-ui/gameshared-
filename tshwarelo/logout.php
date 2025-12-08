<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy session
session_unset();
session_destroy();

// Redirect to home or login
header("Location: index.php?page=login");
exit;
