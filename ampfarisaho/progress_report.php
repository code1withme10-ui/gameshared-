<?php
include "includes/functions.php";
$children = readJSON("data/children.json");
?>

<link rel="stylesheet" href="css/style.css">
<?php include "includes/menu-bar.php"; ?>

<div class="container">
    <h2>Progress Reports</h2>

    <?php foreach ($children as $c): ?>
        <?php if ($c['status'] == "Approved"): ?>
            <div class="card">
                <b><?= $c['name'] . " " . $c['surname'] ?></b><br>
                <i>Progress reports are released 12 months after enrollment.</i>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

</div>
