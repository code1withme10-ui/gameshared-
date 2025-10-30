<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $file = "users.json";
    $users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION["user"] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "❌ Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
<p style="color:red;"><?= $error ?? '' ?></p>
<p>Don't have an account? <a href="registration.php">Register here</a></p>
</body>
</html>
