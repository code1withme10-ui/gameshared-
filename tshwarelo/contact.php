<?php
// CONTACT PAGE: contact.php

// CRITICAL: Start the session at the very top
session_start();

// --- Simple Email Submission Logic (Placeholder) ---
$message_status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // NOTE: In a live environment, you would use a secure mail function here.
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Dummy logic: Check if all fields are filled
    if ($name && $email && $subject && $message) {
        // Assume mail function works for demonstration
        $message_status = '<div class="success-message">🎉 Thank you, ' . $name . '! Your message has been sent successfully.</div>';
    } else {
        $message_status = '<div class="error-message">🛑 Please fill in all fields before sending your message.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Humulani Pre School</title>
    
    <style>
        /* --- 1. CORE STYLES (Consistency) --- */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes rainbowShine {
            0% { border-color: #ff0000; } 50% { border-color: #0000ff; } 100% { border-color: #ff0000; }
        }
        /* --- Base & Layout --- */
        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background-color: #fcfcfc; text-align: left; }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; }
        h1 {
            color: #47b3ff; /* Blue accent for Contact page */
            font-size: 2.8em; margin-top: 30px;
            border-bottom: 3px solid #ff9900; 
            padding-bottom: 10px; margin-bottom: 40px;
        }
        /* --- Navigation Styles (UPDATED to Registration) --- */
        .site-header { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; background-color: #fff; border-bottom: 3px solid transparent; animation: rainbowShine 8s infinite alternate; }
        .nav-link { text-decoration: none; font-weight: bold; margin: 0 10px; }
        .nav-link:nth-child(1) { color: #ff0000; } .nav-link:nth-child(2) { color: #ff9900; } 
        .nav-link:nth-child(3) { color: #008000; } .nav-link:nth-child(4) { color: #0000ff; } /* Registration */
        .nav-link:nth-child(5) { color: #4b0082; } /* Contact */
        .nav-link:nth-child(6) { color: #ee82ee; } 

        /* --- 2. CONTACT SPECIFIC STYLES --- */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two columns: Info and Form */
            gap: 40px;
            margin-bottom: 50px;
        }
        .contact-info, .contact-form-box {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            background-color: #fff;
            height: 100%;
        }
        .contact-info {
            background-color: #f0f8ff; /* Soft blue background */
            border-left: 5px solid #47b3ff;
        }
        .contact-info h2 { color: #4b0082; margin-top: 0; }
        .contact-info ul { list-style: none; padding: 0; }
        .contact-info ul li { margin-bottom: 15px; }
        
        /* Form Styles */
        input[type="text"], input[type="email"], textarea {
            width: 100%; padding: 10px; margin-bottom: 15px;
            border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;
        }
        .submit-btn {
            background-color: #008000; /* Green submit button */
            color: white; padding: 12px 25px; border: none;
            border-radius: 5px; cursor: pointer; font-size: 1em;
            transition: background-color 0.3s;
        }
        .submit-btn:hover { background-color: #00b300; }

        .success-message, .error-message {
            padding: 15px; margin-bottom: 20px; border-radius: 5px;
            font-weight: bold; text-align: center;
        }
        .success-message { background-color: #e6ffe6; border: 1px solid #00cc00; color: #008000; }
        .error-message { background-color: #ffe6e6; border: 1px solid #cc0000; color: #cc0000; }
        
        footer { margin-top: 40px; padding: 20px 0; border-top: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>
    
    <header>
        <div class="container site-header">
            <div style="font-size: 1.5em; font-weight: bold; color: #333;">Humulani Pre School</div>
            
            <nav>
                <a href="index.php" class="nav-link">Home</a>
                <a href="about.php" class="nav-link">About Us</a>
                <a href="gallery.php" class="nav-link">Gallery</a>
                <a href="registration.php" class="nav-link">Admission</a>
                <a href="contact.php" class="nav-link">Contact</a>
                <a href="login.php" class="nav-link">Login</a>
            </nav>
        </div>
    </header>

    <div class="container">
        
        <h1>Get in Touch with Humulani</h1>

        <div class="contact-grid">
            
            <div class="contact-info">
                <h2>Find Us and Connect</h2>
                <p>We are always happy to hear from prospective parents. Connect with us on social media or reach out directly.</p>
                
                <ul>
                    <li>📞 **Phone:** (011) 555-HUMU</li>
                    <li>📧 **Email:** info@humulani.co.za</li>
                    
                    <li>📘 **Facebook:** <a href="https://facebook.com/HumulaniPreSchool" target="_blank">@HumulaniPreSchool</a></li>
                    
                    <li>📍 **Address:** 23 Mahube Street, Mamelodi East, 0122, South Africa</li>
                </ul>
                
                <h2 style="margin-top: 30px;">School Hours</h2>
                <p>Monday to Friday: 7:00 AM - 5:30 PM</p>
                
                <div style="height: 200px; background-color: #e9e9e9; border-radius: 5px; text-align: center; line-height: 200px; color: #777;">
                    
                    [Placeholder for Google Map Embed]
                </div>
            </div>
            
            <div class="contact-form-box">
                <h2>Send Us a Message</h2>
                
                <?php echo $message_status; // Display success/error message ?>

                <form action="contact.php" method="POST">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>
                    
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required>
                    
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>

                    <label for="message">Your Message:</label>
                    <textarea id="message" name="message" rows="6" required></textarea>

                    <button type="submit" class="submit-btn">Send Message</button>
                </form>
            </div>
            
        </div>

        <footer>
            <p>&copy; 2026 Humulani Pre School</p>
        </footer>
    </div>
</body>
</html>