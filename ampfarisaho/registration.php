<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $line = "$name,$email,$password\n";
    file_put_contents("users.txt", $line, FILE_APPEND);

    header("Location: confirm.php?name=" . urlencode($name));
    exit;
}

$content = '
  <h2>Register</h2>
  <form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
  </form>
';
include 'includes/layout.php';
?>


