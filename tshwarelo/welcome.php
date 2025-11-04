<?php
// CRITICAL: This MUST be the first line of the file. No spaces or blank lines before it.
session_start();

// --- 1. Security Check and Variable Setup ---

// If the user is NOT logged in, redirect them immediately to the login page.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// User is logged in, set up personalized variables
$parent_name = htmlspecialchars($_SESSION['user_name'] ?? 'Parent/Guardian');
$welcome_message = "Welcome Back, $parent_name!";

// --- 2. Include Header/Menu ---
// Include the menu bar here, after the session and security checks are complete.
include 'menu-bar.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Ndlovu's Creche</title>
    <!-- Add your stylesheet link here if you have one -->
    <!-- <link rel="stylesheet" href="styles.css" /> -->
    <style>
        /* Basic styling to make the welcome look good */
        body { font-family: sans-serif; text-align: center; }
        .portal-container { margin-top: 50px; padding: 30px; border: 1px solid #ddd; border-radius: 8px; max-width: 600px; margin-left: auto; margin-right: auto; }
        .welcome-msg { color: #007bff; font-weight: bold; margin-bottom: 20px; }
        .logout-btn { background-color: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    
    <div class="portal-container">
        <!-- Display the welcome message -->
        <h2 class="welcome-msg"><?= $welcome_message ?></h2> 
        
        <h1>Ndlovu's Kids Creche Portal</h1>
        
        <p>You have successfully accessed the main portal. Here, you can view your child's schedule, daily reports, and access important announcements from the creche staff.</p>

        <p>Ready to leave for now?</p>
        
        <!-- Placeholder link to log out (you'll need to create logout.php next) -->
        <a href="logout.php" class="logout-btn">Log Out</a>
    </div>

    <footer>
        <p>&copy; 2025 Ndlovu's Crèche</p>
    </footer>
</body>
</html>
