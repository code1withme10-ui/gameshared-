<?php
// CRITICAL: Start the session at the very top of the script
session_start();

// Define the path to the JSON file
$json_file = 'users.json';
$message = '';

// Check if the user is already logged in (redirect them away from the login page)
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Redirect to the home page or a dashboard page
    header('Location: index.php');
    exit;
}

// Check if the login form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Get and sanitize the input data using $_POST
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $message = '<p style="color:red;">Please enter both username and password.</p>';
    } else {
        
        // 2. Read the existing users from the JSON file
        $users_data = file_get_contents($json_file);
        $users = json_decode($users_data, true) ?? [];
        
        // 3. Verify credentials
        
        // Check if the username exists in our array
        if (isset($users[$username])) {
            
            // Check if the submitted password matches the stored password
            // NOTE: In a real app, you would use password_verify() on a HASHED password.
            if ($users[$username] === $password) {
                
                // 4. Login successful: Set the session variables
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                
                // Redirect to the home page after a successful login
                header('Location: index.php');
                exit;
                
            } else {
                $message = '<p style="color:red;">Invalid password.</p>';
            }
        } else {
            $message = '<p style="color:red;">Username not found.</p>';
        }
    }
}

// Include your menu/header file
include 'menu-bar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
</head>
<body>
    <h1>User Login</h1>
    
    <?php echo $message; // Display any success or error messages ?>

    <form method="POST" action="login.php">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Login">
    </form>
    
    <p>Don't have an account? <a href="registration.php">Register here</a>.</p>
    
       <h1>Welcome to Ndlovu's Kids Creche</h1>
</body>
</html>