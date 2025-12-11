<link rel="stylesheet" href="css/style.css">
<?php include __DIR__ . '/../../includes/menu-bar.php'; ?>

<div class="container">
    <h2>Headmaster Dashboard</h2>
    <p>Review and manage applications submitted by parents.</p>

    <?php if(empty($this->children)): ?>
        <p>No applications found.</p>
    <?php else: ?>
        <?php foreach($this->children as $i=>$child): ?>
            <div class="card">
                <h3><?= htmlspecialchars($child['child_name']) ?></h3>
                <?php $parent_info = $this->getParentInfo($child['parent_username']); ?>
                <?php if($parent_info): ?>
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
                <b>Grade Category:</b> <?= htmlspecialchars($child['grade_category']) ?><br>
                <b>Status:</b> <span style="color:<?= $child['status']=='Approved'?'green':($child['status']=='Declined'?'red':'orange') ?>"><?= htmlspecialchars($child['status']) ?></span><br><br>

                <b>Documents:</b><br>
                <?php if(isset($child['birth_certificate']) && file_exists($child['birth_certificate'])): ?>
                    <a href="<?= htmlspecialchars($child['birth_certificate']) ?>" target="_blank">Birth Certificate</a><br>
                <?php endif; ?>
                <?php if(isset($child['parent_id']) && file_exists($child['parent_id'])): ?>
                    <a href="<?= htmlspecialchars($child['parent_id']) ?>" target="_blank">Parent/Guardian ID</a><br>
                <?php endif; ?>
                <br>
                <a class="button" href="index.php?page=headmaster_dashboard&approve=<?= $i ?>">Approve</a>
                <a class="button" style="background:red;" href="index.php?page=headmaster_dashboard&decline=<?= $i ?>">Decline</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


