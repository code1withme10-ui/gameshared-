<?php
session_start();

// Use the same absolute path as registration.php
$usersFile = __DIR__ . '/users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $childName = trim($_POST["childName"]);
    $childSurname = trim($_POST["childSurname"]);
    $password = trim($_POST["password"]);

    $foundUser = null;
    foreach ($users as $user) {
        if (
            strcasecmp($user["childName"], $childName) === 0 &&
            strcasecmp($user["childSurname"], $childSurname) === 0
        ) {
            $foundUser = $user;
            break;
        }
    }

    if ($foundUser && password_verify($password, $foundUser["password"])) {
        $_SESSION["user"] = [
            "parentName" => $foundUser["parentName"],
            "childName" => $foundUser["childName"],
            "childAge" => $foundUser["childAge"]
        ];
        header("Location: index.php");
        exit();
    } else {
        $error = "❌ Invalid child name, surname, or password.";
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
        <label for="childName">Child's First Name:</label><br>
        <input type="text" id="childName" name="childName" required><br><br>

        <label for="childSurname">Child's Surname:</label><br>
        <input type="text" id="childSurname" name="childSurname" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="registration.php">Register here</a></p>
</main>
</body>
</html>
