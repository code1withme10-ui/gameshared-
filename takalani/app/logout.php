<?php
// CRITICAL FIX 1: Start the session before attempting to destroy it.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
// Destroy all data registered to the session
session_destroy();

// Redirect to the login page (relative path is correct if files are in the same folder)
header("Location: login.php");

// Always exit after a header redirect
exit();

// CRITICAL FIX 2: Removed the extraneous '}' that was causing a syntax error.