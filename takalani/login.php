<?php
session_start();

// Correct file names
$headmastersFile = __DIR__ . '/headmaster-login.json';
$usersFile = __DIR__ . '/users.json';

// Load files
$headmasters = file_exists($headmastersFile) ? json_decode(file_get_contents($headmastersFile), true) : [];
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // --------------------------------------
    // 🔍 1. Check HEADMASTER LOGIN FIRST
    // --------------------------------------
    foreach ($headmasters as $hm) {
        if (strcasecmp($hm["username"], $username) === 0) {

            if (password_verify($password, $hm["password"])) {

                // Set session
                $_SESSION["user"] = [
                    "username" => $hm["username"],
                    "role" => "headmaster"
                ];

                header("Location: headmaster.php");
                exit();
            }

            // Wrong password for headmaster
            $error = "❌ Invalid username or password.";
            break;
        }
    }

    // --------------------------------------
    // 🔍 2. Check PARENT LOGIN
    // --------------------------------------
    foreach ($users as $user) {
        if (strcasecmp($user["username"], $username) === 0) {

            if (password_verify($password, $user["password"])) {

                $_SESSION["user"] = [
                    "username" => $user["username"],
                    "role" => $user["role"] ?? "parent",
                    "parentName" => $user["parentName"] ?? "",
                    "parentSurname" => $user["parentSurname"] ?? "",
                    "childName" => $user["childName"] ?? "",
                    "childSurname" => $user["childSurname"] ?? "",
                    "childAge" => $user["childAge"] ?? "",
                    "email" => $user["email"] ?? "",
                    "phone" => $user["contact"] ?? ""
                ];

                header("Location: parent.php");
                exit();
            }

            $error = "❌ Invalid username or password.";
            break;
        }
    }

    // --------------------------------------
    // If no match
    // --------------------------------------
    if (!$error) {
        $error = "❌ Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Login - SubixStar Pre-School</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
<?php require_once 'menu-bar.php'; ?>
<main style="text-align:center; margin-top:40px;">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" style="display:inline-block; text-align:left;">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p style="margin-top:10px;">Don't have an account? <a href="registration.php">Register here</a></p>
</main>
</body>
</html>
