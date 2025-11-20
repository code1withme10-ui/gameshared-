<?php
// Simple password for admin access
$ADMIN_PASSWORD = "ChangeThisPass!"; // change this

session_start();

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_pass'])) {
    if ($_POST['admin_pass'] === $ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = "Invalid password.";
    }
}

$logged_in = !empty($_SESSION['admin_logged_in']);

// Show login form if not logged in
if (!$logged_in) {
    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Admin Login</title>
        <link rel="stylesheet" href="forms/style.css">
    </head>
    <body>
        <main class="container">
            <h1>Admin Login</h1>
            <?php if (!empty($login_error)) : ?>
                <div class="notice error"><?= htmlspecialchars($login_error) ?></div>
            <?php endif; ?>
            <form method="post">
                <label for="admin_pass">Password</label>
                <input id="admin_pass" name="admin_pass" type="password" required>
                <button type="submit">Login</button>
            </form>
            <p><a href="index.php">Back to form</a></p>
        </main>
    </body>
    </html>
    <?php
    exit;
}

// Logged in: read JSON file
$json_file = "data/progress.json";
$entries = [];

if (file_exists($json_file)) {
    $json_data = file_get_contents($json_file);
    $entries = json_decode($json_data, true);
    if (!$entries) {
        $entries = [];
    }
}

// Handle JSON download
if (isset($_GET['download']) && file_exists($json_file)) {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="progress.json"');
    readfile($json_file);
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Developer Progress</title>
    <link rel="stylesheet" href="forms/style.css">
</head>
<body>
<main class="container">
    <h1>Developer Progress Entries</h1>

    <p>
        <a href="?download=1">Download JSON</a> |
        <a href="?action=logout">Logout</a> |
        <a href="index.php">Back to form</a>
    </p>

    <?php if (empty($entries)) : ?>
        <div class="notice">No entries yet.</div>
    <?php else : ?>
        <table class="entries">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Project</th>
                    <th>Tasks</th>
                    <th>Hours</th>
                    <th>Status</th>
                    <th>Challenges</th>
                    <th>Need Help</th>
                    <th>Help Details</th>
                    <th>Productivity</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $entry) : ?>
                    <tr>
                        <?php foreach ($entry as $value) : ?>
                            <td><?= nl2br(htmlspecialchars($value)) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
</body>
</html>
