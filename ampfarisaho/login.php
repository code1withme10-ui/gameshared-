<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $users = file("users.txt", FILE_IGNORE_NEW_LINES);

    foreach ($users as $user) {
        list($name, $storedEmail, $storedPass) = explode(",", $user);
        if ($email == $storedEmail && $password == trim($storedPass)) {
            $_SESSION["username"] = $name;
            header("Location: welcome.php");
            exit;
        }
    }
    $error = "Invalid email or password.";
}

$content = '
  <h2>Login</h2>
  <form method="POST">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
  </form>
  <p style="color:red;">' . $error . '</p>
';
include 'includes/layout.php';
?>

