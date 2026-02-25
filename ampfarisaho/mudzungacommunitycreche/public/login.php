<?php
require_once __DIR__ . '/../app/services/JsonStorage.php';
require_once __DIR__ . '/../app/views/partials/header.php';
require_once __DIR__ . '/../app/views/partials/navbar.php';

session_start();

$errors = [];

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    if (($_SESSION['user']['role'] ?? 'parent') === 'parent') {
        header('Location: /app/views/parent/dashboard.php');
        exit;
    } else {
        header('Location: /app/views/admin/dashboard.php');
        exit;
    }
}

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $usersStorage = new JsonStorage(__DIR__ . '/../storage/users.json');
    $parentsStorage = new JsonStorage(__DIR__ . '/../storage/parents.json');

    $users = array_merge(
        $usersStorage->read(),
        $parentsStorage->read()
    );

    $userFound = null;
    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            $userFound = $user;
            break;
        }
    }

    if ($userFound) {
        $_SESSION['user'] = [
            'id' => $userFound['id'],
            'full_name' => $userFound['full_name'],
            'email' => $userFound['email'],
            'role' => $userFound['role'] ?? 'parent'
        ];

        if ($_SESSION['user']['role'] === 'parent') {
            header('Location: /app/views/parent/dashboard.php');
        } else {
            header('Location: /app/views/admin/dashboard.php');
        }
        exit;
    } else {
        $errors[] = 'Invalid email or password.';
    }
}
?>

<div class="container">
    <div class="login-card">
        <h2>Login</h2>

        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $e): ?>
                <p class="error"><?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <p style="margin-top:15px; text-align:center;">
            Don't have an account? <a href="/register.php" style="color:#09223b; font-weight:bold;">Register here</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>