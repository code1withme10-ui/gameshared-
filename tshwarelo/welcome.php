<?php
// WELCOME PAGE: welcome.php
session_start();

// --- CRITICAL SECURITY CHECK ---
// If the user is NOT logged in, redirect them immediately to the login page.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Retrieve the logged-in username for personalization
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to the Parent Portal - Humulani Pre School</title>
    
    <style>
        /* --- General Layout and Styles --- */
        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background-color: #fcfcfc; text-align: left; }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; }
        
        /* --- Portal Header --- */
        .portal-header {
            background-color: #4b0082; /* Deep violet */
            color: white;
            padding: 30px 0;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .portal-header h1 { margin: 0; font-size: 2.8em; }
        .portal-header p { margin: 5px 0 0 0; font-size: 1.2em; color: #ff9900; }
        
        /* --- Info Grid for Content --- */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        .info-card {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 5px solid; /* Defined by inline style below */
        }
        .info-card h3 { color: #4b0082; margin-top: 0; border-bottom: 2px dashed #eee; padding-bottom: 10px; }
        
        /* --- Logout Button --- */
        .logout-btn {
            display: inline-block;
            background-color: #ff0000;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-top: 20px;
            margin-bottom: 50px;
        }
        .logout-btn:hover { background-color: #cc0000; }
        
        footer { padding: 20px 0; border-top: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>
    
    <div class="portal-header">
        <div class="container">
            <h1>Parent Portal Dashboard</h1>
            <p>Welcome back, **<?php echo htmlspecialchars(ucfirst($username)); ?>**! Access your child's updates here.</p>
        </div>
    </div>

    <div class="container">
        
        <h2>Your Child's Information & Daily Updates</h2>
        
        <div class="info-grid">
            
            <div class="info-card" style="border-color: #ff9900;">
                <h3>Today's Schedule</h3>
                <ul>
                    <li>**7:30 AM:** Arrival & Free Play</li>
                    <li>**9:00 AM:** Circle Time & Storytelling</li>
                    <li>**10:30 AM:** Snack & Outdoor Activity</li>
                    <li>**12:00 PM:** Lunch Time & Nap</li>
                    <li>**2:30 PM:** Creative Arts & Music</li>
                </ul>
            </div>
            
            <div class="info-card" style="border-color: #47b3ff;">
                <h3>Important Documents</h3>
                <p>Download your personalized fee statement and school calendar.</p>
                <p><a href="documents/fees-2026-parent.pdf" target="_blank">Fee Statement (Q1 2026)</a></p>
                <p><a href="documents/calendar-2026.pdf" target="_blank">School Calendar 2026</a></p>
            </div>

            <div class="info-card" style="border-color: #ff0077;">
                <h3>Teacher's Latest Note</h3>
                <blockquote style="border-left: 3px solid #ff0077; padding-left: 10px; margin: 15px 0; font-style: italic;">
                    "We observed wonderful sharing during block time today! Your child ate all their carrots at lunch. Keep up the great work!"
                </blockquote>
                <p style="font-size: 0.9em; text-align: right;">— Ms. Jane (Pre-K Teacher)</p>
            </div>
        </div>

        <a href="logout.php" class="logout-btn">Log Out of Portal</a> 
    </div>
    
    <footer>
        <div class="container">
            <p>&copy; 2026 Humulani Pre School Parent Portal</p>
        </div>
    </footer>
</body>
</html>
🚪 Final Step: The Logout Page (logout.php)
For security, the portal needs a way to securely end the session.

Please create a second small file named logout.php in your main project directory:

PHP

<?php
// LOGOUT PAGE: logout.php

session_start();

// Unset all session variables
$_SESSION = array();

// If it's desired to kill the session cookie as well
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();

// Redirect back to the login page after logging out
header('Location: login.php');
exit;
?>