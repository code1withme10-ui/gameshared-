<?php
include "includes/functions.php";

$filename = "data/parents.json";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $parents = readData($filename);

    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if parent already exists
    foreach ($parents as $parent) {
        if ($parent['email'] === $email) {
            $message = "Email already registered!";
            break;
        }
    }

    if (empty($message)) {
        $parents[] = [
            "name" => $name,
            "email" => $email,
            "password" => $password
        ];
        writeData($filename, $parents);
        $message = "Registration successful! You can now log in.";
    }
}
?>

<div class="w3-container w3-padding">
  <h2>Parent Registration</h2>

  <?php if ($message): ?>
    <div class="w3-panel w3-pale-green w3-border">
      <p><?php echo $message; ?></p>
    </div>
  <?php endif; ?>

  <form class="w3-container w3-card w3-padding" method="post">
    <label>Name</label>
    <input class="w3-input w3-border" type="text" name="name" required>

    <label>Email</label>
    <input class="w3-input w3-border" type="email" name="email" required>

    <label>Password</label>
    <input class="w3-input w3-border" type="password" name="password" required>

    <button class="w3-button w3-pink w3-margin-top" type="submit">Register</button>
  </form>
</div>

