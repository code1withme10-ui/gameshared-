<?php
// WELCOME/DASHBOARD PAGE: welcome.php

session_start();

// Check if the user is NOT logged in. If not, redirect them to the login page.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Retrieve user data from the session
$parent_name = htmlspecialchars($_SESSION['parent_name'] ?? 'Parent');
$username = htmlspecialchars($_SESSION['username'] ?? 'user');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome - Parent Portal Dashboard</title>
    <style>
        /* General Styles */
        body { font-family: 'Arial', sans-serif; background-color: #f4f4f9; margin: 0; padding: 0; }
        .header-bar {
            background-color: #4b0082; /* Deep Purple */
            color: white;
            padding: 30px 0;
            text-align: center;
        }
        .header-bar h1 { margin: 0; font-size: 2.5em; }
        .header-bar p { margin: 5px 0 0; font-size: 1.1em; color: #ff9900; /* Orange accent */ }

        /* Dashboard Layout */
        .dashboard-content {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .section-title {
            color: #4b0082;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #ff0077;
            display: inline-block;
            padding-bottom: 5px;
            font-size: 1.8em;
            width: 100%;
        }

        /* Cards */
        .card-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 40px;
        }
        .card {
            flex: 1;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-left: 5px solid;
            text-align: left;
        }
        .card:nth-child(1) { border-left-color: #ff9900; } /* Orange */
        .card:nth-child(2) { border-left-color: #008000; } /* Green */
        .card:nth-child(3) { border-left-color: #ff0077; } /* Pink */

        .card h3 { color: #333; margin-top: 0; margin-bottom: 15px; }
        .card ul { list-style: none; padding: 0; }
        .card ul li { margin-bottom: 8px; }
        .card a { display: block; margin-top: 5px; color: #008000; text-decoration: none; font-weight: bold; }

        /* Logout Button */
        .btn-logout {
            padding: 15px 30px;
            background-color: #ff0000;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block;
            width: 250px;
            margin: 0 auto;
            text-align: center;
        }
        .btn-logout:hover { background-color: #cc0000; }

        footer { text-align: center; padding: 20px 0; border-top: 1px solid #ddd; margin-top: 40px; color: #666; }
    </style>
</head>
<body>
    <div class="header-bar">
        <h1>Parent Portal Dashboard</h1>
        <p>Welcome back, **<?php echo $username; ?>**! Access your child's updates here.</p>
    </div>

    <div class="dashboard-content">
        <h2 class="section-title">Your Child's Information & Daily Updates</h2>

        <div class="card-container">
            <div class="card">
                <h3>Today's Schedule</h3>
            </div>

            <div class="card">
                <h3>Important Documents</h3>
                <p>Download your personalized fee statement and school calendar here.</p>
            </div>

            <div class="card">
                <h3>Teacher's Latest Note</h3>
            </div>
        </div>
        
        <a href="logout.php" class="btn-logout">Log Out of Portal</a>

    </div>

    <footer>
        <p>&copy; 2026 Humulani Pre School Parent Portal</p>
    </footer>
</body>
</html>