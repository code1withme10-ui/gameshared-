<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Parent Login</title>
    <link rel="stylesheet" href="webstyle.css">
</head>
<body>
<header><h1>Parent Login</h1></header>
<?php include('menu-bar.php'); ?>
<div class="container">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('config.php');
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM parents WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['parent_id'] = $row['id'];
            $_SESSION['parent_name'] = $row['parent_name'];
            header("Location: parent_dashboard.php");
            exit();
        } else {
            echo "<p style='color:red;'>Incorrect password.</p>";
        }
    } else {
        echo "<p style='color:red;'>No account found with that email.</p>";
    }
}
?>
<form method="POST" action="">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
</div>
</body>
</html>

