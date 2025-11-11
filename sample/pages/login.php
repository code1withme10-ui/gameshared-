<?php
session_start();
include "includes/functions.php";

$filename = "data/parents.json";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $parents = readData($filename);

    foreach ($parents as $parent) {
        if ($parent['email'] === $email && password_verify($password, $parent['password'])) {
            $_SESSION['parent'] = $parent;
            header("Location: ?page=parent_dashboard");
            exit;
        }
    }
    $message = "Invalid email or password.";
}
?>

<div class="w3-container w3-padding">
  <h2>Parent Login</h2>

  <?php if ($message): ?>
    <div class="w3-panel w3-pale-red w3-border">
      <p><?php echo $message; ?></p>
    </div>
  <?php endif; ?>

  <form class="w3-container w3-card w3-padding" method="post">
    <label>Email</label>
    <input class="w3-input w3-border" type="email" name="email" required>

    <label>Password</label>
    <input class="w3-input w3-border" type="password" name="password" required>

    <button class="w3-button w3-pink w3-margin-top" type="submit">Login</button>
  </form>
</div>

