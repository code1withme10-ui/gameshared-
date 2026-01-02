<?php
include __DIR__ . '/../includes/menu-bar.php';
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Parent Dashboard</h2>

    <?php if(!empty($thisPage->parent_info)): ?>
        <h3>Your Profile</h3>
        <p><b>Full Name:</b> <?= htmlspecialchars($thisPage->parent_info['full_name']) ?></p>
        <p><b>Email:</b> <?= htmlspecialchars($thisPage->parent_info['email']) ?></p>
        <p><b>Phone:</b> <?= htmlspecialchars($thisPage->parent_info['phone']) ?></p>
        <p><b>Relationship:</b> <?= htmlspecialchars($thisPage->parent_info['relationship']) ?></p>
        <p><b>Address:</b> <?= htmlspecialchars($thisPage->parent_info['address']) ?></p>
    <?php endif; ?>

    <h3>Your Child(ren)</h3>

    <?php if(empty($thisPage->my_children)): ?>
        <p>You have not registered any children yet.</p>
    <?php else: ?>
        <?php foreach($thisPage->my_children as $child): ?>
            <div class="card" style="border:2px solid <?= $child['status']=='Approved'?'green':($child['status']=='Declined'?'red':'orange') ?>; padding:10px; margin-bottom:15px;">
                <h4><?= htmlspecialchars($child['child_name']) ?></h4>
                <p><b>Date of Birth:</b> <?= htmlspecialchars($child['dob']) ?></p>
                <p><b>Gender:</b> <?= htmlspecialchars($child['gender']) ?></p>
                <p><b>Grade Category:</b> <?= htmlspecialchars($child['grade_category']) ?></p>
                <p><b>Address:</b> <?= htmlspecialchars($child['address']) ?></p>
                <p><b>Status:</b> <span style="color:<?= $child['status']=='Approved'?'green':($child['status']=='Declined'?'red':'orange') ?>"><?= htmlspecialchars($child['status']) ?></span></p>
                <p><b>Documents:</b><br>
                    <?php if(isset($child['birth_certificate']) && file_exists($child['birth_certificate'])): ?>
                        <a href="<?= htmlspecialchars($child['birth_certificate']) ?>" target="_blank">Birth Certificate</a><br>
                    <?php endif; ?>
                    <?php if(isset($child['parent_id']) && file_exists($child['parent_id'])): ?>
                        <a href="<?= htmlspecialchars($child['parent_id']) ?>" target="_blank">Parent/Guardian ID</a>
                    <?php endif; ?>
                </p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h3>Add New Child</h3>

    <?php if(!empty($thisPage->errors)): ?>
        <?php foreach($thisPage->errors as $e): ?>
            <p style="color:red; font-weight:bold;"><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if(!empty($thisPage->success_message)): ?>
        <p style="color:green; font-weight:bold;"><?= htmlspecialchars($thisPage->success_message) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="new_child" value="1">

        <input name="child_name" placeholder="Child Full Name" required><br><br>

        <label>Date of Birth</label><br>
        <input type="date" name="child_dob" required><br><br>

        <label>Gender</label><br>
        <select name="child_gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select><br><br>

        <label>Grade Category</label><br>
        <select name="grade_category" required>
            <option value="">Select Category</option>
            <option value="Infants">Infants (6–12 months)</option>
            <option value="Toddlers">Toddlers (1–3 years)</option>
            <option value="Playgroup">Playgroup (3–4 years)</option>
            <option value="Pre-School">Pre-School (4–5 years)</option>
        </select><br><br>

        <input name="child_address" placeholder="Child Residential Address" required><br><br>

        <h4>Upload Documents</h4>
        <label>Birth Certificate (PDF/JPG/PNG, ≤2MB)</label><br>
        <input type="file" name="birth_cert" accept=".pdf,.jpg,.jpeg,.png" required><br><br>

        <label>Parent/Guardian ID (PDF/JPG/PNG, ≤2MB)</label><br>
        <input type="file" name="parent_id" accept=".pdf,.jpg,.jpeg,.png" required><br><br>

        <button class="button">Submit New Child</button>
    </form>
</div>




