<?php
session_start();

require_once __DIR__ . '/../app/services/JsonStorage.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation
    if ($fullName === '') {
        $errors[] = 'Full name is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email address is required.';
    }

    if ($phone === '' || !preg_match('/^[0-9]{10,15}$/', $phone)) {
        $errors[] = 'Valid phone number is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    // Load parents storage
    $parentsStorage = new JsonStorage(__DIR__ . '/../storage/parents.json');
    $parents = $parentsStorage->read();

    // Check duplicate email
    foreach ($parents as $parent) {
        if ($parent['email'] === $email) {
            $errors[] = 'Email is already registered.';
            break;
        }
    }

    // Save parent
    if (empty($errors)) {

        $parents[] = [
            'id' => uniqid('parent_'),
            'full_name' => htmlspecialchars($fullName),
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'parent',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $parentsStorage->write($parents);

        $success = 'Registration successful! You can now login.';
    }
}

require_once __DIR__ . '/../app/views/partials/header.php';
require_once __DIR__ . '/../app/views/partials/navbar.php';
?>

<div class="container">
<div class="login-card">

<h2>Parent Registration</h2>

<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($success): ?>

<p class="success"><?php echo htmlspecialchars($success); ?></p>

<a href="/login.php" class="btn btn-primary">Go to Login</a>

<?php else: ?>

<form method="POST">

<div class="form-group">
<label>Full Name</label>
<input type="text" name="full_name" required>
</div>

<div class="form-group">
<label>Email Address</label>
<input type="email" name="email" required>
</div>

<div class="form-group">
<label>Phone Number</label>
<input type="text" name="phone" placeholder="Example: 0712345678" required>
</div>

<div class="form-group">
<label>Password</label>
<input type="password" name="password" required>
</div>

<div class="form-group">
<label>Confirm Password</label>
<input type="password" name="confirm_password" required>
</div>

<button type="submit" class="btn btn-primary">Register</button>

</form>

<p style="margin-top:15px; text-align:center;">
Already have an account?
<a href="/login.php" style="color:#09223b; font-weight:bold;">Login here</a>
</p>

<?php endif; ?>

</div>
</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
