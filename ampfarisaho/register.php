<?php include('includes/header.php'); include('database/connection.php'); ?>

<div class="container">
    <h2>Parent Registration</h2>
    <form method="POST">
        <label>Full Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="register">Register</button>
    </form>
</div>

<?php
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO parents (parent_name, email, password) VALUES ('$name', '$email', '$password')";
    if ($conn->query($sql)) {
        echo "<p class='success'>Registration successful! <a href='parent_login.php'>Login here</a>.</p>";
    } else {
        echo "<p class='error'>Email already exists or an error occurred.</p>";
    }
}
?>

<?php include('includes/footer.php'); ?>
