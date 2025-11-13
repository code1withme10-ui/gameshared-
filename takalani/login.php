<?php session_start();

// Define file paths
$headmastersFile = __DIR__ . '/headmasters.json';
$usersFile = __DIR__ . '/users.json';

// Read JSON files
$headmasters = file_exists($headmastersFile) ? json_decode(file_get_contents($headmastersFile), true) : [];
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $foundUser = null;

    // First, check headmasters.json
    foreach ($headmasters as $headmaster) {
        if (strcasecmp($headmaster["username"], $username) === 0) {
            $foundUser = $headmaster;
            break;
        }
    }

    // If not found in headmasters, check users.json
    if (!$foundUser) {
        foreach ($users as $user) {
            if (strcasecmp($user["username"], $username) === 0) {
                $foundUser = $user;
                break;
            }
        }
    }

    // Verify password
    if ($foundUser && password_verify($password, $foundUser["password"])) {
        $_SESSION["user"] = [
            "username"   => $foundUser["username"],
            "firstName" => $foundUser["firstName"] ?? "",
            "lastName"  => $foundUser["lastName"] ?? "",
            "role"      => $foundUser["role"],
            "parentName"=> $foundUser["parentName"] ?? "",
            "childName" => $foundUser["childName"] ?? "",
            "childAge"  => $foundUser["childAge"] ?? "",
            "email"     => $foundUser["email"] ?? "",
            "phone"     => $foundUser["phone"] ?? ""
        ];

        if ($foundUser["role"] === "headmaster") {
            header("Location: headmaster.php");
        } else {
            header("Location: index.php");
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
    <meta charset="UTF-8">
    <title>Login - SubixStar Pre-School</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once 'menu-bar.php'; ?>

    <main style="text-align:center; margin-top:50px;">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?endif; ?>

        <form method="POST" style="display:inline-block; text-align:left;">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="registration.php">Register here</a></p>
    </main>
</body>
</html>