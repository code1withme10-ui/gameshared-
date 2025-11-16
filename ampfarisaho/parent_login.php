<?php include('includes/header.php'); include('database/connection.php'); ?>

<div class="container">
    <h2>Parent Login</h2>
    <form method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="login">Login</button>
    </form>
</div>

<?php
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM parents WHERE email='$email' AND password = '$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $parent = $result->fetch_assoc();
        if (password_verify($password, $parent['password'])) {
            $_SESSION['parent_id'] = $parent['id'];
            $_SESSION['parent_name'] = $parent['parent_name'];
            $_SESSION['email'] = $email;
            header("Location: parent_dashboard.php");
        } else echo "<p class='error'>Invalid password.</p>";
    } else echo "<p class='error'>No account found with that email.</p>";
}
?>

<?php include('includes/footer.php'); ?>
