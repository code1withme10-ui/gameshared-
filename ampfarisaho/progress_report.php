<?php
include "includes/auth.php";
requireParentLogin();
include "includes/functions.php";

$children = readJSON("data/children.json");
$username = $_SESSION['parent'];
?>
<link rel="stylesheet" href="css/style.css">
<?php include "includes/menu-bar.php"; ?>
<div class="container">
    <h2>Progress Report</h2>
    <?php foreach ($children as $c) {
        if ($c['parent'] == $username && $c['status'] == "Approved") {
            echo "<div class='card'>
                <b>{$c['name']} {$c['surname']}</b><br>
                Reports are released 12 months after enrollment.
            </div>";
        }
    } ?>
</div>
