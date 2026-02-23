<?php
session_start();
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<main class="home-container">
    <header class="hero-section">
        <h1 class="creche-title">Tiny Tots Creche</h1>
        <h2 class="welcome-subtitle">Where little minds grow, explore, and shine!</h2>
        <p class="tagline">From 3 months to Grade RR • Aftercare also available</p>
    </header>

    <section class="welcome-message">
        <div class="message-card">
            <h3>Welcome to Tiny Tots Creche!</h3>
            <p>We provide a safe, nurturing, and stimulating environment where children are encouraged to discover their talents, build confidence, and develop a love for learning.</p>
            <p>Our dedicated caregivers are passionate about supporting every child's emotional, social, physical, and intellectual development through structured activities, creative play, and positive guidance.</p>
        </div>
    </section>

    <section class="key-highlights">
        <h3>Why Choose Tiny Tots?</h3>
        <div class="highlights-grid">
            <div class="highlight-item">
                <h4>🏠 Home-Away-From-Home</h4>
                <p>Warm, family-like atmosphere where children feel loved and secure</p>
            </div>
            <div class="highlight-item">
                <h4>👤 Individual Attention</h4>
                <p>We understand each child's unique personality and needs</p>
            </div>
            <div class="highlight-item">
                <h4>🤝 Parent Partnerships</h4>
                <p>Open communication and active parent involvement</p>
            </div>
            <div class="highlight-item">
                <h4>🌟 Holistic Development</h4>
                <p>Nurturing emotional intelligence, social skills, and creativity</p>
            </div>
            <div class="highlight-item">
                <h4>🛡️ Safe Environment</h4>
                <p>Structured learning with guided play and safety first</p>
            </div>
            <div class="highlight-item">
                <h4>📚 School Ready</h4>
                <p>Preparing children for Grade R and formal schooling</p>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <h3>Ready to Join Our Family?</h3>
        <p>Give your child the foundation they need for a bright and successful future.</p>
        <div class="cta-buttons">
            <a href="admission.php" class="cta-btn">Apply Now</a>
            <a href="about.php" class="cta-btn">Learn More</a>
        </div>
    </section>

    <section class="contact-section">
        <h3>📞 Contact Us</h3>
        <p><strong>📍 Address:</strong> 4 Copper Street, Musina, Limpopo, 0900</p>
        <p><strong>📱 Phone:</strong> 081 421 0084</p>
        <p><strong>📧 Email:</strong> mollerv40@gmail.com</p>
        <p><strong>🏢 EMIS NR:</strong> 973304431</p>
        <div class="operating-hours">
            <h4>🕐 Operating Hours</h4>
            <p>Monday to Friday: 7:00 AM - 5:30 PM</p>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
