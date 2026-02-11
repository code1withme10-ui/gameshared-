<?php
require_once __DIR__ . '/../app/services/JsonStorage.php';
session_start();

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$fullName) $errors[] = "Full name is required.";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (!$phone || !preg_match('/^\d+$/', $phone)) $errors[] = "Valid phone number is required.";
    if (!$password) $errors[] = "Password is required.";

    $parentsStorage = new JsonStorage(__DIR__ . '/../storage/parents.json');
    $parents = $parentsStorage->read();

    // Check if email exists
    foreach ($parents as $p) {
        if ($p['email'] === $email) {
            $errors[] = "Email is already registered.";
            break;
        }
    }

    if (empty($errors)) {
        $newParent = [
            'id' => uniqid('parent_'),
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];
        $parents[] = $newParent;
        $parentsStorage->write($parents);
        $success = "Registration successful! You can now login.";
    }
}
?>

<div class="container">
    <h2>Parent Registration</h2>
    <?php foreach ($errors as $e): ?>
        <p style="color:red;"><?php echo $e; ?></p>
    <?php endforeach; ?>
    <?php if ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
        <a href="/login.php">Go to Login</a>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
</div>
