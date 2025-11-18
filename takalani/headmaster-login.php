<?php
// PHP login logic remains the same (assuming you fixed the login functionality earlier)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'functions.php'; // Ensures access to find_headmaster()

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $user = find_headmaster($username); 

    if ($user && password_verify($password, $user['password'])) {
        
        $_SESSION["user"] = [
            "username" => $user['username'],
            "role" => $user['role']
        ];
        
        header("Location: headmaster.php");
        exit();

    } else {
        $error = "❌ Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Headmaster Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php require_once 'menu-bar.php'; ?>

<main>
    <h2 style="text-align:center;">Headmaster Login</h2>
    
    <?php if ($error): ?>
        <p style="color:red; text-align:center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" style="max-width:300px; margin:auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</main>

<?php include 'footer.php'; ?>

</body>
</html>