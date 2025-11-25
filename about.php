<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Humulani Pre School</title>
    <link rel="stylesheet" href="style.css"> <!-- Your global stylesheet -->
</head>
<body>

    <!-- Navigation Bar (shared style from style.css) -->
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="info.php">Info</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>

    <div class="page-container">

        <h1>About Humulani Pre School</h1>

        <div class="about-section">
            <h2>📜 School History</h2>
            <p>Humulani Pre School was founded in 2021 with a commitment to providing high-quality early childhood education in a supportive community setting. Initially operating from a small community hall, we grew rapidly, driven by parental demand for our unique play-based approach.</p>
            <p>In 2024, thanks to generous community support and our primary sponsor, we moved into our current secure, modern facility, allowing us to expand our curriculum and capacity. We are proud of our journey and remain dedicated to every child's bright start.</p>
        </div>

        <div class="about-section">
            <h2>👑 Our Founder</h2>

            <div class="founder-box">
                <img src="images/founder-image.jpg" alt="Founder Photo" class="founder-img">
                <div>
                    <h3>Tshwarelo Treat Ndlovu (TUT Student)</h3>
                    <p>Mr Ndlovu established Humulani with a vision of creating a space where learning is an adventure and curiosity is celebrated. His philosophy centers on holistic development, ensuring every child leaves Humulani confident and ready for primary school.</p>
                </div>
            </div>
        </div>

        <div class="about-section">
            <h2>🤝 Our Community Sponsors</h2>
            <p>We are grateful for the vital support we receive from organizations dedicated to early childhood welfare and community development. Their contributions help us maintain our modern facilities and fund educational resources.</p>

            <div class="sponsor-grid">
                <img src="images/logo-community-bank.png" alt="Community Bank" class="sponsor-logo">
                <img src="images/logo-local-ngo.png" alt="Local NGO" class="sponsor-logo">
                <img src="images/logo-education-fund.png" alt="Education Fund" class="sponsor-logo">
            </div>
        </div>

        <footer>
            <p>&copy; 2026 Humulani Pre School</p>
        </footer>

    </div>

</body>
</html>
