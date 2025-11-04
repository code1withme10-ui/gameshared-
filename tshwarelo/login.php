<?php
// CRITICAL: Start the session at the very top of the script
session_start();

// --- 1. Include Database Connection ---
// This file connects to the MySQL database container
include 'db_connect.php'; 

// --- 2. Define Helper Functions and Variables ---
$message = '';

// Check if the user is already logged in (redirect them away from the login page)
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Redirect to the welcome/dashboard page
    header('Location: welcome.php');
    exit;
}

// --- 3. Process Login Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get and sanitize the input data
    // Use trim() to remove leading/trailing spaces, just like in registration.php
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $message = '<p style="color:red;">Please enter both username and password.</p>';
    } else {
        
        try {
            // --- 4. Database Lookup (SELECT) ---
            // Prepare the SQL SELECT statement to fetch the user by username (email)
            $stmt = $pdo->prepare("SELECT password_hash, parent_name FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user) {
                // User found. Now verify the password hash.
                // NOTE: We assume registration.php is using password_hash() for security.
                if (password_verify($password, $user['password_hash'])) {
                    
                    // --- 5. Login successful: Set the session variables ---
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $username;
                    $_SESSION['user_name'] = $user['parent_name']; // Store the friendly name
                    
                    // Redirect to the welcome/dashboard page
                    header('Location: welcome.php');
                    exit;
                    
                } else {
                    // Password verification failed
                    $message = '<p style="color:red;">Invalid password.</p>';
                }
            } else {
                // User not found in the database
                $message = '<p style="color:red;">Username not found.</p>';
            }
        } catch (\PDOException $e) {
            // Handle database errors
            $message = '<p style="color:red;">A database error occurred during login. Please try again.</p>';
            error_log("Login PDO Error: " . $e->getMessage()); 
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
    <h2>User Login</h2>
    
    <?php echo $message; // Display any success or error messages ?>

    <form method="POST" action="login.php">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username ?? '') ?>" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Login">
    </form>
    
    <p>Don't have an account? <a href="registration.php">Register here</a>.</p>
    <footer>
        <p>&copy; 2025 Ndlovu's Crèche</p>
    </footer>
</body>
</html>
