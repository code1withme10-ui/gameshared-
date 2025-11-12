<?php include('includes/header.php'); include('database/connection.php'); session_start(); ?>

<div class="container">
<?php if (!isset($_SESSION['parent_id'])) { ?>
    <p class="error">Please <a href='parent_login.php'>login as parent</a> first.</p>
<?php } else { ?>
    <h2>Admission Form</h2>
    <form method="POST">
        <label>Child Name:</label><br>
        <input type="text" name="child_name" required><br><br>

        <label>Child Age:</label><br>
        <input type="number" name="child_age" required><br><br>

        <button type="submit" name="register">Register Child</button>
    </form>
<?php } ?>
</div>

<?php
if (isset($_POST['register'])) {
    $pid = $_SESSION['parent_id'];
    $pname = $_SESSION['parent_name'];
    $email = $_SESSION['email'];
    $cname = $_POST['child_name'];
    $age = $_POST['child_age'];

    $sql = "INSERT INTO admissions (parent_id, parent_name, child_name, child_age, parent_email, status)
            VALUES ('$pid', '$pname', '$cname', '$age', '$email', 'Pending')";
    if ($conn->query($sql)) header("Location: parent_dashboard.php");
    else echo "<p class='error'>Error: " . $conn->error . "</p>";
}
?>

<?php include('includes/footer.php'); ?>
