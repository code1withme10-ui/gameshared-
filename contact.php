<?php
session_start();

// Simple Email Submission Logic
$message_status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    if ($name && $email && $subject && $message) {
        $message_status = '<div class="status-message-success">🎉 Thank you, ' . $name . '! Your message has been sent successfully.</div>';
    } else {
        $message_status = '<div class="status-message-error">🛑 Please fill in all fields before sending your message.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Humulani Pre School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navigation -->
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
        <h1>Get in Touch with Humulani</h1>

        <div class="contact-grid">
            <!-- Contact Info -->
            <div class="contact-info">
                <h2>Find Us and Connect</h2>
                <p>We are always happy to hear from prospective parents. Connect with us on social media or reach out directly.</p>
                <ul>
                    <li>📞 Phone: (011) 555-HUMU</li>
                    <li>📧 Email: info@humulani.co.za</li>
                    <li>📘 Facebook: <a href="https://facebook.com/HumulaniPreSchool" target="_blank">@HumulaniPreSchool</a></li>
                    <li>📍 Address: 23 Mahube Street, Mamelodi East, 0122, South Africa</li>
                </ul>

                <h2>School Hours</h2>
                <p>Monday to Friday: 7:00 AM - 5:30 PM</p>

                <div class="map-placeholder">
                    [Placeholder for Google Map Embed]
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-box">
                <h2>Send Us a Message</h2>

                <?php echo $message_status; ?>

                <form action="contact.php" method="POST">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>

                    <label for="message">Your Message:</label>
                    <textarea id="message" name="message" rows="6" required></textarea>

                    <button type="submit">Send Message</button>
                </form>
            </div>
        </div>

        <footer>
            <p>&copy; 2026 Humulani Pre School</p>
        </footer>
    </div>

</body>
</html>
