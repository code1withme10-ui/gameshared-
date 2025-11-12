<?php
session_start();
include('config.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admission - Little Ones Preschool</title>
    <link rel="stylesheet" href="webstyle.css">
</head>
<body>
<header><h1>Child Admission</h1></header>
<?php include('menu-bar.php'); ?>
<div class="container">
<?php
if (!isset($_SESSION['parent_name'])) {
    echo "<p>You must be logged in as a parent to register a child.</p>";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parent = $_SESSION['parent_name'];
    $child = $_POST['child_name'];
    $age = $_POST['child_age'];

    $sql = "INSERT INTO admissions (parent_name, child_name, child_age)
            VALUES ('$parent', '$child', '$age')";

    if ($conn->query($sql) === TRUE) {
        header("Location: parent_dashboard.php");
        exit();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}
?>
<form method="POST" action="">
    <label>Child Name:</label><br>
    <input type="text" name="child_name" required><br><br>

    <label>Child Age:</label><br>
    <input type="number" name="child_age" required><br><br>

    <button type="submit">Register Child</button>
</form>
</div>
</body>
</html>
