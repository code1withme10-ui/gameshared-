<?php
// index.php - HOME PAGE

session_start();

// Redirect logged-in users
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: welcome.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Humulani Pre School Home - Your Child's Journey</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

<header>
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

<div class="container">

    <h1 class="homepage-title">Welcome to Humulani Pre School</h1>

    <div class="hero">
        <h2>Your Child's Journey Starts with Joy at Humulani.</h2>
        <p>Building a strong foundation for future success through daily exploration.</p>
        <a href="admission.php" class="cta-btn">Register Your Child Today!</a>
    </div>

    <h2 class="section-heading">Quick Info</h2>

    <div class="info-card-grid">

        <div class="info-card">
            <h3>🕰️ Caring Hours</h3>
            <ul>
                <li><strong>Full Day:</strong> 7:00 AM - 5:30 PM</li>
                <li><strong>Half Day:</strong> 7:00 AM - 1:00 PM</li>
                <li><em>Closed on Public Holidays</em></li>
            </ul>
        </div>

        <div class="info-card">
            <h3>💰 School Fees</h3>
            <p>Our fees are competitive and all-inclusive of meals and materials.</p>
            <a href="documents/fee_structure_2026.pdf" download class="cta-btn fee-btn">
                Download Fee Structure
            </a>
        </div>

    </div>

    <div class="content-section">

        <div class="philosophy-box">
            <h3>Our Philosophy</h3>
            <p>At Humulani Pre School, we believe in nurturing the whole child—fostering curiosity,
            creativity, and confidence through play-based learning in a safe, supportive environment.</p>
        </div>

        <h3>Why Choose Us?</h3>
        <ul class="why-us-list">
            <li>Experienced and caring staff.</li>
            <li>Nutritious meals prepared daily.</li>
            <li>Secure, modern facilities.</li>
            <li>Curriculum focused on early developmental milestones.</li>
        </ul>

        <p>
            Ready to learn more? Visit our
            <a href="about.php" class="link-highlight">About Us</a>
            page.
        </p>

    </div>

    <footer>
        <p>&copy; 2026 Humulani Pre School</p>
    </footer>

</div>
</body>
</html>