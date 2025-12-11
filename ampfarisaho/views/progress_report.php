<?php include __DIR__ . '/../../includes/menu-bar.php'; ?>
<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Progress Report</h2>

    <?php if(empty($this->my_children)): ?>
        <p>No approved children found. Progress reports will appear after approval.</p>
    <?php else: ?>
        <?php foreach($this->my_children as $c): ?>
            <?php if($c['status'] === 'Approved'): ?>
                <div class="card">
                    <b>Child Name:</b> <?= htmlspecialchars($c['child_name']) ?><br>
                    <b>Date of Birth:</b> <?= htmlspecialchars($c['dob']) ?><br>
                    <b>Grade Category:</b> <?= htmlspecialchars($c['grade_category']) ?><br>
                    <b>Address:</b> <?= htmlspecialchars($c['address']) ?><br>
                    <p>Reports are released 12 months after enrollment.</p>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>




