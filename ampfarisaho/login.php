<?php
session_start();
include __DIR__ . "/includes/functions.php";

// Load JSON files
$parents = readJSON(__DIR__ . "/data/parents.json");
$headmaster = readJSON(__DIR__ . "/data/headmaster.json");

// Ensure $parents is always an array
if (!is_array($parents)) {
    $parents = [];
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ----- HEADMASTER LOGIN -----
    if (isset($headmaster['username'], $headmaster['password'])) {
        if ($username === $headmaster['username'] && $password === $headmaster['password']) {
            $_SESSION['headmaster'] = $username;
            header("Location: headmaster_dashboard.php");
            exit;
        }
    }

    // ----- PARENT LOGIN -----
    foreach ($parents as $p) {
        if (isset($p['username'], $p['password'])) {
            if ($p['username'] === $username && $p['password'] === $password) {
                $_SESSION['parent'] = $username;
                header("Location: parent_dashboard.php");
                exit;
            }
        }
    }

    $error = "Invalid username or password.";
}
?>

<link rel="stylesheet" href="/public/css/style.css">
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

