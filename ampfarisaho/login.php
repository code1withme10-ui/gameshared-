<?php include('includes/header.php'); ?>

<div class="container">
    <h2>Headmaster Login</h2>
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="login">Login</button>
    </form>
</div>

<?php
if (isset($_POST['login'])) {
    if ($_POST['username'] == "headmaster" && $_POST['password'] == "secure123")
        header("Location: headmaster_dashboard.php");
    else echo "<p class='error'>Invalid credentials!</p>";
}
?>

<?php include('includes/footer.php'); ?>
