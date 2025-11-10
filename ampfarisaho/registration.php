<?php
include('includes/header.php');
include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $sql = "INSERT INTO parents (username, password) VALUES ('$username', '$password')";
  $conn->query($sql);
  header("Location: confirm.php");
}
?>
<h2>Parent Registration</h2>
<form method="POST">
  <label>Username:</label><br>
  <input type="text" name="username" required><br>
  <label>Password:</label><br>
  <input type="password" name="password" required><br>
  <button type="submit">Register</button>
</form>
<?php include('includes/footer.php'); ?>
