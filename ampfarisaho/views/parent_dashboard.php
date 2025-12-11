<?php
// Include auth and functions from new structure
include __DIR__ . '/../includes/auth.php';
requireParentLogin(); // ensures $_SESSION['parent'] is set
include __DIR__ . '/../includes/functions.php';

// Load JSON files
$parents = readJSON(__DIR__ . "/../data/parents.json");
$children = readJSON(__DIR__ . "/../data/children.json");

// Logged-in parent info
$parent_username = $_SESSION['parent'] ?? '';
$parent_info = null;
foreach ($parents as $p) {
    if (isset($p['username']) && $p['username'] === $parent_username) {
        $parent_info = $p;
        break;
    }
}

// Handle new child submission
$errors = [];
$success_message = "";
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['new_child'])) {
    // Validate required fields
    if (empty($_POST['child_name']) || empty($_POST['child_dob']) || empty($_POST['child_gender']) || empty($_POST['grade_category']) || empty($_POST['child_address'])) {
        $errors[] = "All child fields are required.";
    }

    // Validate documents
    $allowed_ext = ["pdf","jpg","jpeg","png"];
    foreach (["birth_cert","parent_id"] as $file) {
        if (!isset($_FILES[$file]) || $_FILES[$file]["error"] !== 0) {
            $errors[] = "Missing required document: $file";
        } else {
            $ext = strtolower(pathinfo($_FILES[$file]["name"], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed_ext)) {
                $errors[] = "Invalid file type for $file.";
            }
            if ($_FILES[$file]["size"] > 2*1024*1024) {
                $errors[] = "$file exceeds 2MB.";
            }
        }
    }

    if (empty($errors)) {
        // Save uploaded files
        $upload_dir = __DIR__ . "/../uploads/" . $parent_username . "/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $birth_cert_file = $upload_dir . "birth_cert_" . time() . "_" . $_FILES['birth_cert']['name'];
        move_uploaded_file($_FILES['birth_cert']['tmp_name'], $birth_cert_file);

        $parent_id_file = $upload_dir . "parent_id_" . time() . "_" . $_FILES['parent_id']['name'];
        move_uploaded_file($_FILES['parent_id']['tmp_name'], $parent_id_file);

        // Save child to JSON
        $children[] = [
            "parent_username" => $parent_username,
            "child_name" => $_POST['child_name'],
            "dob" => $_POST['child_dob'],
            "gender" => $_POST['child_gender'],
            "grade_category" => $_POST['grade_category'],
            "address" => $_POST['child_address'],
            "birth_certificate" => $birth_cert_file,
            "parent_id" => $parent_id_file,
            "status" => "Awaiting approval"
        ];
        writeJSON(__DIR__ . "/../data/children.json", $children);
        $success_message = "New child application submitted successfully!";
    }
}

// Find children for this parent
$my_children = array_filter($children, fn($c) => isset($c['parent_username']) && $c['parent_username'] === $parent_username);
?>

<!-- Link to CSS in public folder -->
<link rel="stylesheet" href="css/style.css">

<?php 
// Include menu bar from new structure
include __DIR__ . '/../includes/menu-bar.php'; 
?>

<div class="container">
    <h2>Parent Dashboard</h2>

    <?php if ($parent_info): ?>
        <h3>Your Profile</h3>
        <p><b>Full Name:</b> <?= htmlspecialchars($parent_info['full_name']) ?></p>
        <p><b>Email:</b> <?= htmlspecialchars($parent_info['email']) ?></p>
        <p><b>Phone:</b> <?= htmlspecialchars($parent_info['phone']) ?></p>
        <p><b>Relationship:</b> <?= htmlspecialchars($parent_info['relationship']) ?></p>
        <p><b>Address:</b> <?= htmlspecialchars($parent_info['address']) ?></p>
    <?php endif; ?>

    <h3>Your Child(ren)</h3>

    <?php if (empty($my_children)): ?>
        <p>You have not registered any children yet.</p>
    <?php else: ?>
        <?php foreach ($my_children as $child): ?>
            <div class="card">
                <h4><?= htmlspecialchars($child['child_name']) ?></h4>
                <p><b>Date of Birth:</b> <?= htmlspecialchars($child['dob']) ?></p>
                <p><b>Gender:</b> <?= htmlspecialchars($child['gender']) ?></p>
                <p><b>Grade Category:</b> <?= htmlspecialchars($child['grade_category']) ?></p>
                <p><b>Address:</b> <?= htmlspecialchars($child['address']) ?></p>
                <p><b>Status:</b> <span style="color:<?= $child['status']=='Approved'?'green':($child['status']=='Declined'?'red':'orange') ?>"><?= htmlspecialchars($child['status']) ?></span></p>
                <p><b>Documents:</b><br>
                    <?php if(file_exists($child['birth_certificate'])): ?>
                        <a href="<?= htmlspecialchars($child['birth_certificate']) ?>" target="_blank">Birth Certificate</a><br>
                    <?php endif; ?>
                    <?php if(file_exists($child['parent_id'])): ?>
                        <a href="<?= htmlspecialchars($child['parent_id']) ?>" target="_blank">Parent/Guardian ID</a>
                    <?php endif; ?>
                </p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <h3>Add New Child</h3>
    <?php foreach($errors as $e): ?>
        <p style="color:red; font-weight:bold;"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>
    <?php if($success_message): ?>
        <p style="color:green; font-weight:bold;"><?= htmlspecialchars($success_message) ?></p>
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


