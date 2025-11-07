<?php
// LOGIN PAGE: login.php

// CRITICAL: Start the session at the very top
session_start();

// --- 1. Define Valid Credentials (For demonstration purposes only) ---
// NOTE: In a real system, these would be securely hashed and fetched from a database.
$valid_username = 'parent';
$valid_password = 'password123'; 

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // --- 2. Check Credentials ---
    if ($username === $valid_username && $password === $valid_password) {
        // Successful login
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = 'parent'; 

        // Redirect to the secure portal page
        header('Location: welcome.php');
        exit;
    } else {
        // Failed login
        $login_error = 'Invalid username or password. Please try again.';
    }
}

// --- 3. If user is already logged in, redirect them immediately ---
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: welcome.php');
    exit;
}
// IMPORTANT: We omit the closing '?>' tag here to prevent the "headers already sent" error
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parent Login - Humulani Pre School</title>
    
    <style>
        /* --- CORE STYLES (Consistency with Homepage) --- */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes rainbowShine {
            0% { border-color: #ff0000; } 50% { border-color: #0000ff; } 100% { border-color: #ff0000; }
        }
        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background-color: #fcfcfc; text-align: left; }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; }
        .site-header {
            padding: 15px 0; background-color: #fff; border-bottom: 3px solid transparent; 
            animation: rainbowShine 8s infinite alternate; display: flex; 
            justify-content: space-between; align-items: center;
        }
        /* --- Navigation Links (UPDATED FOR registration.php) --- */
        .nav-link { text-decoration: none; font-weight: bold; margin: 0 10px; }
        .nav-link:nth-child(1) { color: #ff0000; } .nav-link:nth-child(2) { color: #ff9900; } 
        .nav-link:nth-child(3) { color: #008000; } .nav-link:nth-child(4) { color: #0000ff; } 
        .nav-link:nth-child(5) { color: #4b0082; } .nav-link:nth-child(6) { color: #ee82ee; } 

        /* --- LOGIN SPECIFIC STYLES --- */
        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
        }
        .login-box {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border-top: 5px solid #4b0082; /* Deep violet accent */
            animation: fadeIn 0.8s ease-out;
        }
        .login-box h1 {
            color: #4b0082;
            margin-bottom: 30px;
            font-size: 2em;
        }
        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .login-btn {
            background-color: #ff9900; /* Orange login button */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .login-btn:hover {
            background-color: #ffb347;
        }
        .error-message {
            padding: 10px;
            background-color: #ffe6e6; 
            border: 1px solid #cc0000; 
            color: #cc0000;
            border-radius: 5px;
            margin-bottom: 20px;
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
                <a href="registration.php" class="nav-link">Registration</a> 
                <a href="contact.php" class="nav-link">Contact</a>
                <a href="login.php" class="nav-link">Login</a>
            </nav>
        </div>
    </header>

    <div class="container login-wrapper">
        <div class="login-box">
            <h1>Parent Portal Login</h1>
            
            <?php if ($login_error): ?>
                <div class="error-message"><?php echo $login_error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit" class="login-btn">Secure Login</button>
            </form>
            
            <p style="margin-top: 20px; font-size: 0.9em; color: #777;">
                **Test Credentials:** Username: `parent`, Password: `password123`
            </p>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Humulani Pre School</p>
    </footer>
</body>
</html>l>