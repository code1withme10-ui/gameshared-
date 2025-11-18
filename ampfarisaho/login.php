<?php
session_start();
include "includes/functions.php";

$parents = readJSON("data/parents.json");
$headmaster = readJSON("data/headmaster.json");

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $headmaster['username'] && $password === $headmaster['password']) {
        $_SESSION['headmaster'] = $username;
        header("Location: headmaster_dashboard.php");
        exit;
    }

    foreach ($parents as $p) {
        if ($p['username'] == $username && $p['password'] == $password) {
            $_SESSION['parent'] = $username;
            header("Location: parent_dashboard.php");
            exit;
        }
    }
    $error = "Invalid username or password.";
}
?>
<link rel="stylesheet" href="css/style.css">
<?php include "includes/menu-bar.php"; ?>
<div class="container">
    <h2>Login</h2>
    <?php if(isset($error)) echo "<p style='color:red; font-weight:bold;'>$error</p>"; ?>
    <form method="POST">
        <input name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button class="button">Login</button>
    </form>
</div>
