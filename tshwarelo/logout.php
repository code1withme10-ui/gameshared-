<?php
// LOGOUT PAGE: logout.php

// 1. Start the session to gain access to session variables
session_start();

// 2. Unset all session variables (clears the data)
$_SESSION = array();

// 3. Destroy the session cookie (if one exists)
// This makes sure the browser no longer tries to use the old session ID
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finally, destroy the session on the server
session_destroy();

// 5. Redirect the user back to the login page
header('Location: login.php');
exit;
?>