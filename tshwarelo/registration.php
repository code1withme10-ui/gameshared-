<?php
// Define the path to the JSON file
$json_file = 'users.json';
$message = '';

// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Get and sanitize ALL input data using $_POST
    $parent_name = trim($_POST['parent_name'] ?? '');
    $parent_number = trim($_POST['parent_number'] ?? '');
    $email = trim($_POST['email'] ?? ''); // This will be the username
    $password = $_POST['password'] ?? '';
    $child_name = trim($_POST['child_name'] ?? '');
    $child_age = trim($_POST['child_age'] ?? '');
    $allergies = trim($_POST['allergies'] ?? 'None');
    $medical_info = trim($_POST['medical_info'] ?? 'None');
    
    // Basic validation: Check required fields
    if (empty($parent_name) || empty($email) || empty($password) || empty($child_name) || empty($child_age)) {
        $message = '<p style="color:red;">Please fill in all required fields (Name, Email, Password, Child Name, Age).</p>';
    } else {
        
        // 2. Read existing users from the JSON file
        $users_data = file_get_contents($json_file);
        $users = json_decode($users_data, true) ?? [];
        
        // Check if the email (username) already exists
        if (isset($users[$email])) {
            $message = '<p style="color:red;">This email is already registered. Please choose another or <a href="login.php">login</a>.</p>';
        } else {
            
            // 3. Create a comprehensive record for the new user
            $new_user_record = [
                'password' => $password, // Storing password for this exercise
                'parent_name' => $parent_name,
                'parent_number' => $parent_number,
                'child_name' => $child_name,
                'child_age' => $child_age,
                'allergies' => $allergies,
                'medical_info' => $medical_info,
            ];

            // Add the new record to the main users array, using the email as the key
            $users[$email] = $new_user_record; 
            
            // 4. Encode and save the data
            $new_users_data = json_encode($users, JSON_PRETTY_PRINT);
            
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
    <h1>Creche Registration</h1>
    
    <?php echo $message; // Display any success or error messages ?>

    <form method="POST" action="registration.php">
        <h2>Parent Information</h2>
        <label for="parent_name">Parent/Guardian Name:</label><br>
        <input type="text" id="parent_name" name="parent_name" required><br><br>
        
        <label for="parent_number">Contact Number:</label><br>
        <input type="tel" id="parent_number" name="parent_number"><br><br>
        
        <label for="email">Email (Your Login Username):</label><br>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Choose Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <hr>

        <h2>Child Information</h2>
        <label for="child_name">Child's Name:</label><br>
        <input type="text" id="child_name" name="child_name" required><br><br>
        
        <label for="child_age">Child's Age:</label><br>
        <input type="number" id="child_age" name="child_age" required><br><br>
        
        <label for="allergies">Allergies (e.g., Peanuts, Dairy):</label><br>
        <textarea id="allergies" name="allergies"></textarea><br><br>

        <label for="medical_info">Medical Information (e.g., daily meds, conditions):</label><br>
        <textarea id="medical_info" name="medical_info"></textarea><br><br>
        
        <input type="submit" value="Complete Registration">
    </form>
    
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
      <footer>
    <p>&copy; 2025 Ndlovu's Crèche</p>
  </footer>
</body>
</html>
