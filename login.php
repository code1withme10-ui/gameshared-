<?php
session_start();

$headmastersFile = __DIR__ . '/headmaster-login.json';
$usersFile = __DIR__ . '/users.json';

$headmasters = file_exists($headmastersFile) ? json_decode(file_get_contents($headmastersFile), true) : [];
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $foundUser = null;

    // Check headmasters
    foreach ($headmasters as $headmaster) {
        if (isset($headmaster["username"]) && strcasecmp($headmaster["username"], $username) === 0) {
            $foundUser = $headmaster;
            break;
        }
    }

    // Check parents
    if (!$foundUser) {
        foreach ($users as $user) {
            if (isset($user["username"]) && strcasecmp($user["username"], $username) === 0) {
                $foundUser = $user;
                break;
            }
        }
    }

    if ($foundUser && password_verify($password, $foundUser["password"])) {
        // Set session
        $_SESSION["user"] = [
            "username"   => $foundUser["username"],
            "role"       => $foundUser["role"] ?? "parent",
            "parentName" => $foundUser["parentName"] ?? "",
            "parentSurname" => $foundUser["parentSurname"] ?? "",
            "childName"  => $foundUser["childName"] ?? "",
            "childSurname" => $foundUser["childSurname"] ?? "",
            "childAge"   => $foundUser["childAge"] ?? "",
            "email"      => $foundUser["email"] ?? "",
            "phone"      => $foundUser["contact"] ?? ""
        ];

        if ($foundUser["role"] === "headmaster") {
            header("Location: headmaster.php");
        } else {
            header("Location: parent.php");
        }
        exit();
    } else {
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
