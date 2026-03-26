<?php
// --- THE BRAIN: Parent-Only Registration Logic ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataDir = 'data/';
    if (!is_dir($dataDir)) mkdir($dataDir, 0777, true);

    $applicationId = "GP-" . time();
    $email = trim(strtolower($_POST['email']));
    $parentName = $_POST['parent_name'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // 1. UPDATE USERS DATABASE (For Portal Login)
    $usersFile = $dataDir . 'users.json';
    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
    if (!is_array($users)) $users = [];
    
    $userExists = false;
    foreach($users as $u) {
        if(isset($u['email']) && $u['email'] === $email) { $userExists = true; break; }
    }

    if(!$userExists) {
        $users[] = [
            "email"    => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "name"     => $parentName,
            "phone"    => $phone,
            "role"     => "parent"
        ];
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    }

    // 2. UPDATE APPLICATIONS DATABASE (Simple Entry)
    $appsFile = $dataDir . 'applications.json';
    $applications = file_exists($appsFile) ? json_decode(file_get_contents($appsFile), true) : [];
    if (!is_array($applications)) $applications = [];

    $newApp = [
        'application_id'  => $applicationId,
        'email'           => $email, 
        'parent_name'     => $parentName,
        'parent_phone'    => $phone,
        'status'          => 'registered',
        'submission_date' => date("Y-m-d H:i:s")
    ];

    $applications[] = $newApp;
    file_put_contents($appsFile, json_encode($applications, JSON_PRETTY_PRINT));

    header("Location: login.php?signup=success");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Started | Govern Psy Educational Center</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background: #fdfdfd;">
    <?php include 'includes/navbar.php'; ?>

    <header class="page-banner" style="background: #003366; color: white; padding: 40px 20px; text-align: center; border-bottom: 5px solid #C41E3A;">
        <h1>Parent Registration</h1>
        <p>Create your portal account to begin</p>
    </header>

    <main class="container" style="max-width: 600px; margin: 50px auto; padding: 20px;">
        <form action="admissions.php" method="POST" style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
            
            <div style="margin-bottom: 25px;">
                <h3 style="color: #003366; margin-bottom: 25px; text-align: center;">Contact Details</h3>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Full Name</label>
                    <input type="text" name="parent_name" required placeholder="Enter your full name" style="width:100%; padding:12px; border:1px solid #ddd; border-radius: 8px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Phone Number</label>
                    <input type="tel" name="phone" required placeholder="012 345 6789" style="width:100%; padding:12px; border:1px solid #ddd; border-radius: 8px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Email Address</label>
                    <input type="email" name="email" required placeholder="name@example.com" style="width:100%; padding:12px; border:1px solid #ddd; border-radius: 8px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Create Password</label>
                    <input type="password" name="password" required minlength="6" placeholder="At least 6 characters" style="width:100%; padding:12px; border:1px solid #ddd; border-radius: 8px; box-sizing: border-box;">
                </div>
            </div>

            <button type="submit" style="background:#C41E3A; color:white; padding:18px; border:none; border-radius:8px; width:100%; cursor:pointer; font-weight:bold; font-size: 1.1rem; transition: background 0.3s;">
                CREATE ACCOUNT
            </button>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>