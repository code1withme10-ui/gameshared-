<?php
// LOGIN PAGE: login.php

// CRITICAL: Start the session
session_start();

// Check if the user is already logged in, if so, redirect them to the welcome page
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: welcome.php');
    exit;
}

// Include database connection file (must define the $pdo object)
require_once 'db_connect.php'; 

// Initialize variables
$username = $password = "";
$username_err = $password_err = $login_err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validate input fields
    
    // Check if username is empty
    if (empty(trim($_POST['username'] ?? ''))) {
        $username_err = "Please enter username (email).";
    } else {
        $username = trim($_POST['username']);
    }
    
    // Check if password is empty
    if (empty(trim($_POST['password'] ?? ''))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST['password']);
    }

    // 2. Validate credentials if no errors in input
    if (empty($username_err) && empty($password_err)) {
        // SQL to retrieve the user's hashed password and other details
        $sql = "SELECT id, username, password_hash, parent_name FROM users WHERE username = :username";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind the username parameter
            $stmt->bindValue(':username', $username);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $hashed_password = $user['password_hash'];
                    
                    // 3. Verify the password
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, start a new session
                        $_SESSION['logged_in'] = true;
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['parent_name'] = $user['parent_name'];
                        $_SESSION['user_type'] = 'parent';

                        // Redirect user to welcome page
                        header('Location: welcome.php');
                        exit;
                    } else {
                        $login_err = "Invalid username or password.";
                    }
                } else {
                    $login_err = "Invalid username or password.";
                }
            } else {
                $login_err = "Oops! Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parent Portal Login - Humulani Pre School</title>
    
    <style>
        /* ADDED CSS for header consistency */
        @keyframes rainbowShine {
            0% { border-color: #ff0000; } 50% { border-color: #0000ff; } 100% { border-color: #ff0000; }
        }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; }
        .site-header { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; background-color: #fff; border-bottom: 3px solid transparent; animation: rainbowShine 8s infinite alternate; }
        .nav-link { text-decoration: none; font-weight: bold; margin: 0 10px; }
        .nav-link:nth-child(1) { color: #ff0000; } .nav-link:nth-child(2) { color: #ff9900; } .nav-link:nth-child(3) { color: #008000; } 
        .nav-link:nth-child(4) { color: #0000ff; } .nav-link:nth-child(5) { color: #4b0082; } .nav-link:nth-child(6) { color: #ee82ee; } 
        footer { margin-top: 40px; padding: 20px 0; border-top: 1px solid #ddd; text-align: left; }
        /* END ADDED CSS */


        body { font-family: 'Poppins', sans-serif; background-color: #f0f2f5; margin: 0; padding-top: 50px; /* Added padding to move content down */ }
        .login-center-container { display: flex; justify-content: center; align-items: center; min-height: 80vh; }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 { color: #4b0082; margin-bottom: 25px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        label { display: block; font-weight: bold; color: #333; margin-bottom: 5px; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }
        .btn-secure {
            width: 100%;
            padding: 12px;
            background-color: #ff9900;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-secure:hover { background-color: #ffb347; }
        .alert-danger {
            color: #cc0000;
            background-color: #ffe6e6;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #cc0000;
        }
        .help-text { font-size: 0.9em; color: #666; margin-top: 15px; }
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
    <div class="login-center-container">
        <div class="login-container">
            <h2>Parent Portal Login</h2>
            
            <?php 
            if (!empty($login_err)) {
                echo '<div class="alert-danger">' . $login_err . '</div>';
            }        
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                
                <div class="form-group">
                    <label for="username">Email Address:</label>
                    <input type="text" id="username" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                    <span style="color: red; font-size: 0.9em;"><?php echo $username_err; ?></span>
                </div>    
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span style="color: red; font-size: 0.9em;"><?php echo $password_err; ?></span>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn-secure">Secure Login</button>
                </div>
                
                <p class="help-text">Don't have an account? <a href="registration.php">Apply for Admission here</a>.</p>
            </form>
        </div>
    </div>
    
    <footer>
        <div class="container">
            <p>&copy; 2026 Humulani Pre School</p>
        </div>
    </footer>
    </body>
</html>