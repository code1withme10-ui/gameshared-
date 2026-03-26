<?php

require_once __DIR__ . '/../app/services/PasswordResetService.php';

$service = new PasswordResetService();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $identifier = $_POST['identifier'];
    $method = $_POST['method'];

    $parent = $service->findParent($identifier);

    if (!$parent) {
        $message = "Parent not found";
    } else {

        $code = $service->generateCode();

        $service->storeCode($parent['id'], $code);

        if ($method === "email") {
            $service->sendEmail($parent['email'], $code);
        }

        if ($method === "sms") {
            $service->sendSms($parent['phone'], $code);
        }

        session_start();
        $_SESSION['reset_parent'] = $parent['id'];

        header("Location: verify-code.php");
        exit;
    }
}
?>

<form method="POST">

<input name="identifier" placeholder="Email or Phone" required>

<label>
<input type="radio" name="method" value="email" required>
Email
</label>

<label>
<input type="radio" name="method" value="sms">
SMS
</label>

<button type="submit">Send Code</button>

</form>

<p><?= $message ?></p>