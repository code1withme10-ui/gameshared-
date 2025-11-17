<?php
include "includes/auth.php";
requireHeadmasterLogin();
include "includes/functions.php";

$children = readJSON("data/children.json");

if (isset($_GET['approve'])) {
    $children[$_GET['approve']]['status'] = "Approved";
    writeJSON("data/children.json", $children);
}

if (isset($_GET['decline'])) {
    $children[$_GET['decline']]['status'] = "Declined";
    writeJSON("data/children.json", $children);
}
?>
<link rel="stylesheet" href="css/style.css">
<?php include "includes/menu-bar.php"; ?>
<div class="container">
    <h2>Headmaster Dashboard</h2>

    <?php foreach ($children as $i => $c): ?>
        <div class="card">
            <b><?= $c['name'] . " " . $c['surname'] ?></b><br>
            Parent: <?= $c['parent'] ?><br>
            Age: <?= $c['age'] ?><br>
            Race: <?= $c['race'] ?><br>
            Gender: <?= $c['gender'] ?><br>
            Status: <b><?= $c['status'] ?></b><br><br>

            <a class="button" href="?approve=<?= $i ?>">Approve</a>
            <a class="button" href="?decline=<?= $i ?>" style="background:red;">Decline</a>
        </div>
    <?php endforeach; ?>
</div>
