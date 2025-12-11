<?php include __DIR__ . '/../../includes/menu-bar.php'; ?>
<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Parent Dashboard</h2>

    <?php if ($this->parent_info): ?>
        <h3>Your Profile</h3>
        <p><b>Full Name:</b> <?= htmlspecialchars($this->parent_info['full_name']) ?></p>
        <p><b>Email:</b> <?= htmlspecialchars($this->parent_info['email']) ?></p>
        <p><b>Phone:</b> <?= htmlspecialchars($this->parent_info['phone']) ?></p>
        <p><b>Relationship:</b> <?= htmlspecialchars($this->parent_info['relationship']) ?></p>
        <p><b>Address:</b> <?= htmlspecialchars($this->parent_info['address']) ?></p>
    <?php endif; ?>

    <h3>Your Child(ren)</h3>
    <?php if (empty($this->my_children)): ?>
        <p>You have not registered any children yet.</p>
    <?php else: ?>
        <?php foreach ($this->my_children as $child): ?>
            <div class="card">
                <h4><?= htmlspecialchars($child['child_name']) ?></h4>
                <p><b>Date of Birth:</b> <?= htmlspecialchars($child['dob']) ?></p>
                <p><b>Gender:</b> <?= htmlspecialchars($child['gender']) ?></p>
                <p><b>Grade Category:</b> <?= htmlspecialchars($child['grade_category']) ?></p>
                <p><b>Address:</b> <?= htmlspecialchars($child['address']) ?></p>
                <p><b>Status:</b> <span style="color:<?= $child['status']=='Approved'?'green':($child['status']=='Declined'?'red':'orange') ?>"><?= htmlspecialchars($child['status']) ?></span></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>



