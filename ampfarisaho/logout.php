<?php
// logout.php
session_start();

// Destroy the session to log out
session_destroy();

// Redirect to the login page
header('Location: login.php');
exit();
?>

