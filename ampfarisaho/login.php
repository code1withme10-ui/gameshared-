<?php
session_start();
include __DIR__ . "/includes/functions.php";
include __DIR__ . "/includes/auth.php";

$parents = readJSON(__DIR__ . "/data/parents.json");
$headmaster = readJSON(__DIR__ . "/data/headmaster.json");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Headmaster login
    if (isset($headmaster['username'], $headmaster['password']) &&
        $username === $headmaster['username'] && $password === $headmaster['password']) {
        $_SESSION['headmaster'] = $username;
        header("Location: index.php?page=headmaster_dashboard");
        exit;
    }

    // Parent login
    foreach ($parents as $p) {
        if (isset($p['username'], $p['password']) &&
            $p['username'] === $username && $p['password'] === $password) {
            $_SESSION['parent'] = $username;
            header("Location: index.php?page=parent_dashboard");
            exit;
        }
    }

    $error = "Invalid username or password.";
}
?>

<link rel="stylesheet" href="public/css/style.css">
<?php include __DIR__ . "/includes/menu-bar.php"; ?>

<div class="container">
    <h2>Login</h2>
    <p>Parents and Headmaster login using <strong>Username + Password</strong>.</p>

    <?php if (!empty($error)): ?>
        <p style="color:red; font-weight:bold;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button class="button">Login</button>
    </form>
</div>

