<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Home - SubixStar Pre-School</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<?php require_once 'menu-bar.php'; ?>

<main style="text-align:center; margin-top:50px;">
    <h2>Hami Amukela, Ndaa ria-vha Tanganedza</h2>

    <?php if (isset($_SESSION['user'])): ?>
        <h3>Welcome, <?= htmlspecialchars($_SESSION['user']['childName']) ?>!</h3>
        <p><strong>Child Name:</strong> <?= htmlspecialchars($_SESSION['user']['childName']) ?></p>
        <p><strong>Age:</strong> <?= htmlspecialchars($_SESSION['user']['childAge']) ?></p>

        <?php if (!empty($_SESSION['user']['email'])): ?>
            <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['user']['email']) ?></p>
        <?php endif; ?>

        <?php if (!empty($_SESSION['user']['phone'])): ?>
            <p><strong>Phone:</strong> <?= htmlspecialchars($_SESSION['user']['phone']) ?></p>
        <?php endif; ?>

        <p><a href="logout.php" class="button">Logout</a></p>
    <?php else: ?>
        <p>Welcome! Please <a href="login.php">Login</a> or <a href="registration.php">Register</a> to continue.</p>
    <?php endif; ?>
</main>

<section style="text-align:center; margin-top:40px;">
    <h3>Contact Us</h3>
    <p><strong>Address:</strong> 582 New-Stands Street, Elim, South Africa</p>
    <p><strong>Phone:</strong> +27 66 024 5504</p>
    <p><strong>Email:</strong> takalanimusubi117@gmail.com</p>
</section>

<footer style="text-align:center; margin-top:30px;">
    <p>© 2025 SubixStar Pre-School</p>
</footer>

</body>
</html>
