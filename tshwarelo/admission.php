<?php
session_start();
$data_file = 'users.json'; // The file where all user data is stored

// ----------------------------------------------------
// Functions to read/write JSON data
// ----------------------------------------------------
function get_users($file) {
    if (!file_exists($file) || filesize($file) == 0) {
        return [];
    }
    
    $data = json_decode(file_get_contents($file), true);
    
    if (!is_array($data)) {
        return [];
    }
    
    return $data;
}

function save_users($file, $users) {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}
// ----------------------------------------------------

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get form values
    $parentName = $_POST['parentName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? ''; 
    $childName = $_POST['childName'] ?? '';
    $age = $_POST['age'] ?? 0;
    $gender = $_POST['gender'] ?? '';

    $users = get_users($data_file);

    // 2. Check if email (username) already exists
    $userExists = false;
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $userExists = true;
            break;
        }
    }

    if ($userExists) {
        $message = '<p style="color: red;">This email is already registered. Please log in or use a different email.</p>';
    } else {
        // 3. Create the new user and child object
        $new_user = [
            'id' => uniqid('user-'), 
            'parentName' => $parentName,
            'email' => $email,
            'password' => $password, 
            'children' => [
                [
                    'childId' => uniqid('child-'),
                    'name' => $childName,
                    'age' => (int)$age,
                    'gender' => $gender,
                    'reports' => [],
                    'status' => 'pending' // <-- CRITICAL: New admission status
                ]
            ]
        ];

        // 4. Add the new user and save
        $users[] = $new_user;
        save_users($data_file, $users);

        $message = '<p style="color: green;">Admission Complete! Your account is created. <a href="login.php">Log in now</a>.</p>';
    }
}

// PHP logic to check login status for the navbar
$is_logged_in = isset($_SESSION['user_email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Admission</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    
    <div class="navbar">
        <span class="navbar-title">Humulani Pre School</span>
        <div class="navbar-links">
            <a href="index.php">Home</a> 
            <a href="about.php">About Us</a>
            <a href="gallery.php">Gallery</a>
            <a href="admission.php">Admission</a>
            <a href="contact.php">Contact</a>
            
            <?php if ($is_logged_in): ?>
                <a href="dashboard.php?action=logout">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="page-container">
        <h1>Online Admission</h1>
        
        <?php echo $message; ?>

        <form method="POST" action="admission.php">
            <h2>Parent/Guardian Details (Your Account)</h2>
            <label for="parentName">Parent/Guardian Name:</label>
            <input type="text" id="parentName" name="parentName" required><br><br>

            <label for="email">Email (Your Username):</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <hr>

            <h2>Child Details (First Child)</h2>
            <label for="childName">Child's Name:</label>
            <input type="text" id="childName" name="childName" required><br><br>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" min="1" max="6" required><br><br>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select><br><br>

            <button type="submit">Complete Admission & Create Account</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>

        <footer>
            <p>&copy; 2026 Humulani Pre School</p>
        </footer>
    </div>
</body>
</html>