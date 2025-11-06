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

// --- NEW LOGIC: Check if this is the first time logging in this session ---
if (!isset($_SESSION['_first_login'])) {
    // This is the first time the user has hit the portal during this session
    $greeting = "Hello";
    // Set the session variable so future hits use the 'Welcome Back' message
    $_SESSION['_first_login'] = true;
} else {
    // User has been here before during this session
    $greeting = "Welcome Back";
}
$welcome_message = "$greeting, $parent_name!";
// --- END NEW LOGIC ---

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
        /* Changed text-align to left and adjusted container width and margin */
        body { 
            font-family: sans-serif; 
            text-align: left; /* Ensures all default text (including footer) is left-aligned */
        }
        .portal-container { 
            margin-top: 50px; 
            padding: 30px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            /* Reduced max-width and set left margin to 50px */
            max-width: 450px; 
            margin-left: 50px; 
            margin-right: auto; /* Keeps it responsive on the right */
            text-align: left; /* Ensure text inside the box is also left-aligned */
        }
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
        <!-- Footer text now relies on the body's left-align and does not need inline style -->
        <p>&copy; 2025 Ndlovu's Crèche</p>
    </footer>
</body>
</html>