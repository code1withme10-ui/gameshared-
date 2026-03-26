<?php
session_start();
$error_message = "";

// --- THE BRAIN ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $usersFile = 'data/users.json';
    
    if (file_exists($usersFile)) {
        $users = json_decode(file_get_contents($usersFile), true);
        $userFound = false;

        foreach ($users as $user) {
            $isPasswordCorrect = (password_verify($password, $user['password']) || $password === $user['password']);

            if ($user['email'] === $email && $isPasswordCorrect) {
                $userFound = true;
                $_SESSION['user_role'] = $user['role']; 
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['parent_email'] = $user['email'];

                if ($user['role'] === 'admin') {
                    header("Location: headmaster_portal.php");
                } else {
                    header("Location: parent_dashboard.php");
                }
                exit();
            }
        }
        
        if (!$userFound) {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "System Error: Database missing.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Govern Psy Educational Center</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>

<body class="no-scroll-page">

    <?php include 'includes/navbar.php'; ?>

    <main>
        <div class="contact-layout" style="max-width: 500px; grid-template-columns: 1fr;">
            <div class="form-card" style="text-align: center;">
                <div class="card-header">
                    <h2 style="font-family: 'Montserrat', sans-serif; font-weight: 900;">WELCOME BACK</h2>
                    <p class="cta-message">Secure Portal Access</p>
                </div>
                
                <?php if($error_message !== ""): ?>
                    <div style="color: #C41E3A; background: #fff2f2; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; font-weight: bold; border: 1px solid #ffcccc;">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <form action="login.php" method="POST" class="styled-form" style="text-align: left;">
                    <div class="input-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" name="email" required placeholder="Enter your email">
                    </div>

                    <div class="input-group">
                        <label><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>

                    <button type="submit" class="submit-btn">
                        Sign In to Dashboard
                    </button>
                </form>
                <div style="margin-top: 15px; text-align: center;">
    <a href="forgot_password.php" style="color: #003366; text-decoration: none; font-size: 0.9rem;">
        Forgot Password?
    </a>
</div>
                
                <div style="margin-top: 25px; font-size: 0.9rem; color: #666;">
                    <p>New Parent? <a href="admissions.php" style="color: #C41E3A; font-weight: bold; text-decoration: none;">Apply for Admission</a></p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>