<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="styles.css">
<meta charset="UTF-8">
    <title>Home - SubixStar Pre-School</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
// Include your menu bar if you have one
require_once 'menu-bar.php';
?>

<main style="text-align:center; margin-top:50px;">
<h2>Hami Amukela, Ndaa ria-vha Tanganedza</h2>

    <?php if (isset($_SESSION['user'])): ?>
        <h3>Hello, <?= htmlspecialchars($_SESSION['user']['parentName']) ?>!</h3>
        <p>Your child: <?= htmlspecialchars($_SESSION['user']['childName']) ?> (Age: <?= htmlspecialchars($_SESSION['user']['childAge']) ?>)</p>
        <p><a href="logout.php" class="button">Logout</a></p>
    <?php else: ?>
        <p>Welcome! Please <a href="login.php">Login</a> or <a href="registration.php">Register</a> to continue.</p>
    <?php endif; ?>
</main>

<!-- Contact Details Section (added below home content) -->
<section style="text-align:center; margin-top:40px;">
    <h3>Contact Us</h3>
    <p><strong>Address:</strong> 582 New-Stands Street, Elim, South Africa</p>
    <p><strong>Phone:</strong>Cellphone: +27 66 024 5504</p>
    <p><strong>Email:</strong>Email us on :takalanimusubi117@gmail.com</p>
</section>

<footer style="text-align:center; margin-top:30px;">
    <p>© 2025 SubixStar Pre-School</p>
</footer>

</body>
</html>
