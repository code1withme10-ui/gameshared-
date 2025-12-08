<?php
 
// CRITICAL FIX: Robust session start (required because this is a primary page)
if (session_id() === '') {
    session_start();
}
// Set up error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Home - SubixStar Pre-School</title>
<link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>

<?php 
// FIX: Changed mismatched quote to double quote: "../app/menu-bar.php"
require_once "../app/menu-bar.php"; 
?>

<main class="home-container">
    <h1>Welcome to SubixStar Pre-School</h1>
    <h2 class="welcome-subtitle">Hami Amukela, Ndaa ria-vha Tanganedza</h2>

    <?php if (isset($_SESSION['user'])): 
        // Safely retrieve user data, defaulting to 'N/A' or a safe string if key is missing
        $parentName = $_SESSION['user']['parentName'] ?? 'User';
        $childName  = $_SESSION['user']['childName'] ?? 'N/A';
        $childAge   = $_SESSION['user']['childAge'] ?? 'N/A';
        $email      = $_SESSION['user']['email'] ?? '';
        $phone      = $_SESSION['user']['phone'] ?? '';
    ?>
        <div class="user-info-card">
            <h3>Welcome, <?= htmlspecialchars($parentName) ?>!</h3>
            
            <p><strong>Child Name:</strong> <?= htmlspecialchars($childName) ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($childAge) ?></p>

            <?php if (!empty($email)): ?>
                <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <?php endif; ?>

            <?php if (!empty($phone)): ?>
                <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
            <?php endif; ?>

            <p><a href="/logout" class="button rainbow-btn">Logout</a></p>
        </div>
    <?php else: ?>
        <p class="welcome-text">
            Welcome! Please <a href="/login" class="rainbow-link">Login</a> or 
            <a href="/registration" class="rainbow-link">Register</a> to continue.
        </p>
       
    <?php endif; ?>
</main>

<section class="contact-section">
    <h3>📞 Contact Us</h3>
    <p><strong>Address:</strong> 582 New-Stands Street, Elim, South Africa</p>
    <p><strong>Phone:</strong> +27 66 024 5504</p>
    <p><strong>Email:</strong> takalanimusubi117@gmail.com</p>
</section>

<?php 
// FIX: Corrected path to include '../app/footer.php' 
include '../app/footer.php'; 
?>

</body>
</html>