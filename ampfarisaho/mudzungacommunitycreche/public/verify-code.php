<?php

session_start();

require_once __DIR__ . '/../app/services/PasswordResetService.php';

$service = new PasswordResetService();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $code = $_POST['code'];
    $parentId = $_SESSION['reset_parent'];

    if ($service->verifyCode($parentId, $code)) {

        header("Location: reset-password.php");
        exit;

    } else {

        $message = "Invalid or expired code";
    }
}
?>

<form method="POST">

<input name="code" placeholder="Enter Code" required>

<button type="submit">Verify</button>

</form>

<p><?= $message ?></p>

