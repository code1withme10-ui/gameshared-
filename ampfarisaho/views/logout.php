<?php
// Include auth from the new structure
include __DIR__ . '/../includes/auth.php';

// Log out the user
logout();

// Redirect to login page
header("Location: index.php?page=login");
exit;

