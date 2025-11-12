<!DOCTYPE html>
<html>
<head>
    <title>Parent Registration</title>
    <link rel="stylesheet" href="webstyle.css">
</head>
<body>
<header><h1>Parent Registration</h1></header>
<?php include('menu-bar.php'); ?>
<div class="container">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('config.php');
    $name = $_POST['parent_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM parents WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo "<p style='color:red;'>Email already registered.</p>";
    } else {
        $sql = "INSERT INTO parents (parent_name, email, password) VALUES ('$name','$email','$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>Registration successful! <a href='parent_login.php'>Login here</a>.</p>";
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }
}
?>
<form method="POST" action="">
    <label>Full Name:</label><br>
    <input type="text" name="parent_name" required><br><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Register</button>
</form>
</div>
</body>
</html>
