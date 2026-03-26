<?php
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(strtolower($_POST['email']));
    $phone = trim($_POST['phone']);
    $new_password = $_POST['new_password'];
    
    $usersFile = 'data/users.json';
    if (file_exists($usersFile)) {
        $users = json_decode(file_get_contents($usersFile), true);
        $found = false;

        foreach ($users as &$user) {
            // Verify both email and phone for security
            if ($user['email'] === $email && $user['phone'] === $phone) {
                $user['password'] = password_hash($new_password, PASSWORD_DEFAULT);
                $found = true;
                break;
            }
        }

        if ($found) {
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            header("Location: login.php?reset=success");
            exit();
        } else {
            $message = "Details do not match our records.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | Govern Psy</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: Arial, sans-serif;">

    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px;">
        <h2 style="color: #003366; text-align: center;">Reset Password</h2>
        
        <?php if($message): ?>
            <p style="color: red; text-align: center;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Registered Email</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc;">
            
            <label>Registered Phone Number</label>
            <input type="tel" name="phone" required style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc;">

            <label>New Password</label>
            <input type="password" name="new_password" required minlength="6" style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc;">

            <button type="submit" style="width: 100%; padding: 12px; background: #003366; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 10px;">
                Update Password
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="login.php" style="color: #666; text-decoration: none;">Back to Login</a>
        </p>
    </div>

</body>
</html>