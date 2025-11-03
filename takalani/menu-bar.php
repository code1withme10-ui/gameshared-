<?php
session_start(); // Always start the session to detect login
?>

<header>
    <nav style="background-color: #f2f2f2; padding: 10px; text-align: center;">
        <a href="index.php">Home</a> |
        <a href="about.php">About</a> |
        <a href="registration.php">Registration</a> |
        <a href="gallery.php">Gallery</a> |
        <a href="staff.php">Staff</a> |
        <a href="code-of-conduct.php">Code of Conduct</a> |
        
        <?php if (isset($_SESSION['user'])): ?>
            <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['user']['email']) ?>)</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>

<h1 style="text-align:center; margin-top: 20px;">Welcome to SubixStar Pre-School</h1>
