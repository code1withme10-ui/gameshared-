<?php
// Include auth and functions from new structure
include __DIR__ . '/../includes/auth.php';
requireParentLogin();
include __DIR__ . '/../includes/functions.php';

// Load children JSON
$children = readJSON(__DIR__ . "/../data/children.json");
$username = $_SESSION['parent'] ?? '';

// Filter only approved children for this parent
$approved_children = array_filter($children, fn($c) => isset($c['parent_username'], $c['status']) && $c['parent_username'] === $username && $c['status'] === 'Approved');
?>

<!-- Link to CSS in public folder -->
<link rel="stylesheet" href="css/style.css">

<?php 
// Include menu bar from new structure
include __DIR__ . '/../includes/menu-bar.php'; 
?>

<div class="container">
    <h2>Progress Report</h2>

    <?php if(empty($approved_children)): ?>
        <p>No approved children found. Progress reports will appear after approval.</p>
    <?php else: ?>
        <?php foreach($approved_children as $c): ?>
            <div class="card">
                <b>Child Name:</b> <?= htmlspecialchars($c['child_name']) ?><br>
                <b>Date of Birth:</b> <?= htmlspecialchars($c['dob']) ?><br>
                <b>Grade Category:</b> <?= htmlspecialchars($c['grade_category']) ?><br>
                <b>Address:</b> <?= htmlspecialchars($c['address']) ?><br>
                <p>Reports are released 12 months after enrollment.</p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>



