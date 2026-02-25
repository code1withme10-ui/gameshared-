<?php
require_once __DIR__ . '/../app/services/JsonStorage.php';
require_once __DIR__ . '/../app/views/partials/header.php';
require_once __DIR__ . '/../app/views/partials/navbar.php';

session_start();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$fullName) $errors[] = 'Full name is required.';
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (!$phone || !preg_match('/^\d+$/', $phone)) $errors[] = 'Valid phone number is required.';
    if (!$password) $errors[] = 'Password is required.';

    $parentsStorage = new JsonStorage(__DIR__ . '/../storage/parents.json');
    $parents = $parentsStorage->read();

    foreach ($parents as $parent) {
        if ($parent['email'] === $email) {
            $errors[] = 'Email is already registered.';
            break;
        }
    }

    if (empty($errors)) {
        $parents[] = [
            'id' => uniqid('parent_'),
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'parent'
        ];

        $parentsStorage->write($parents);
        $success = 'Registration successful! You can now login.';
    }
}
?>

<div class="container">
    <div class="login-card">
        <h2>Parent Registration</h2>

        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $e): ?>
                <p class="error"><?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
            <a href="/login.php" class="btn btn-primary">Go to Login</a>
        <?php else: ?>
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="Enter your phone number" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Create a password" required>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            <p style="margin-top:15px; text-align:center;">
                Already have an account? <a href="/login.php" style="color:#09223b; font-weight:bold;">Login here</a>
            </p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
