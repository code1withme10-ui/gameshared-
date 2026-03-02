<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/services/JsonStorage.php';

$errors = [];

/*
|--------------------------------------------------------------------------
| Redirect if already logged in
|--------------------------------------------------------------------------
*/
if (isset($_SESSION['user'])) {

    $role = strtolower($_SESSION['user']['role'] ?? 'parent');

    if ($role === 'parent') {
        header('Location: /parent-dashboard.php');
    } else {
        header('Location: /admin-dashboard.php');
    }
    exit;
}

/*
|--------------------------------------------------------------------------
| Handle Login Submission
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Please enter both email and password.';
    } else {

        $usersStorage   = new JsonStorage(__DIR__ . '/../storage/users.json');
        $parentsStorage = new JsonStorage(__DIR__ . '/../storage/parents.json');

        $users = array_merge(
            $usersStorage->read() ?? [],
            $parentsStorage->read() ?? []
        );

        $userFound = null;

        foreach ($users as $user) {
            if (
                isset($user['email'], $user['password']) &&
                $user['email'] === $email &&
                password_verify($password, $user['password'])
            ) {
                $userFound = $user;
                break;
            }
        }

        if ($userFound) {

            $_SESSION['user'] = [
                'id'        => $userFound['id'],
                'full_name' => $userFound['full_name'],
                'email'     => $userFound['email'],
                'role'      => strtolower($userFound['role'] ?? 'parent')
            ];

            if ($_SESSION['user']['role'] === 'parent') {
                header('Location: /parent-dashboard.php');
            } else {
                header('Location: /admin-dashboard.php');
            }
            exit;

        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}

/*
|--------------------------------------------------------------------------
| Load header only AFTER logic
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/../app/views/partials/header.php';
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
            Don't have an account?
            <a href="/register.php" style="color:#09223b; font-weight:bold;">
                Register here
            </a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>