<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="main-nav">
    <div class="nav-container">

        <!-- LEFT (Spacer to balance flexbox) -->
        <div class="nav-left-spacer"></div>

        <!-- CENTER (Logo) -->
        <div class="nav-center-logo">
            <a href="/index.php">
                <img src="/assets/images/logo.png" alt="Logo" class="nav-logo">
            </a>
        </div>

        <!-- RIGHT (Hamburger & Dropdown) -->
        <div class="nav-right-menu">
            <button id="hamburger-btn" class="hamburger-btn" aria-label="Toggle Navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div id="dropdown-menu" class="dropdown-menu">
                <div class="dropdown-links">
                    <a href="/index.php">Home</a>
                    <a href="/about.php">About</a>
                    <a href="/gallery.php">Gallery</a>
                    <a href="/notice-board.php">Notice Board</a>
                    <a href="/help-desk.php">Help Desk</a>
                </div>
                
                <div class="dropdown-divider"></div>

                <div class="dropdown-actions">
                    <?php if (isset($_SESSION['user'])): ?>
                        
                        <a class="btn-dashboard" href="<?php
                            echo $_SESSION['user']['role'] === 'parent'
                                ? '/parent-dashboard.php'
                                : '/admin-dashboard.php';
                        ?>">
                            Dashboard
                        </a>

                        <a class="btn-logout" href="/logout.php">Logout</a>

                    <?php else: ?>

                        <a class="btn-login" href="/login.php">Login</a>

                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const dropdownMenu = document.getElementById('dropdown-menu');

    if (hamburgerBtn && dropdownMenu) {
        hamburgerBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            hamburgerBtn.classList.toggle('active');
            dropdownMenu.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownMenu.contains(e.target) && !hamburgerBtn.contains(e.target)) {
                hamburgerBtn.classList.remove('active');
                dropdownMenu.classList.remove('active');
            }
        });
    }
});
</script>
