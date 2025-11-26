<?php
include __DIR__ . "/includes/auth.php";
requireParentLogin();
include __DIR__ . "/includes/functions.php";

$children = readJSON(__DIR__ . "/data/children.json");
$username = $_SESSION['parent'];
?>

<link rel="stylesheet" href="/public/css/style.css">
<?php include __DIR__ . "/includes/menu-bar.php"; ?>

<div class="container">
    <h2>Progress Report</h2>

    <?php 
    $has_children = false;

    foreach ($children as $c) {
        if (isset($c['parent_username'], $c['status']) && $c['parent_username'] === $username && $c['status'] === "Approved") {
            $has_children = true;
            ?>
            <div class="card" style="padding:15px; margin-bottom:15px; border:1px solid #ccc; border-radius:8px; background:#fafafa;">
                <p><b>Child Name:</b> <?= htmlspecialchars($c['child_name']) ?></p>
                <p><b>Date of Birth:</b> <?= htmlspecialchars($c['dob']) ?></p>
                <p><b>Grade Category:</b> <?= htmlspecialchars($c['grade_category']) ?></p>
                <p><b>Address:</b> <?= htmlspecialchars($c['address']) ?></p>
                <p><i>Progress reports are released 12 months after enrollment.</i></p>
            </div>
        <?php 
        }
    }

    if (!$has_children) {
        echo "<p>No approved children found. Progress reports will appear after approval.</p>";
    }
    ?>
</div>

