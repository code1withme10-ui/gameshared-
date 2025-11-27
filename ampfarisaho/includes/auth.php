<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require parent login
function requireParentLogin() {
    if (!isset($_SESSION['parent'])) {
        header("Location: login.php");
        exit();
    }
}

// Require headmaster login
function requireHeadmasterLogin() {
    if (!isset($_SESSION['headmaster'])) {
        header("Location: login.php");
        exit();
    }
}

// Logout function
function logout() {
    // Clear session data
    $_SESSION = [];

    // Destroy session cookie if exists
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), 
            '', 
            time() - 42000,
            $params["path"], 
            $params["domain"],
            $params["secure"], 
            $params["httponly"]
        );
    }

    // Destroy session
    session_destroy();
}
?>

