<?php
include('header.php');
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM parents WHERE username='$username'";
  $result = $conn->query($sql);
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['username'] = $username;
    header("Location: parent_dashboard.php");
  } else {
    echo "<p style='color:red;'>Invalid login details!</p>";
  }
}
?>
<h2>Parent Login</h2>
<form method="POST">
  <label>Username:</label><br>
  <input type="text" name="username" required><br>
  <label>Password:</label><br>
  <input type="password" name="password" required><br>
  <button type="submit">Login</button>
</form>
<?php include('footer.php'); ?>


