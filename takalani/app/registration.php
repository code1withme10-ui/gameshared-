<?php
 
// CRITICAL FIX: Robust session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
// PATH FIX: Corrected the path from app/ to the data folder:
$usersFile = __DIR__ . '/../data/users.json'; 

if (!file_exists($usersFile)) {
    file_put_contents($usersFile, json_encode([], JSON_PRETTY_PRINT));
}
$users = json_decode(file_get_contents($usersFile), true);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect and sanitize POST data
    $email = trim($_POST["email"] ?? '');
    $parentName = trim($_POST["parentName"] ?? '');
    $parentSurname = trim($_POST["parentSurname"] ?? '');
    $parentAddress = trim($_POST["parentAddress"] ?? '');
    $childName = trim($_POST["childName"] ?? '');
    $childSurname = trim($_POST["childSurname"] ?? '');
    $childGender = trim($_POST["childGender"] ?? '');
    $childAge = trim($_POST["childAge"] ?? '');
    $contact = trim($_POST["contact"] ?? '');
    $medical = trim($_POST["medical"] ?? '');
    $password = $_POST["password"] ?? '';
    
    // CRITICAL FIX: Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // username convention: childname + childsurname (lowercase, no spaces)
    $username = strtolower(preg_replace('/\\s+/', '', $childName . $childSurname));

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
    } elseif (empty($password) || !preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password)) {
        // Simple password complexity check: minimum 6 characters, at least one letter and one number
        $message = "<p style='color:red;'>⚠️ Password must be at least 6 characters long and contain both letters and numbers.</p>";
    } else {
        // Prepare the new user data array
        $newUser = [
            "username"      => $username,
            "password"      => $hashedPassword, // STORE THE HASHED PASSWORD
            "role"          => "parent", // Default role
            "parentName"    => $parentName,
            "parentSurname" => $parentSurname,
            "parentAddress" => $parentAddress,
            "email"         => $email,
            "contact"       => $contact,
            "childName"     => $childName,
            "childSurname"  => $childSurname,
            "childGender"   => $childGender,
            "childAge"      => $childAge,
            "medical"       => $medical,
            "registrationDate" => date('Y-m-d H:i:s')
        ];

        // Add the new user and save the updated array
        $users[] = $newUser;
        
        if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT))) {
            $message = "<p style='color:green;'>✅ Registration successful! Your username is <strong>{$username}</strong>. You can now <a href=\"/login\" class=\"rainbow-link\">Login</a>.</p>";
            // Clear POST variables to prevent form resubmission
            $_POST = []; 
        } else {
            $message = "<p style='color:red;'>❌ Registration failed: Could not save user data. Check file permissions.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Registration - SubixStar Pre-School</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
<?php require_once "../app/menu-bar.php"; ?>

<main style="max-width:400px; margin:40px auto; padding:20px; background:#fff; border-radius:8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align:left;">
    <h2 style="text-align:center;">Parent/Guardian Registration</h2>
    
    <?php if (!empty($message)): ?>
        <?= $message ?>
    <?php endif; ?>

    <p style="text-align:center; color:#555; margin-bottom: 20px;">
        Already registered? <a href="/login">Login here</a>.
    </p>

    <form method="POST">
        <h3>Parent/Guardian Information</h3>
        <label for="parentName">First Name:</label><br />
        <input type="text" id="parentName" name="parentName" required value="<?= htmlspecialchars($_POST['parentName'] ?? '') ?>" /><br /><br />

        <label for="parentSurname">Surname:</label><br />
        <input type="text" id="parentSurname" name="parentSurname" required value="<?= htmlspecialchars($_POST['parentSurname'] ?? '') ?>" /><br /><br />
        
        <label for="contact">Contact Number:</label><br />
        <input type="tel" id="contact" name="contact" required value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>" /><br /><br />

        <label for="parentAddress">Address:</label><br />
        <input type="text" id="parentAddress" name="parentAddress" required value="<?= htmlspecialchars($_POST['parentAddress'] ?? '') ?>" /><br /><br />

        <label for="email">Email Address:</label><br />
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" /><br /><br />

        <h3>Child Information</h3>
        <label for="childName">First Name:</label><br />
        <input type="text" id="childName" name="childName" required value="<?= htmlspecialchars($_POST['childName'] ?? '') ?>" /><br /><br />

        <label for="childSurname">Surname:</label><br />
        <input type="text" id="childSurname" name="childSurname" required value="<?= htmlspecialchars($_POST['childSurname'] ?? '') ?>" /><br /><br />

        <label for="childGender">Gender:</label><br />
        <select id="childGender" name="childGender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male" <?= (($_POST['childGender'] ?? '') === 'Male') ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= (($_POST['childGender'] ?? '') === 'Female') ? 'selected' : '' ?>>Female</option>
        </select><br /><br />

        <label for="childAge">Age (Years):</label><br />
        <input type="number" id="childAge" name="childAge" min="0" max="18" required value="<?= htmlspecialchars($_POST['childAge'] ?? '') ?>" /><br /><br />

        <label for="password">Password (Min 6 chars, incl. letter & number):</label><br />
        <input type="password" id="password" name="password" required /><br /><br />

        <label for="medical">Medical Information / Allergies:</label><br />
        <textarea id="medical" name="medical" rows="4" cols="40"><?= htmlspecialchars($_POST['medical'] ?? '') ?></textarea><br /><br />

        <input type="submit" value="Register" />
    </form>
    <p style="margin-top:12px; text-align:center; color:#777;">After registering, your login **username** will be generated as *childname+childsurname* (lowercase, no spaces). Example: `leratoMokoena` $\to$ `leratomokoena`</p>
</main>
<?php 
    if (file_exists('footer.php')) {
        include 'footer.php'; 
    }
?>
</body>
</html>