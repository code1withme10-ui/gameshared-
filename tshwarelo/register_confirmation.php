<?php
// CRITICAL: session_start() must be the very first instruction in the file
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Complete</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <main>
        <h1>Registration Successful!</h1>
        <p>Thank you for registering. Your details have been securely saved.</p>
        <p>You may now proceed to the <a href="login.php">login page</a> to access your account.</p>

        <?php 
        // Optional: display user name if you want a personalized message
        if (isset($_SESSION['user_name'])) {
            echo "<p>Welcome, " . htmlspecialchars($_SESSION['user_name']) . "!</p>";
        }
        ?>
    </main>
    <footer>
        <p>&copy; 2025 Ndlovu's Crèche</p>
    </footer>
</body>
</html>