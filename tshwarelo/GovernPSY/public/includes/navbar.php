<nav class="main-nav" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 8%; background: #ffffff; color: #333; min-height: 80px; font-family: 'Montserrat', sans-serif; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000;">
    
    <div class="logo">
        <a href="index.php">
            <img src="images/logo.png" alt="Govern Psy Educational Center" style="height: 65px; width: auto; transition: 0.3s;">
        </a>
    </div>

    <ul style="list-style: none; display: flex; gap: 35px; margin: 0; padding: 0; align-items: center;">
        <?php if (!isset($_SESSION['user_role'])): ?>
            <li><a href="index.php" style="color: #003366; text-decoration: none; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Home</a></li>
            <li><a href="about.php" style="color: #003366; text-decoration: none; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">About Us</a></li>
            <li><a href="admissions.php" style="color: #003366; text-decoration: none; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Admissions</a></li>
            <li><a href="contact.php" style="color: #003366; text-decoration: none; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Contact</a></li>
            <li>
                <a href="login.php" style="background: #c62828; padding: 10px 25px; border-radius: 5px; color: white; text-decoration: none; font-weight: bold; font-size: 0.9rem; transition: 0.3s;">
                    LOGIN
                </a>
            </li>
        <?php else: ?>
            <li style="font-weight: bold; color: #c62828; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                <i class="fas fa-user-circle"></i> 
                <?php echo ($_SESSION['user_role'] === 'admin') ? 'Headmaster' : 'Parent Portal'; ?>
            </li>
            <li>
                <a href="actions/logout.php" style="background: #333; color: white; padding: 8px 18px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 0.85rem;">
                    LOGOUT
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>