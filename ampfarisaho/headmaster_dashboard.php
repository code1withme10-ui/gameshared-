<?php
include __DIR__ . "/includes/auth.php";
requireHeadmasterLogin();
include __DIR__ . "/includes/functions.php";

$children = readJSON(__DIR__ . "/data/children.json");
$parents = readJSON(__DIR__ . "/data/parents.json");

// Handle Approve
if (isset($_GET['approve'])) {
    $index = intval($_GET['approve']);
    if (isset($children[$index])) {
        $children[$index]['status'] = "Approved";
        writeJSON(__DIR__ . "/data/children.json", $children);
    }
    header("Location: headmaster_dashboard.php");
    exit();
}

// Handle Decline
if (isset($_GET['decline'])) {
    $index = intval($_GET['decline']);
    if (isset($children[$index])) {
        $children[$index]['status'] = "Declined";
        writeJSON(__DIR__ . "/data/children.json", $children);
    }
    header("Location: headmaster_dashboard.php");
    exit();
}

// Get parent info
function getParentInfo($parents, $username) {
    foreach ($parents as $p) {
        if (isset($p['username']) && $p['username'] === $username) {
            return $p;
        }
    }
    return null;
}
?>

<link rel="stylesheet" href="/public/css/style.css">
<?php include __DIR__ . "/includes/menu-bar.php"; ?>

<div class="container">
    <h2>Headmaster Dashboard</h2>
    <p>Review and manage all child admission applications.</p>

    <?php if (empty($children)): ?>
        <p>No applications submitted yet.</p>
    <?php else: ?>
        <?php foreach ($children as $i => $child): ?>
            <div class="card" style="padding:15px; margin-bottom:15px; border:1px solid #ccc; border-radius:8px; background:#fafafa;">
                <h3><?= htmlspecialchars($child['child_name']) ?></h3>

                <?php $parent_info = getParentInfo($parents, $child['parent_username']); ?>

                <?php if ($parent_info): ?>
                    <b>Parent Name:</b> <?= htmlspecialchars($parent_info['full_name']) ?><br>
                    <b>Relationship:</b> <?= htmlspecialchars($parent_info['relationship']) ?><br>
                    <b>Email:</b> <?= htmlspecialchars($parent_info['email']) ?><br>
                    <b>Phone:</b> <?= htmlspecialchars($parent_info['phone']) ?><br>
                    <b>Address:</b> <?= htmlspecialchars($parent_info['address']) ?><br>
                <?php else: ?>
                    <b>Parent Username:</b> <?= htmlspecialchars($child['parent_username']) ?><br>
                <?php endif; ?>

                <b>Date of Birth:</b> <?= htmlspecialchars($child['dob']) ?><br>
                <b>Gender:</b> <?= htmlspecialchars($child['gender']) ?><br>
                <b>Address:</b> <?= htmlspecialchars($child['address']) ?><br>
                <b>Grade Category:</b> <?= htmlspecialchars($child['grade_category']) ?><br>

                <b>Status:</b>
                <span style="font-weight:bold; color:
                    <?= $child['status']=='Approved'?'green':($child['status']=='Declined'?'red':'orange') ?>;">
                    <?= htmlspecialchars($child['status']) ?>
                </span>
                <br><br>

                <!-- Supporting Documents -->
                <b>Documents:</b><br>

                <?php if (!empty($child['birth_certificate'])): ?>
                    <a href="/<?= htmlspecialchars($child['birth_certificate']) ?>" target="_blank">
                        View Birth Certificate
                    </a><br>
                <?php endif; ?>

                <?php if (!empty($child['parent_id'])): ?>
                    <a href="/<?= htmlspecialchars($child['parent_id']) ?>" target="_blank">
                        View Parent/Guardian ID
                    </a><br>
                <?php endif; ?>

                <br>
                <a class="button" href="headmaster_dashboard.php?approve=<?= $i ?>">Approve</a>
                <a class="button" href="headmaster_dashboard.php?decline=<?= $i ?>" style="background:red;">Decline</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

