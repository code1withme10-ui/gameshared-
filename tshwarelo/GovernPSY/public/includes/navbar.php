<nav class="main-nav">
    <div class="logo">
        <a href="index.php">
            <span class="logo-top">GOVERN PSY</span>
            <span class="logo-bottom">EDUCATIONAL CENTER</span>
        </a>
    </div>

    <?php if (!isset($_SESSION['user_role'])): ?>
        <!-- Public pages: Show ellipsis menu and navigation links -->
        <div class="menu-toggle" id="mobile-menu">
            <i class="fas fa-ellipsis-v"></i>
        </div>

        <ul class="nav-links" id="nav-list">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="admissions.php">Admissions</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php" class="nav-login-btn">LOGIN</a></li>
        </ul>
    <?php else: ?>
        <!-- Logged-in pages: Show only User Badge and Logout -->
        <ul class="nav-links" style="display: flex; gap: 20px; align-items: center;">
            <li class="user-info" style="font-weight: bold; color: var(--red); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                <i class="fas fa-user-circle"></i> 
                <?php echo ($_SESSION['user_role'] === 'admin') ? 'Headmaster' : 'Parent Portal'; ?>
            </li>
            <li>
                <a href="actions/logout.php" class="logout-btn" style="background: #333; color: white; padding: 8px 18px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.85rem;">LOGOUT</a>
            </li>
        </ul>
    <?php endif; ?>
</nav>

<?php if (!isset($_SESSION['user_role'])): ?>
<script>
// Mobile menu toggle functionality (only for public pages)
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('mobile-menu');
    const navList = document.getElementById('nav-list');

    if(menuBtn && navList) {
        menuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            navList.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!menuBtn.contains(e.target) && !navList.contains(e.target)) {
                navList.classList.remove('active');
            }
        });
    }
});
</script>
<?php endif; ?>