<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = htmlspecialchars($_SESSION["username"]);
$content = "
  <h2>Welcome, $username!</h2>
  <p>We’re glad to have you as part of Happy Kids Creche.</p>
  <a href='logout.php'>Logout</a>
";
include 'layout.php';
?>

