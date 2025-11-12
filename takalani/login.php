<?php
session_start();

$usersFile = __DIR__ . '/users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]); // child name
    $password = trim($_POST["password"]);

    $foundUser = null;
    foreach ($users as $user) {
        if (strcasecmp($user["childName"], $username) === 0) {
            $foundUser = $user;
            break;
        }
    }

    if ($foundUser && password_verify($password, $foundUser["password"])) {
        $_SESSION["user"] = [
            "parentName" => $foundUser["parentName"],
            "childName" => $foundUser["childName"],
            "childAge" => $foundUser["childAge"],
            "email" => $foundUser["email"] ?? "",
            "phone" => $foundUser["phone"] ?? ""
        ];
        header("Location: index.php");
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
    <?php endif; ?>

    <form method="POST" style="display:inline-block; text-align:left;">
        <label for="username">Enter Username (Child Name):</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="registration.php">Register here</a></p>
</main>
</body>
</html>
