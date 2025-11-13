<?php include('includes/header.php'); include('database/connection.php'); ?>

<div class="container">
    <h2>Headmaster Dashboard</h2>
    <table>
        <tr><th>Parent Name</th><th>Child Name</th><th>Age</th><th>Status</th><th>Action</th></tr>
        <?php
        $result = $conn->query("SELECT * FROM admissions");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['parent_name']}</td>
                <td>{$row['child_name']}</td>
                <td>{$row['child_age']}</td>
                <td>{$row['status']}</td>
                <td>
                    <form method='POST'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <button type='submit' name='approve'>Approve</button>
                    </form>
                </td>
            </tr>";
        }
        if (isset($_POST['approve'])) {
            $id = $_POST['id'];
            $conn->query("UPDATE admissions SET status='Approved' WHERE id=$id");
            echo "<meta http-equiv='refresh' content='0'>";
        }
        ?>
    </table>
</div>

<?php include('includes/footer.php'); ?>
