<?php
session_start();

$file = "users.json";
$users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $childName = trim($_POST["childName"]);
    $password = trim($_POST["password"]);
    $error = "";

    // Find user by child's name
    $foundUser = null;
    foreach ($users as $user) {
        if (strcasecmp($user["childName"], $childName) === 0) {
            $foundUser = $user;
            break;
        }
    }

    // Verify login
    if ($foundUser && password_verify($password, $foundUser["password"])) {
        $_SESSION["user"] = [
            "parentName" => $foundUser["parentName"],
            "childName" => $foundUser["childName"],
            "childAge" => $foundUser["childAge"]
        ];

        header("Location: index.php");
        exit();
    } else {
        $error = "❌ Invalid child name or password.";
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
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" style="display:inline-block; text-align:left;">
        <label for="childName">Child's Name:</label><br>
        <input type="text" id="childName" name="childName" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="registration.php">Register here</a></p>
</main>
</body>
</html>
