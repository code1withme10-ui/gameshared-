<?php include('includes/header.php'); include('database/connection.php'); session_start();
if (!isset($_SESSION['parent_id'])) header("Location: parent_login.php");
$pid = $_SESSION['parent_id'];
$result = $conn->query("SELECT * FROM admissions WHERE parent_id='$pid'");
?>

<div class="container">
    <h2>Welcome, <?php echo $_SESSION['parent_name']; ?></h2>
    <h3>Your Registered Children</h3>

    <table>
        <tr><th>Child Name</th><th>Age</th><th>Status</th></tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['child_name']; ?></td>
            <td><?php echo $row['child_age']; ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <?php } ?>
    </table>

    <form action="admission.php" style="margin-top:20px;">
        <button>Add Another Child</button>
    </form>
    <form action="logout.php" method="POST" style="margin-top:10px;">
        <button type="submit">Logout</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
