<?php
// Start session if not already started
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Determine current page for active link highlighting
$currentPage = $_GET['page'] ?? 'home';
?>

<!-- Add CSS link -->
<link rel="stylesheet" href="../public/css/style.css">

<nav class="menu-bar">
    <ul>
        <a href="index.php?page=home" <?= $currentPage==='home'?'class="active"':'' ?>>Home</a>
        <a href="index.php?page=about" <?= $currentPage==='about'?'class="active"':'' ?>>About</a>
        <a href="index.php?page=gallery" <?= $currentPage==='gallery'?'class="active"':'' ?>>Gallery</a>
        <a href="index.php?page=code_of_conduct" <?= $currentPage==='code_of_conduct'?'class="active"':'' ?>>Code of Conduct</a>
        <a href="index.php?page=admission" <?= $currentPage==='admission'?'class="active"':'' ?>>Admission</a>
        <a href="index.php?page=help" <?= $currentPage==='help'?'class="active"':'' ?>>Help</a>

        <?php if(isset($_SESSION['parent'])): ?>
            <a href="index.php?page=parent_dashboard" <?= $currentPage==='parent_dashboard'?'class="active"':'' ?>>Dashboard</a>
            <a href="index.php?page=progress_report" <?= $currentPage==='progress_report'?'class="active"':'' ?>>Progress Report</a>
            <a href="index.php?page=logout">Logout</a>
        <?php elseif(isset($_SESSION['headmaster'])): ?>
            <a href="index.php?page=headmaster_dashboard" <?= $currentPage==='headmaster_dashboard'?'class="active"':'' ?>>Headmaster Dashboard</a>
            <a href="index.php?page=logout">Logout</a>
        <?php else: ?>
            <a href="index.php?page=login" <?= $currentPage==='login'?'class="active"':'' ?>>Login</a>
        <?php endif; ?>
    </ul>
</nav>


