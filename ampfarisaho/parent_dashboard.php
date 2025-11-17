<?php
include "includes/auth.php";
requireParentLogin();
include "includes/functions.php";

$username = $_SESSION['parent'];
$children = readJSON("data/children.json");

if ($_POST) {
    $children[] = [
        "parent" => $username,
        "name" => $_POST['name'],
        "surname" => $_POST['surname'],
        "age" => $_POST['age'],
        "race" => $_POST['race'],
        "gender" => $_POST['gender'],
        "status" => "Awaiting approval"
    ];
    writeJSON("data/children.json", $children);
}
?>
<link rel="stylesheet" href="css/style.css">
<?php include "includes/menu-bar.php"; ?>
<div class="container">
    <h2>Parent Dashboard</h2>
    <p>Welcome, <b><?= $username ?></b></p>

    <h3>Your Registered Children</h3>
    <?php
    foreach ($children as $c) {
        if ($c['parent'] == $username) {
            echo "<div class='card'>
                <b>{$c['name']} {$c['surname']}</b><br>
                Age: {$c['age']}<br>
                Status: <b>{$c['status']}</b>
            </div>";
        }
    }
    ?>

    <h3>Add Another Child</h3>
    <form method="POST">
        <input name="name" placeholder="Name" required>
        <input name="surname" placeholder="Surname" required>
        <input name="age" placeholder="Age" required>
        <input name="race" placeholder="Race" required>
        <input name="gender" placeholder="Gender" required>
        <button class="button">Add Child</button>
    </form>
</div>
