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
            // Check for hashed password or plain text (for initial admin setup)
            $isPasswordCorrect = (password_verify($password, $user['password']) || $password === $user['password']);

            if ($user['email'] === $email && $isPasswordCorrect) {
                $userFound = true;
                $_SESSION['user_role'] = $user['role']; 
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['parent_email'] = $user['email'];
                
                // --- THE FIX: Capture the phone number for the dashboard ---
                $_SESSION['parent_phone'] = $user['phone'] ?? 'N/A';

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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #003366;
            --red: #C41E3A;
            --gold: #FFD700;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            color: #333;
        }

        /* --- SLIM HEADER (2rem) --- */
        .page-banner {
            background: linear-gradient(135deg, var(--navy) 0%, #001a33 100%);
            padding: 40px 20px;
            position: relative;
            border-bottom: 5px solid var(--red);
            text-align: center;
            color: white;
        }

        .page-banner h1 {
            font-weight: 900;
            font-size: 2rem;
            letter-spacing: 1px;
            margin: 0;
            text-transform: uppercase;
        }

        .gold-divider {
            width: 40px; 
            height: 3px; 
            background: var(--gold); 
            margin: 10px auto;
        }

        /* --- LOGIN CARD --- */
        .login-container {
            max-width: 450px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-top: 6px solid var(--navy);
        }

        .form-group { margin-bottom: 20px; }
        .form-group label { 
            display: block; 
            font-weight: 700; 
            font-size: 0.75rem; 
            color: var(--navy); 
            margin-bottom: 8px; 
            text-transform: uppercase;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: var(--navy);
            outline: none;
        }

        .submit-btn {
            background: var(--red);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-weight: 800;
            cursor: pointer;
            font-size: 1rem;
            transition: 0.3s;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: #a31830;
            transform: translateY(-2px);
        }

        .alert-danger {
            background: #fff5f5;
            color: var(--red);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid #fed7d7;
            text-align: center;
        }

        .login-footer a {
            color: var(--navy);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .login-footer a:hover { color: var(--red); }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <header class="page-banner">
        <h1>Welcome Back</h1>
        <div class="gold-divider"></div>
        <p style="text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; opacity: 0.8;">Secure Portal Access</p>
    </header>

    <main class="login-container">
        <div class="login-card">
            
            <?php if($error_message !== ""): ?>
                <div class="alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label><i class="fas fa-envelope" style="margin-right: 5px;"></i> Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-lock" style="margin-right: 5px;"></i> Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>

                <button type="submit" class="submit-btn">Sign In to Dashboard</button>
            </form>

            <div class="login-footer" style="text-align: center; margin-top: 25px;">
                <p style="margin-bottom: 10px;">
                    <a href="forgot_password.php">Forgot Password?</a>
                </p>
                <div style="border-top: 1px solid #eee; padding-top: 20px; margin-top: 20px;">
                    <p style="font-size: 0.9rem; color: #666;">
                        New Parent? <a href="admissions.php" style="color: var(--red);">Apply for Admission</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>