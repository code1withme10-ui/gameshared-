<?php
session_start();
$data_file = "users.json";

// -------------------- Load Users --------------------
function get_users($file) {
    if (!file_exists($file) || filesize($file) == 0) return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

$message = "";

// -------------------- If form submitted --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $users = get_users($data_file);
    $loggedIn = false;

    foreach ($users as $user) {

        // ---------- Skip Admission Applications ----------
        if (isset($user['applicationID'])) {
            continue;
        }

        // ---------- Headmaster Login ----------
        if (isset($user['role']) && $user['role'] === 'headmaster') {
            if ($user['email'] === $email && $user['password'] === $password) {

                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = "headmaster";
                $_SESSION['name'] = $user['parentName'];

                header("Location: headmaster_dashboard.php");
                exit();
            }
        }

        // ---------- Parent Login ----------
        if (isset($user['email'], $user['password'], $user['parentName'])) {
            if ($user['email'] === $email && $user['password'] === $password) {

                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = "parent";
                $_SESSION['name'] = $user['parentName'];

                header("Location: dashboard.php");
                exit();
            }
        }
    }

    // If no match found
    $message = "<p style='color:red;'>Invalid email or password.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Humulani Pre School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVIGATION BAR -->
<div class="navbar">
    <span class="navbar-title">Humulani Pre School</span>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
</div>

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
