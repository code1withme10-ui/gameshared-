<?php include __DIR__ . '/../../includes/menu-bar.php'; ?>
<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Login</h2>
    <p>Parents and Headmaster login using <strong>Username + Password</strong>.</p>

    <?php if(!empty($this->error)): ?>
        <p style="color:red; font-weight:bold;"><?= htmlspecialchars($this->error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button class="button">Login</button>
    </form>
</div>



