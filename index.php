<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home - SubixStar Pre-School</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<?php require_once 'menu-bar.php'; ?>

<main class="home-container">
    <h1>Welcome to SubixStar Pre-School</h1>
    <h2 class="welcome-subtitle">Hami Amukela, Ndaa ria-vha Tanganedza</h2>

    <?php if (isset($_SESSION['user'])): ?>
        <div class="user-info-card">
            <h3>Welcome, <?= htmlspecialchars($_SESSION['user']['parentName']) ?>!</h3>
            <p><strong>Child Name:</strong> <?= htmlspecialchars($_SESSION['user']['childName']) ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($_SESSION['user']['childAge']) ?></p>

            <?php if (!empty($_SESSION['user']['email'])): ?>
                <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['user']['email']) ?></p>
            <?php endif; ?>

            <?php if (!empty($_SESSION['user']['phone'])): ?>
                <p><strong>Phone:</strong> <?= htmlspecialchars($_SESSION['user']['phone']) ?></p>
            <?php endif; ?>

            <p><a href="logout.php" class="button rainbow-btn">Logout</a></p>
        </div>
    <?php else: ?>
        <p class="welcome-text">
            Welcome! Please <a href="login.php" class="rainbow-link">Login</a> or 
            <a href="registration.php" class="rainbow-link">Register</a> to continue.
        </p>
    <?php endif; ?>
</main>

<section class="contact-section">
    <h3>📞 Contact Us</h3>
    <p><strong>Address:</strong> 582 New-Stands Street, Elim, South Africa</p>
    <p><strong>Phone:</strong> +27 66 024 5504</p>
    <p><strong>Email:</strong> takalanimusubi117@gmail.com</p>
</section>

<footer class="footer">
    <p>© 2025 SubixStar Pre-School | Growing Little Stars 🌟</p>
</footer>

</body>
</html>
