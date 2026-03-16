<?php

session_start();

require_once __DIR__ . '/../app/services/PasswordResetService.php';

$service = new PasswordResetService();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = $_POST['password'];
    $parentId = $_SESSION['reset_parent'];

    $service->resetPassword($parentId, $password);

    session_destroy();

    $message = "Password successfully reset!";
}
?>

<form method="POST">

<input type="password" name="password" placeholder="New Password" required>

<button type="submit">Reset Password</button>

</form>

<p><?= $message ?></p>

