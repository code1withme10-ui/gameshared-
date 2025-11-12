<?php session_start(); ?>
<nav>
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="gallery.php">Gallery</a>
    <a href="code_of_conduct.php">Code of Conduct</a>
    <a href="help.php">Help</a>

    <?php if (isset($_SESSION['parent_id'])) { ?>
        <a href="parent_dashboard.php">Parent Dashboard</a>
        <a href="logout.php">Logout</a>
    <?php } else { ?>
        <a href="register.php">Parent Register</a>
        <a href="parent_login.php">Parent Login</a>
        <a href="login.php">Headmaster Login</a>
    <?php } ?>
</nav>
