<?php
// --- THE BRAIN: Parent-Only Registration Logic ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataDir = 'data/';
    if (!is_dir($dataDir)) mkdir($dataDir, 0777, true);

    $email = trim(strtolower($_POST['email']));
    $parentName = $_POST['parent_name'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        $usersFile = $dataDir . 'users.json';
        $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
        
        $userExists = false;
        if (is_array($users)) {
            foreach($users as $u) {
                if(isset($u['email']) && $u['email'] === $email) { 
                    $userExists = true; 
                    break; 
                }
            }
        }

        if(!$userExists) {
            // Create the User Account
            $users[] = [
                "email"    => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "name"     => $parentName,
                "phone"    => $phone,
                "role"     => "parent"
            ];
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

            /* NOTE: We removed the 'applications.json' block from here.
               The parent's dashboard will now remain empty until they 
               specifically fill out the child registration form.
            */

            header("Location: login.php?signup=success");
            exit();
        } else {
            $error = "This email is already registered.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Registration | Govern Psy</title>
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

        .reg-container { max-width: 550px; margin: 50px auto; padding: 0 20px; }
        
        .reg-card {
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
            letter-spacing: 1px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: inherit;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--navy);
            outline: none;
            box-shadow: 0 0 5px rgba(0,51,102,0.1);
        }

        .btn-register {
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
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .btn-register:hover { 
            background: #a31830; 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(196, 30, 58, 0.3);
        }

        .alert { 
            background: #fff5f5; 
            color: var(--red); 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 25px; 
            text-align: center;
            font-weight: 600;
            border: 1px solid #fed7d7;
        }

        .footer-links a {
            color: var(--red);
            font-weight: 700;
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <header class="page-banner">
        <h1>Parent Portal</h1>
        <div class="gold-divider"></div>
        <p style="text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; opacity: 0.8;">Secure Registration</p>
    </header>

    <main class="reg-container">
        <div class="reg-card">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2 style="color: var(--navy); font-weight: 800; margin: 0;">Create Account</h2>
                <p style="color: #777; font-size: 0.9rem; margin-top: 5px;">Join the Govern Psy community today.</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
            <?php endif; ?>

            <form action="admissions.php" method="POST" onsubmit="return validatePasswords()">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="parent_name" class="form-control" required placeholder="Enter Parent/Guardian Name">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" class="form-control" required placeholder="071 234 5678">
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="name@example.com">
                    </div>
                </div>

                <div class="form-group">
                    <label>Create Password</label>
                    <input type="password" name="password" id="password" class="form-control" required minlength="6" placeholder="Min. 6 characters">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required placeholder="Repeat your password">
                </div>

                <button type="submit" class="btn-register">Register Now</button>
            </form>

            <div class="footer-links" style="text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 25px;">
                <p style="font-size: 0.9rem; color: #666;">Already have an account? <a href="login.php">Login here</a></p>
                <a href="index.php" style="font-size: 0.8rem; color: #999; font-weight: 400;"><i class="fas fa-arrow-left"></i> Back to Homepage</a>
            </div>
        </div>
    </main>

    <script>
        function validatePasswords() {
            var p1 = document.getElementById('password').value;
            var p2 = document.getElementById('confirm_password').value;
            if (p1 !== p2) {
                alert("The passwords you entered do not match.");
                return false;
            }
            return true;
        }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>