<?php include __DIR__ . '/../includes/menu-bar.php'; ?>

<div class="container">
    <h2>Parent & Child Admission</h2>

    <?php 
        $errors = $thisPage->errors ?? [];
        $success_message = $thisPage->success_message ?? '';
    ?>

    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $e): ?>
            <p style="color:red; font-weight:bold;"><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <p style="color:green; font-weight:bold;"><?= htmlspecialchars($success_message) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <h3>Create Parent Login</h3>
        <input name="parent_username" placeholder="Create Username" required><br><br>
        <input name="parent_password" type="password" placeholder="Create Password" required><br><br>

        <h3>Parent Information</h3>
        <input name="parent_name" placeholder="Full Name" required><br><br>
        <input name="parent_relationship" placeholder="Relationship to Child" required><br><br>
        <input name="parent_email" placeholder="Email Address" required><br><br>
        <input name="parent_phone" placeholder="Phone Number" required><br><br>
        <input name="parent_address" placeholder="Residential Address" required><br><br>

        <h3>Child Information</h3>
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

        <h3>Upload Documents</h3>
        <label>Birth Certificate (PDF/JPG/PNG, ≤2MB)</label><br>
        <input type="file" name="birth_cert" accept=".pdf,.jpg,.jpeg,.png" required><br><br>
        <label>Parent/Guardian ID (PDF/JPG/PNG, ≤2MB)</label><br>
        <input type="file" name="parent_id" accept=".pdf,.jpg,.jpeg,.png" required><br><br>

        <button class="button">Submit Application</button>
    </form>
</div>





