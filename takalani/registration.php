<?php
session_start();

// Path to JSON file for storing users
$usersFile = __DIR__ . '/users.json';

// Create file if it doesn't exist
if (!file_exists($usersFile)) {
    file_put_contents($usersFile, json_encode([]));
}

// Load existing users
$users = json_decode(file_get_contents($usersFile), true);

// Handle form submission
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $parentName = trim($_POST["parentName"]);
    $childName = trim($_POST["childName"]);
    $childAge = trim($_POST["childAge"]);
    $contact = trim($_POST["contact"]);
    $medical = trim($_POST["medical"]);
    $password = $_POST["password"];

    // Check if user already exists
    $exists = false;
    foreach ($users as $user) {
        if ($user["email"] === $email) {
            $exists = true;
            break;
        }
    }

    if ($exists) {
        $message = "<p style='color:red;'>User with this email already exists. Please log in.</p>";
    } else {
        // Password validation (at least 8 chars, 1 uppercase, 1 special character)
        if (!preg_match('/^(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $password)) {
            $message = "<p style='color:red;'>Password must be at least 8 characters long, include one uppercase letter, and one special character.</p>";
        } else {
            // Save new user
            $newUser = [
                "username" => $username,
                "parentName" => $parentName,
                "childName" => $childName,
                "childAge" => $childAge,
                "contact" => $contact,
                "email" => $email,
                "medical" => $medical,
                "password" => password_hash($password, PASSWORD_DEFAULT)
            ];

            $users[] = $newUser;
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            $message = "<p style='color:green;'>Registration successful! You can now <a href='login.php'>login</a>.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
<?php require_once 'menu-bar.php'; ?>

<main>
    <h2>Register</h2>

    <?= $message ?>

    <form action="" method="POST">
        <label for="parentName">Parent/Guardian Name:</label><br />
        <input type="text" id="parentName" name="parentName" required /><br /><br />

        <label for="childName">Child's Name:</label><br />
        <input type="text" id="childName" name="childName" required /><br /><br />

        <label for="childAge">Child's Age:</label><br />
        <input type="number" id="childAge" name="childAge" min="0" max="10" required /><br /><br />

        <label for="contact">Contact Number:</label><br />
        <input type="tel" id="contact" name="contact" pattern="[0-9]{10}" placeholder="e.g., 0123456789" required /><br /><br />

        <label for="email">Email Address:</label><br />
        <input type="email" id="email" name="email" required /><br /><br />

        <label for="username">Username:</label><br />
        <input type="text" id="username" name="username" required /><br /><br />

        <label for="password">Password:</label><br />
        <input type="password" id="password" name="password" required /><br /><br />

        <label for="medical">Medical Information / Allergies:</label><br />
        <textarea id="medical" name="medical" rows="4" cols="40"></textarea><br /><br />

        <input type="submit" value="Register" />
    </form>
</main>

<footer>
    <p>© 2025 SubixStar Pre-School</p>
</footer>
</body>
</html>
