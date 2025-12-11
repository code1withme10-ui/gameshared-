<?php
// Start session if not already started
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Determine current page for active link highlighting
$currentPage = $_GET['page'] ?? 'home';
?>

<nav class="menu-bar">
    <ul>
        <li><a href="index.php?page=home" <?= $currentPage==='home'?'class="active"':'' ?>>Home</a></li>
        <li><a href="index.php?page=about" <?= $currentPage==='about'?'class="active"':'' ?>>About</a></li>
        <li><a href="index.php?page=gallery" <?= $currentPage==='gallery'?'class="active"':'' ?>>Gallery</a></li>
        <li><a href="index.php?page=code_of_conduct" <?= $currentPage==='code_of_conduct'?'class="active"':'' ?>>Code of Conduct</a></li>
        <li><a href="index.php?page=admission" <?= $currentPage==='admission'?'class="active"':'' ?>>Admission</a></li>
        <li><a href="index.php?page=help" <?= $currentPage==='help'?'class="active"':'' ?>>Help</a></li>

        <?php if(isset($_SESSION['parent'])): ?>
            <li><a href="index.php?page=parent_dashboard" <?= $currentPage==='parent_dashboard'?'class="active"':'' ?>>Dashboard</a></li>
            <li><a href="index.php?page=progress_report" <?= $currentPage==='progress_report'?'class="active"':'' ?>>Progress Report</a></li>
            <li><a href="index.php?page=logout">Logout</a></li>
        <?php elseif(isset($_SESSION['headmaster'])): ?>
            <li><a href="index.php?page=headmaster_dashboard" <?= $currentPage==='headmaster_dashboard'?'class="active"':'' ?>>Headmaster Dashboard</a></li>
            <li><a href="index.php?page=logout">Logout</a></li>
        <?php else: ?>
            <li><a href="index.php?page=login" <?= $currentPage==='login'?'class="active"':'' ?>>Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Optional styling -->
<style>
.menu-bar {
    background: #4CAF50;
    padding: 10px;
}
.menu-bar ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
}
.menu-bar li {
    margin-right: 15px;
}
.menu-bar a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    padding: 5px 8px;
    border-radius: 3px;
}
.menu-bar a:hover {
    text-decoration: underline;
    background: rgba(255,255,255,0.2);
}
.menu-bar a.active {
    background: rgba(255,255,255,0.4);
}
</style>

