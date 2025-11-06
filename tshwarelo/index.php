<?php
// CRITICAL: Start the session at the very top
session_start();

// --- 1. If the user is logged in, redirect them to the secure portal ---
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: welcome.php');
    exit;
}

// --- 2. If not logged in, proceed to display the public marketing content ---

// Include your menu/header file
include 'menu-bar.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ndlovu's Crèche Home</title>
    <style>

    </style>
</head>
<body>
    <div class="container">
        
        <div class="hero">
            <h2>A Bright Start for Little Stars</h2>
            <p>Providing loving care and early education for your little stars.</p>
            <a href="registration.php" class="cta-btn">Register Your Child Today!</a>
        </div>

        <div class="content-section">
            <h3>Our Philosophy</h3>
            <p>At Ndlovu's Crèche, we believe in nurturing the whole child—fostering curiosity, creativity, and confidence through play-based learning in a safe, supportive environment.</p>
            
            <h3>Why Choose Us?</h3>
            <ul>
                <li>Experienced and caring staff.</li>
                <li>Nutritious meals prepared daily.</li>
                <li>Secure, modern facilities.</li>
                <li>Curriculum focused on early developmental milestones.</li>
        </ul>
        <p>Ready to learn more? Check out our <a href="about.php">About Us</a> page.</p>
    </div>

    <footer>
        <p>&copy; 2025 Ndlovu's Crèche</p>
    </footer>
</body>
</html>