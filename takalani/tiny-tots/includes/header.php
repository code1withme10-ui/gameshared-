<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and get role
$is_logged_in = isset($_SESSION['user']);
$user_role = $_SESSION['user']['role'] ?? null;
$is_headmaster = $is_logged_in && $user_role === 'headmaster';
$is_parent = $is_logged_in && $user_role === 'parent';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Tiny Tots Creche</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <div class="nav-brand">
                <h1>🧸 Tiny Tots Creche</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link active">🏠 Home</a></li>
                <li><a href="about.php" class="nav-link">📖 About Us</a></li>
                <li><a href="admission.php" class="nav-link">📝 Admissions</a></li>
                <li><a href="gallery.php" class="nav-link">🖼️ Gallery</a></li>
                <li><a href="contact.php" class="nav-link">📞 Contact Us</a></li>
                
                <?php if ($is_headmaster): ?>
                    <li><a href="admin/dashboard.php" class="nav-link">👨‍💼 Dashboard</a></li>
                    <li><a href="logout.php" class="nav-link">🚪 Logout</a></li>
                <?php elseif ($is_parent): ?>
                    <li><a href="parent/portal.php" class="nav-link">👨‍👩‍👧‍👦 Parent Portal</a></li>
                    <li><a href="logout.php" class="nav-link">🚪 Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="nav-link">🔐 Login</a></li>
                <?php endif; ?>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>
