<?php
// ABOUT US PAGE: about.php

// CRITICAL: Start the session at the very top
session_start();

// Include the Navigation Bar directly (as we did for the finalized index.php)
// Note: We are deliberately NOT using include 'menu-bar.php' to avoid the session error.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Humulani Pre School</title>
    
    <style>
        /* --- 1. KEYFRAME ANIMATIONS --- */
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
        h1 {
            color: #4b0082; /* Deep violet for main headings */
            font-size: 2.8em;
            margin-top: 30px;
            border-bottom: 3px solid #ff9900; /* Sunshine orange divider */
            padding-bottom: 10px;
            margin-bottom: 40px;
        }

        /* --- 3. HEADER & NAVIGATION (Copied from index.php) --- */
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
        .nav-link:nth-child(1) { color: #ff0000; } 
        .nav-link:nth-child(2) { color: #ff9900; } 
        .nav-link:nth-child(3) { color: #008000; } 
        .nav-link:nth-child(4) { color: #0000ff; } 
        .nav-link:nth-child(5) { color: #4b0082; } 
        .nav-link:nth-child(6) { color: #ee82ee; } 

        /* --- 4. ABOUT PAGE SPECIFIC STYLES --- */
        .about-section {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border-left: 5px solid #008000; /* Green accent */
            animation: fadeIn 1s ease-out;
        }
        .about-section h2 {
            color: #ff0077; /* Pink accent for subheadings */
            margin-top: 0;
            margin-bottom: 15px;
        }
        .founder-box {
            display: flex;
            align-items: center;
            background-color: #f0f8ff; /* Soft blue background */
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .founder-img {
            width: 150px;
            height: 150px;
            border-radius: 50%; /* Circle image */
            margin-right: 20px;
            object-fit: cover;
            border: 4px solid #47b3ff; /* Blue border */
        }
        .sponsor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 30px;
            margin-top: 20px;
            align-items: center;
        }
        .sponsor-logo {
            width: 100%;
            height: auto;
            max-width: 150px;
            filter: grayscale(80%); /* Makes logos look professional/subtle */
            opacity: 0.8;
            transition: filter 0.3s, opacity 0.3s;
        }
        .sponsor-logo:hover {
            filter: grayscale(0%);
            opacity: 1;
        }
        
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
                <a href="registration" class="nav-link">Admission</a>
                <a href="contact.php" class="nav-link">Contact</a>
                <a href="login.php" class="nav-link">Login</a>
            </nav>
        </div>
    </header>

    <div class="container">
        
        <h1>About Humulani Pre School</h1>

        <div class="about-section">
            <h2>📜 School History</h2>
            <p>Humulani Pre School was founded in 2021 with a commitment to providing high-quality early childhood education in a supportive community setting. Initially operating from a small community hall, we grew rapidly, driven by parental demand for our unique play-based approach.</p>
            <p>In 2024, thanks to generous community support and our primary sponsor, we moved into our current secure, modern facility, allowing us to expand our curriculum and capacity. We are proud of our journey and remain dedicated to every child's bright start.</p>
        </div>
        
        <div class="about-section" style="border-left: 5px solid #ff9900;"> <h2>👑 Our Founder</h2>
            <div class="founder-box">
                
                <img src="images/founder-image.jpg" alt="Photo of the Founder" class="founder-img">
                <div>
                    <h3>Tshwarelo Treat Ndlovu (TUT Student)</h3>
                    <p>Mr Ndlovu established Humulani with a vision of creating a space where learning is an adventure and curiosity is celebrated. His philosophy centers on holistic development, ensuring every child leaves Humulani confident and ready for primary school. She continues to oversee our curriculum standards.</p>
                </div>
            </div>
        </div>

        <div class="about-section" style="border-left: 5px solid #ff0077;"> <h2>🤝 Our Community Sponsors</h2>
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