<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}
include('header.php');
?>
<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
<p>Here is your child’s progress:</p>
<ul>
  <li>Social Skills: ⭐⭐⭐⭐☆</li>
  <li>Creativity: ⭐⭐⭐⭐⭐</li>
  <li>Communication: ⭐⭐⭐⭐☆</li>
  <li>Attendance: Excellent</li>
</ul>
<a href="logout.php">Logout</a>
<?php include('footer.php'); ?>
