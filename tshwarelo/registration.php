<?php
// Define the path to the JSON file
$json_file = 'users.json';
$message = '';

// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Get and sanitize the input data using $_POST
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $message = '<p style="color:red;">Username and Password cannot be empty.</p>';
    } else {
        
        // 2. Read the existing users from the JSON file
        $users_data = file_get_contents($json_file);
        
        // Decode the JSON data into a PHP array. If the file is empty or invalid, initialize an empty array.
        $users = json_decode($users_data, true) ?? [];
        
        // Check if username already exists
        if (isset($users[$username])) {
            $message = '<p style="color:red;">Username already taken. Please choose another.</p>';
        } else {
            
            // 3. Add the new user to the array
            // NOTE: For a real application, you would HASH the password here (e.g., password_hash())
            // For this exercise, we store the password directly as requested by the mentor.
            $users[$username] = $password; 
            
            // 4. Encode the updated PHP array back into a JSON string
            $new_users_data = json_encode($users, JSON_PRETTY_PRINT);
            
            // 5. Write the new JSON string back to the file
            if (file_put_contents($json_file, $new_users_data)) {
                $message = '<p style="color:green;">Registration successful! You can now <a href="login.php">login</a>.</p>';
            } else {
                $message = '<p style="color:red;">Error saving user data.</p>';
            }
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
    <title>User Registration</title>
</head>
<body>
    <h1>User Registration</h1>
    
    <?php echo $message; // Display any success or error messages ?>

    <form method="POST" action="registration.php">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Register">
    </form>
    
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>