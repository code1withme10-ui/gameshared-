<?php
// index.php - HOME PAGE

// CRITICAL: Start the session at the very top for login functionality
session_start();

// --- 1. If the user is logged in, redirect them to the secure portal ---
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: welcome.php'); // Redirect to secure parent area
    exit;
}

// --- 2. If not logged in, proceed to display the public marketing content ---

// Removed include 'menu-bar.php' as navigation is directly in the HTML.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Humulani Pre School Home - Your Child's Journey</title> 
    <style>
        /* --- 1. KEYFRAME ANIMATIONS (For Modern/Animated Feel) --- */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes rainbowShine {
            0% { border-color: #ff0000; }
            50% { border-color: #0000ff; }
            100% { border-color: #ff0000; }
        }

        /* --- 2. BASE AND LAYOUT --- */
        body { 
            font-family: 'Poppins', sans-serif; 
            margin: 0; padding: 0; 
            background-color: #fcfcfc; 
            text-align: left; 
        }
        .container { 
            max-width: 1100px; 
            margin: 0 auto; 
            padding: 0 20px; 
        }

        /* --- 3. HEADER & NAVIGATION (Rainbow Colors) --- */
        .site-header {
            padding: 15px 0; 
            background-color: #fff; 
            border-bottom: 3px solid transparent; 
            animation: rainbowShine 8s infinite alternate;
            display: flex; 
            justify-content: space-between; 
            align-items: center;
        }
        .nav-link {
            text-decoration: none;
            font-weight: bold;
            margin: 0 10px;
        }
        .nav-link:nth-child(1) { color: #ff0000; } /* Red */
        .nav-link:nth-child(2) { color: #ff9900; } /* Orange */
        .nav-link:nth-child(3) { color: #008000; } /* Green */
        .nav-link:nth-child(4) { color: #0000ff; } /* Blue */ /* Registration */
        .nav-link:nth-child(5) { color: #4b0082; } /* Indigo */ /* Contact */
        .nav-link:nth-child(6) { color: #ee82ee; } /* Violet */ /* Login */

        /* --- 4. HERO SECTION (Welcome Messages) --- */
        .hero { 
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%); 
            padding: 80px 40px; 
            margin-bottom: 40px; 
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease-out;
        }
        .hero h2 { color: #333; font-size: 2.2em; margin-bottom: 10px; }
        .cta-btn { 
            display: inline-block; 
            background-color: #4b0082; 
            color: white; 
            padding: 12px 25px; 
            text-decoration: none; 
            border-radius: 30px; 
            margin-top: 15px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .cta-btn:hover { background-color: #6a0dad; }

        /* --- 5. INFO CARDS (Hours and Fees) --- */
        .info-card-grid {
            /* Adjusted for two cards only */
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); 
            gap: 30px; 
            margin-bottom: 40px;
        }
        .info-card {
            background-color: #fff;
            padding: 30px; 
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-left: 5px solid;
            height: 100%; 
        }
        .info-card:nth-child(1) { border-color: #ffb347; } /* Orange/Sunshine */
        .info-card:nth-child(2) { border-color: #47b3ff; } /* Blue */
        
        /* --- 6. STYLES FOR PHILOSOPHY AND LISTS --- */
        .philosophy-box {
            background-color: #f7f9ff; 
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
            border-left: 5px solid #ff0077; 
            animation: fadeIn 1s ease-out 0.2s; 
        }
        .philosophy-box h3 {
            color: #4b0082; 
            border-bottom: 2px solid #ff9900; 
            padding-bottom: 5px;
            margin-top: 0;
        }
        .content-section ul {
            list-style: none; 
            padding-left: 0;
        }
        .content-section ul li {
            padding-left: 25px;
            margin-bottom: 10px;
            font-size: 1.1em;
            position: relative;
            color: #333;
        }
        .content-section ul li::before {
            content: '🌈'; /* Custom Rainbow Checkmark */
            position: absolute;
            left: 0;
            top: 0;
            font-size: 0.9em;
        }

        footer { 
            margin-top: 40px; padding: 20px 0; border-top: 1px solid #ddd; text-align: left; 
        }
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
                <a href="registration.php" class="nav-link">Admission</a> <a href="contact.php" class="nav-link">Contact</a>
                <a href="login.php" class="nav-link">Login</a>
            </nav>
        </div>
    </header>

    <div class="container">
        
        <h1 style="
            color: #ff9900; /* Sunshine Orange */
            font-size: 2.5em; 
            margin-top: 30px; 
            margin-bottom: 20px;
        ">Welcome to Humulani Pre School</h1>

        <div class="hero">
            <h2>Your Child's Journey Starts with Joy at Humulani.</h2>
            <p>Building a strong foundation for future success through daily exploration.</p>
            <a href="registration.php" class="cta-btn">Register Your Child Today!</a>
        </div>

        <h2 style="margin-bottom: 20px;">Quick Info</h2>
        <div class="info-card-grid">
            
            <div class="info-card">
                <h3>🕰️ Caring Hours</h3>
                <ul>
                    <li>**Full Day:** 7:00 AM - 5:30 PM</li>
                    <li>**Half Day:** 7:00 AM - 1:00 PM</li>
                    <li>*Closed on Public Holidays*</li>
                </ul>
            </div>
            
            <div class="info-card">
                <h3>💰 School Fees</h3>
                <p>Our fees are competitive and all-inclusive of meals and materials.</p>
                <a href="documents/fee_structure_2026.pdf" download class="cta-btn" style="background-color: #ff9900; padding: 8px 15px; font-size: 0.9em;">
                    Download Fee Structure 
                </a>
            </div>
            
        </div>
        
        <div class="content-section">
            
            <div class="philosophy-box">
                <h3>Our Philosophy</h3>
                <p>At Humulani Pre School, we believe in nurturing the whole child—fostering curiosity, creativity, and confidence through play-based learning in a safe, supportive environment.</p>
            </div>
            
            <h3>Why Choose Us?</h3>
            <ul>
                <li>Experienced and caring staff.</li>
                <li>Nutritious meals prepared daily.</li>
                <li>Secure, modern facilities.</li>
                <li>Curriculum focused on early developmental milestones.</li>
            </ul>
            <p>Ready to learn more? Check out our <a href="about.php" style="color: #4b0082; font-weight: bold;">About Us</a> page.</p>
        </div>

        <footer>
            <p>&copy; 2026 Humulani Pre School</p>
        </footer>
    </div>
</body>
</html>