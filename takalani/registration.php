<?php
session_start();

$usersFile = __DIR__ . '/users.json';
if (!file_exists($usersFile)) {
    file_put_contents($usersFile, json_encode([], JSON_PRETTY_PRINT));
}
$users = json_decode(file_get_contents($usersFile), true);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $parentName = trim($_POST["parentName"]);
    $parentSurname = trim($_POST["parentSurname"]);
    $parentAddress = trim($_POST["parentAddress"]);
    $childName = trim($_POST["childName"]);
    $childSurname = trim($_POST["childSurname"]);
    $childGender = trim($_POST["childGender"]);
    $childAge = trim($_POST["childAge"]);
    $contact = trim($_POST["contact"]);
    $medical = trim($_POST["medical"]);
    $password = $_POST["password"];

    // username convention: childname + childsurname (lowercase, no spaces)
    $username = strtolower(preg_replace('/\s+/', '', $childName . $childSurname));

    // Check if child already registered (by username)
    $exists = false;
    foreach ($users as $user) {
        if (isset($user['username']) && $user['username'] === $username) {
            $exists = true;
            break;
        }
    }

    if ($exists) {
        $message = "<p style='color:red;'>⚠️ This child is already registered. Please log in.</p>";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $password)) {
        $message = "<p style='color:red;'>Password must be at least 8 characters long, include one uppercase letter, and one special character.</p>";
    } else {
        $newUser = [
            "username" => $username,
            "role" => "parent",
            "parentName" => $parentName,
            "parentSurname" => $parentSurname,
            "parentAddress" => $parentAddress,
            "childName" => $childName,
            "childSurname" => $childSurname,
            "childGender" => $childGender,
            "childAge" => $childAge,
            "contact" => $contact,
            "email" => $email,
            "medical" => $medical,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ];

        $users[] = $newUser;
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
        $message = "<p style='color:green;'>✅ Registration successful! Your login username is <strong>" . htmlspecialchars($username) . "</strong>. You can now <a href='login.php'>login</a>.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Register - SubixStar Pre-School</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
<?php require_once 'menu-bar.php'; ?>
<main>
    <h2>Register</h2>
    <?= $message ?>
    <form action="" method="POST">
        <h3>Parent/Guardian Information</h3>
        <label for="parentName">First Name:</label><br />
        <input type="text" id="parentName" name="parentName" required /><br /><br />

        <label for="parentSurname">Surname:</label><br />
        <input type="text" id="parentSurname" name="parentSurname" required /><br /><br />

        <label for="parentAddress">Address:</label><br />
        <input type="text" id="parentAddress" name="parentAddress" required /><br /><br />

        <label for="contact">Contact Number:</label><br />
        <input type="tel" id="contact" name="contact" pattern="[0-9]{9,15}" placeholder="e.g., 0123456789" required /><br /><br />

        <label for="email">Email Address:</label><br />
        <input type="email" id="email" name="email" required /><br /><br />

        <h3>Child Information</h3>
        <label for="childName">First Name:</label><br />
        <input type="text" id="childName" name="childName" required /><br /><br />

        <label for="childSurname">Surname:</label><br />
        <input type="text" id="childSurname" name="childSurname" required /><br /><br />

        <label for="childGender">Gender:</label><br />
        <select id="childGender" name="childGender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br /><br />

        <label for="childAge">Age:</label><br />
        <input type="number" id="childAge" name="childAge" min="0" max="18" required /><br /><br />

        <label for="password">Password:</label><br />
        <input type="password" id="password" name="password" required /><br /><br />

        <label for="medical">Medical Information / Allergies:</label><br />
        <textarea id="medical" name="medical" rows="4" cols="40"></textarea><br /><br />

        <input type="submit" value="Register" />
    </form>
    <p style="margin-top:12px;">After registering, your login <strong>username</strong> will be generated as <em>childname+childsurname</em> (lowercase, no spaces). Example: <code>leratoMokoena → leratomokoena</code></p>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
