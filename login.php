<?php include 'navbar.php'; ?>

<h1>Login</h1>

<h2>Parent Login</h2>
<form method="POST" action="parent_login.php">
    <input type="email" name="email" placeholder="Enter Email" required>
    <button type="submit">Login</button>
</form>

<h2>Headmaster Login</h2>
<form method="POST" action="headmaster_login.php">
    <input type="password" name="password" placeholder="Headmaster Password" required>
    <button type="submit">Login</button>
</form>
