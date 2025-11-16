<?php
session_start();
include "includes/functions.php";

$hm = readJSON("data/headmaster.json");

if ($_POST) {
    if ($_POST['username'] == $hm['username'] &&
        $_POST['password'] == $hm['password']) {

        $_SESSION['headmaster'] = "logged_in";
        header("Location: headmaster_dashboard.php");
        exit;
    }

    echo "<p style='color:red;'>Invalid login.</p>";
}
?>

<link rel="stylesheet" href="css/style.css">
<?php include "includes/menu-bar.php"; ?>

<div class="container">
    <h2>Headmaster Login</h2>
    <form method="POST">
        <input name="username" required placeholder="Username"><br><br>
        <input type="password" name="password" required placeholder="Password"><br><br>
        <button class="button">Login</button>
    </form>
</div>
