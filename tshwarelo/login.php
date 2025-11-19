<?php
session_start();
$data_file = 'users.json'; // The file where all user data is stored

// ----------------------------------------------------
// Functions to read/write JSON data
// ----------------------------------------------------
function get_users($file) {
    if (!file_exists($file) || filesize($file) == 0) {
        return [];
    }
    
    $data = json_decode(file_get_contents($file), true);
    
    if (!is_array($data)) {
        return [];
    }
    
    return $data;
}
// ----------------------------------------------------

// Check if user is already logged in
if (isset($_SESSION['user_email'])) {
    if ($_SESSION['role'] === 'headmaster') {
        header('Location: headmaster_dashboard.php');
    } else {
        header('Location: dashboard.php');
    }
    exit;
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $users = get_users($data_file);
    $loggedIn = false;

    // Find the user and check credentials
    foreach ($users as $user) {
        if ($user['email'] === $email && $user['password'] === $password) {
            // Success! Store user info and their role in the session
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['parent_name'] = $user['parentName'];
            $_SESSION['role'] = $user['role'] ?? 'parent'; // Default to 'parent' if role is missing
            $loggedIn = true;
            break;
        }
    }

    if ($loggedIn) {
        // Redirect based on the user's role
        if ($_SESSION['role'] === 'headmaster') {
            header('Location: headmaster_dashboard.php');
        } else {
            header('Location: dashboard.php');
        }
        exit;
    } else {
        $message = '<p style="color: red;">Invalid email or password. Please try again.</p>';
    }
}

// PHP logic to check login status for the navbar
$is_logged_in = isset($_SESSION['user_email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    
    <div class="navbar">
        <span class="navbar-title">Humulani Pre School</span>
    <!-- Navigation -->
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>

            <?php if ($is_logged_in): ?>
                <a href="dashboard.php?action=logout">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="page-container">
        <h1>Login</h1>

        <?php echo $message; ?>

        <form method="POST" action="login.php">
            <label for="email">Email (Username):</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="admission.php">Register here</a></p>
            </div>

        <footer>
            <p>&copy; 2026 Humulani Pre School</p>
        </footer>
    </div>
</body>
</html>