<?php
session_start();

// Load JSON file
$headmasterFile = __DIR__ . "/headmaster-login.json";

if (!file_exists($headmasterFile)) {
    die("Headmaster login file missing!");
}

$data = json_decode(file_get_contents($headmasterFile), true);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Check credentials
    if ($username === $data["username"] && $password === $data["password"]) {
        
        $_SESSION["user"] = [
            "username" => $username,
            "role" => "headmaster"
        ];

        header("Location: headmaster.php");
        exit();

    } else {
        $error = "❌ Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Headmaster Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2 style="text-align:center;">Headmaster Login</h2>

<?php if ($error): ?>
    <p style="color:red; text-align:center;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" style="max-width:300px; margin:auto;">
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>

</body>
</html>
