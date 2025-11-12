<?php
$username = $_POST['username'];
$password = $_POST['password'];

$correct_user = "headmaster";
$correct_pass = "secure123";

if ($username !== $correct_user || $password !== $correct_pass) {
    echo "<script>alert('Invalid login'); window.location='headmaster_login.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Headmaster Dashboard</title>
    <link rel="stylesheet" href="webstyle.css">
</head>
<body>
<header><h1>Headmaster Dashboard</h1></header>
<?php include('menu-bar.php'); ?>
<div class="container">
    <h2>Pending Admissions</h2>
    <?php
    include('config.php');
    if (isset($_GET['approve'])) {
        $id = $_GET['approve'];
        $conn->query("UPDATE admissions SET status='Approved' WHERE id=$id");
        echo "<p style='color:green;'>Child ID $id approved!</p>";
    }

    $sql = "SELECT * FROM admissions";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1' style='margin:auto;'>";
        echo "<tr><th>ID</th><th>Parent</th><th>Child</th><th>Age</th><th>Status</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['parent_name']."</td>";
            echo "<td>".$row['child_name']."</td>";
            echo "<td>".$row['child_age']."</td>";
            echo "<td>".$row['status']."</td>";
            echo "<td><a href='headmaster_dashboard.php?approve=".$row['id']."'><button>Approve</button></a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No admissions found.</p>";
    }
    ?>
</div>
</body>
</html>
