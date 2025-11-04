<?php
$name = htmlspecialchars($_GET["name"] ?? "User");
$content = "
  <h2>Registration Successful!</h2>
  <p>Thank you, $name. Your registration has been received.</p>
  <p><a href='login.php'>Click here to Login</a></p>
";
include 'includes/layout.php';
?>

