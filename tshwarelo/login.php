<?php
session_start();
// Path to your JSON data
$data_file = __DIR__ . '/storage/users.json';

// Load users from JSON
function get_users($file) {
    if (!file_exists($file) || filesize($file) == 0) return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $users = get_users($data_file);

    foreach ($users as $user) {

 // ---------- Headmaster Login ----------
if (isset($user['role']) && $user['role'] === 'headmaster') {
    if ($user['email'] === $email && $user['password'] === $password) { // plain text password
        session_start();
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = "headmaster";
        $_SESSION['name'] = $user['name'] ?? "Headmaster"; // use 'name' field from JSON
        header("Location: index.php?page=headmaster_dashboard");
        exit;
    }
}
        // ---------- Parent Login (old structure) ----------
        if (isset($user['email'], $user['password'], $user['parentName'])) {
            if ($user['email'] === $email && $user['password'] === $password) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = "parent";
                $_SESSION['name'] = $user['parentName'];
                header("Location: index.php?page=dashboard");
                exit();
            }
        }

        // ---------- Parent Login (new structure) ----------
        if (isset($user['parent']['email'], $user['parent']['password'])) {
            if ($user['parent']['email'] === $email && $user['parent']['password'] === $password) {
                $_SESSION['email'] = $user['parent']['email'];
                $_SESSION['role'] = "parent";
                $_SESSION['name'] = $user['parent']['name'];
                header("Location: index.php?page=dashboard");
                exit();
            }
        }
    }

    // If no match
    $message = "<p style='color:red;'>Invalid email or password.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Humulani Pre School</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="page-container">
    <h1>Login</h1>
    <?php echo $message; ?>

    <form method="POST" action="">
        <label>Email (Username):</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</div>

<!-- FOOTER -->
<footer>
    <p>© 2026 Humulani Pre School</p>
</footer>

</body>
</html>
