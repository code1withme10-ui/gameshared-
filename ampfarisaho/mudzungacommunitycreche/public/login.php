<?php
require_once __DIR__ . '/../app/services/JsonStorage.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $usersStorage = new JsonStorage(__DIR__ . '/../storage/users.json');
    $parentsStorage = new JsonStorage(__DIR__ . '/../storage/parents.json');

    $users = array_merge($usersStorage->read(), $parentsStorage->read());

    $userFound = null;
    foreach ($users as $u) {
        if ($u['email'] === $email && password_verify($password, $u['password'])) {
            $userFound = $u;
            break;
        }
    }

    if ($userFound) {
        $_SESSION['user'] = $userFound;

        if ($userFound['role'] ?? 'parent' === 'parent') {
            header('Location: /app/views/parent/dashboard.php');
        } else {
            header('Location: /app/views/admin/dashboard.php');
        }
        exit;
    } else {
        $errors[] = "Invalid email or password.";
    }
}
?>

<div class="container">
    <h2>Login</h2>
    <?php foreach ($errors as $e): ?>
        <p style="color:red;"><?php echo $e; ?></p>
    <?php endforeach; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
