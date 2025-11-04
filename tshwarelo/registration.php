<?php
session_start();

// Include the database connection file
require 'db_connect.php';

// Function to safely clean input data
function clean($v) {
    return trim(htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'));
}

// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Get and sanitize ALL input data
    $parent_name    = clean($_POST['parent_name'] ?? '');
    $contact_number = clean($_POST['parent_number'] ?? ''); 
    $email          = clean($_POST['email'] ?? '');
    $password       = $_POST['password'] ?? '';
    $child_name     = clean($_POST['child_name'] ?? '');
    $child_age      = clean($_POST['child_age'] ?? '');
    
    // Basic validation: Check required fields
    $errors = [];
    if (empty($parent_name)) { $errors[] = 'Parent/Guardian Name is required.'; }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'A valid Email (Username) is required.'; }
    if (empty($password) || strlen($password) < 6) { $errors[] = 'Password is required and must be at least 6 characters long.'; }
    if (empty($child_name) || empty($child_age)) { $errors[] = 'Child Name and Age are required.'; }

    if (empty($errors)) {
        
        try {
            // 2. Check for duplicate email (username) in the database
            // THIS SECTION REPLACES the file_get_contents/json_decode logic
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :email");
            $stmt->execute(['email' => $email]);
            
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Registration failed: That email is already registered.';
            }

            if (empty($errors)) {
                // 3. Prepare data for insertion
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // THIS SECTION REPLACES the file_put_contents logic (which was line 50)
                $stmt = $pdo->prepare("INSERT INTO users (parent_name, username, password_hash, contact_number, child_name, child_age) 
                                       VALUES (:parent_name, :email, :password_hash, :contact_number, :child_name, :child_age)");

                $stmt->execute([
                    'parent_name' => $parent_name,
                    'email' => $email,
                    'password_hash' => $password_hash,
                    'contact_number' => $contact_number,
                    'child_name' => $child_name,
                    'child_age' => $child_age
                ]);
                
                // 4. Registration successful! Set session and redirect.
                $_SESSION['user_name'] = $parent_name;
                header('Location: register_confirmation.php');
                exit;
            }

        } catch (\PDOException $e) {
            // Log the error internally and provide a user-friendly message
            $errors[] = 'A database error occurred during registration. Please try again.';
            // In a development environment, you might use: $errors[] = $e->getMessage();
        }
    }
}

// Include your menu/header file
include 'menu-bar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Creche Registration</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
    <main>
        <h1>Creche Registration</h1>
        
        <?php if (!empty($errors)): ?>
            <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 20px;">
                <h3>Registration Error(s):</h3>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="registration.php">
            <h2 id="parent-information">Parent Information</h2>
            <label for="parent_name">Parent/Guardian Name:</label><br>
            <input type="text" id="parent_name" name="parent_name" value="<?= htmlspecialchars($parent_name ?? '') ?>" required><br>

            <label for="parent_number">Contact Number:</label><br>
            <input type="tel" id="parent_number" name="parent_number" value="<?= htmlspecialchars($contact_number ?? '') ?>" required><br>

            <label for="email">Email (Your Login Username):</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required><br>

            <label for="password">Choose Password:</label><br>
            <input type="password" id="password" name="password" required><br>

            <hr>

            <h2>Child Information</h2>
            <label for="child_name">Child's Name:</label><br>
            <input type="text" id="child_name" name="child_name" value="<?= htmlspecialchars($child_name ?? '') ?>" required><br>

            <label for="child_age">Child's Age (Years):</label><br>
            <input type="number" id="child_age" name="child_age" min="1" max="5" value="<?= htmlspecialchars($child_age ?? '') ?>" required><br>
            
            <input type="submit" value="Complete Registration">
        </form>
        
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </main>
    <footer>
        <p>&copy; 2025 Ndlovu's Crèche</p>
    </footer>
</body>
</html>
