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

    <?php 
    $has_children = false;
    foreach ($children as $c) {
        if (isset($c['parent_username'], $c['status']) && $c['parent_username'] === $username && $c['status'] === "Approved") {
            $has_children = true;
            ?>
            <div class="card" style="padding:15px; margin-bottom:15px; border:1px solid #ccc; border-radius:8px; background:#fafafa;">
                <b>Child Name:</b> <?= htmlspecialchars($c['child_name']) ?><br>
                <b>Date of Birth:</b> <?= htmlspecialchars($c['dob']) ?><br>
                <b>Grade Category:</b> <?= htmlspecialchars($c['grade_category']) ?><br>
                <b>Address:</b> <?= htmlspecialchars($c['address']) ?><br>
                <p>Reports are released 12 months after enrollment.</p>
            </div>
        <?php 
        }
    }

    if (!$has_children) {
        echo "<p>No approved children found. Progress reports will appear after approval.</p>";
    }
    ?>
</div>
