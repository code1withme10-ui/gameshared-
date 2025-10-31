<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="styles.css">
<meta charset="UTF-8">
    <title>Home - Happy Kids Creche</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
// Include your menu bar if you have one
require_once 'menu-bar.php';
?>

<main style="text-align:center; margin-top:50px;">
    <h1>Welcome to Happy Kids Creche</h1>

    <?php if (isset($_SESSION['user'])): ?>
        <h3>Hello, <?= htmlspecialchars($_SESSION['user']['parentName']) ?>!</h3>
        <p>Your child: <?= htmlspecialchars($_SESSION['user']['childName']) ?> (Age: <?= htmlspecialchars($_SESSION['user']['childAge']) ?>)</p>
        <p><a href="logout.php" class="button">Logout</a></p>
    <?php else: ?>
        <p>Welcome! Please <a href="login.php">Login</a> or <a href="registration.php">Register</a> to continue.</p>
    <?php endif; ?>
</main>

<footer style="text-align:center; margin-top:30px;">
    <p>© 2025 Happy Kids Creche</p>
</footer>

</body>
</html>
