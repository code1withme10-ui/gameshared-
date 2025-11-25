<?php

// Redirect already logged-in users
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'parent') {
        header("Location: ?page=parent_dashboard");
        exit;
    } elseif ($_SESSION['role'] === 'headmaster') {
        header("Location: ?page=headmaster");
        exit;
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Load users
    $parents = json_decode(file_get_contents(__DIR__."/../data/parents.json"), true);
    $headmasters = json_decode(file_get_contents(__DIR__."/../data/headmaster.json"), true);

    $userFound = false;

    // Check parents
    foreach ($parents as $parent) {
        if ($parent['email'] === $email && password_verify($password, $parent['password'])) {
            $_SESSION['user'] = $parent;
            $_SESSION['role'] = 'parent';
            header("Location: ?page=parent_dashboard");
            exit;
        }
    }

    // Check headmasters
    foreach ($headmasters as $hm) {
        if ($hm['email'] === $email && password_verify($password, $hm['password'])) {
            $_SESSION['user'] = $hm;
            $_SESSION['role'] = 'headmaster';
            header("Location: ?page=headmaster");
            exit;
        }
    }

    $error = "Invalid email or password";
}
?>

 

<div class="w3-container w3-padding">
    <h2>Login</h2>
    <?php if ($error): ?>
        <div class="w3-panel w3-red"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" class="w3-container w3-card-4 w3-padding">
        <label>Email</label>
        <input class="w3-input w3-border" type="email" name="email" required>

        <label>Password</label>
        <input class="w3-input w3-border" type="password" name="password" required>

        <button class="w3-button w3-blue w3-margin-top" type="submit">Login</button>
    </form>
</div>

 
