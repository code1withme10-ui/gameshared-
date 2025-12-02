<?php
 
require_once 'functions.php'; // Use your helper functions

// CRITICAL FIX: Robust session start (required because this is a primary page)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $headmastersFile and $usersFile are no longer needed, using constants from functions.php

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // ------------------------------
    // CHECK PARENT LOGIN ONLY
    // ------------------------------
    $user = find_user_by_username($username); // Uses helper from functions.php

    if ($user) {
        if (password_verify($password, $user["password"])) {
            
            // Session is already started at the top
            $_SESSION["user"] = [
                "username"      => $user["username"],
                "role"          => $user["role"] ?? "parent", // Default to parent
                "parentName"    => $user["parentName"] ?? "",
                "parentSurname" => $user["parentSurname"] ?? "",
                "email"         => $user["email"] ?? "",
                "phone"         => $user["contact"] ?? ""
            ];

            // CRITICAL FIX: Redirect to router-friendly clean URL /parent
            header("Location: /parent");
            exit();
        }
    }
    
    // If user not found or password incorrect
    $error = "❌ Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Login - SubixStar Pre-School</title>
  <link rel="stylesheet" href="public/css/styles.css" />
</head>
<body>

<?php 
// SYNTAX FIX: Corrected mismatched quote: '../app/menu-bar.php' changed to "../app/menu-bar.php"
require_once "../app/menu-bar.php"; 
?>

<main style="text-align:center; margin-top:40px;">
    <h2>Parent Login</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" style="display:inline-block; text-align:left;">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p style="margin-top:10px;">
        Don't have an account? <a href="registration.php">Register here</a>
    </p>
    <p style="margin-top:10px;">
        Are you a Headmaster? <a href="headmaster-login.php">Login here</a>
    </p>
</main>

<?php 
    if (file_exists('footer.php')) {
        include 'footer.php'; 
    }
?>
</body>
</html>