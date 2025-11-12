<?php
session_start();
if (!isset($_SESSION['parent_id'])) {
    header("Location: parent_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="webstyle.css">
</head>
<body>
<header><h1>Parent Dashboard</h1></header>
<?php include('menu-bar.php'); ?>
<div class="container">
<?php
include('config.php');
$parent_name = $_SESSION['parent_name'];

echo "<h2>Welcome, $parent_name</h2>";

$sql = "SELECT * FROM admissions WHERE parent_name='$parent_name'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
        echo "<p><strong>Child:</strong> " . $row['child_name'] . "</p>";
        echo "<p><strong>Age:</strong> " . $row['child_age'] . "</p>";
        echo "<p><strong>Status:</strong> " . $row['status'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No children registered yet.</p>";
}
?>
<br>
<a href="registration.php"><button>Register Another Child</button></a>
<br><br>
<a href="logout.php"><button>Logout</button></a>
</div>
</body>
</html>
