<link rel="stylesheet" href="style.css">
<?php include 'navbar.php'; ?>

<h1>Headmaster Dashboard</h1>

<?php
$admissions = json_decode(file_get_contents('admissions.json'), true);

foreach ($admissions as $index => $entry) {
    echo "<p>";
    echo "Parent: " . $entry['parent_name'] . "<br>";
    echo "Child: " . $entry['child_name'] . "<br>";
    echo "Status: " . $entry['status'] . "<br>";

    echo "<a href='update_status.php?id=$index&status=accepted'>Accept</a> | ";
    echo "<a href='update_status.php?id=$index&status=declined'>Decline</a>";
    echo "</p><hr>";
}
?>
