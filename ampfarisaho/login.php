<?php include('header.php'); ?>

<div class="content">
    <h1>Parent Login</h1>
    <form action="parent-page.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Login">
    </form>
</div>

<?php include('footer.php'); ?>



