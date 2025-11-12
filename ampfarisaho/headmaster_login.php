<!DOCTYPE html>
<html>
<head>
    <title>Headmaster Login</title>
    <link rel="stylesheet" href="webstyle.css">
</head>
<body>
<header><h1>Headmaster Login</h1></header>
<?php include('menu-bar.php'); ?>
<div class="container">
<form method="POST" action="headmaster_dashboard.php">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
</div>
</body>
</html>
