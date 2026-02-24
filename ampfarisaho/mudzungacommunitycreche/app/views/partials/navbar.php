<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="main-nav">
    <div class="container nav-container">
        <ul class="nav-links">
            <a href="/index.php">Home</a>
            <a href="/about.php">About</a>
            <a href="/gallery.php">Gallery</a>
            <a href="/notice-board.php">Notice Board</a>
            <a href="/help-desk.php">Help Desk</a>
        </ul>

        <div class="nav-actions">
            <?php if (isset($_SESSION['user'])): ?>
                <a class="btn-dashboard" href="<?php
                    echo $_SESSION['user']['role'] === 'parent'
                        ? '/app/views/parent/dashboard.php'
                        : '/app/views/admin/dashboard.php';
                ?>">
                    Dashboard
                </a>

                <a class="btn-logout" href="/logout.php">Logout</a>
            <?php else: ?>
                <a class="btn-login" href="/login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
