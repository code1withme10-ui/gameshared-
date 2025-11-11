<?php
include('header.php');

// Include your database connection
// Example: include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = trim($_POST['username']);
    $password_plain = $_POST['password'];

    if (!empty($username) && !empty($password_plain)) {
        // Hash the password
        $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

        // Example of saving to database (optional)
        /*
        $stmt = $conn->prepare("INSERT INTO parents (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password_hashed);
        if ($stmt->execute()) {
            header("Location: confirmation.php");
            exit;
        } else {
            echo "<p style='color:red;'>Error: Could not register user.</p>";
        }
        */

        // If not saving to DB yet, just redirect
        header("Location: confirmation.php");
        exit;
    } else {
        echo "<p style='color:red;'>Please fill in all fields.</p>";
    }
}
?>

<h2>Parent Registration</h2>
<form method="POST" action="">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Register</button>
</form>

<?php include('footer.php'); ?>
