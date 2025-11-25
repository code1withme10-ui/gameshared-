<link rel="stylesheet" href="css/style.css">
<?php
include "includes/functions.php";

if ($_POST) {
    $errors = [];

    // Validate email
    if (!filter_var($_POST['parent_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate phone
    if (!preg_match("/^[0-9]{10}$/", $_POST['parent_phone'])) {
        $errors[] = "Phone number must be 10 digits.";
    }

    // Validate required login fields
    if (empty($_POST['parent_username']) || empty($_POST['parent_password'])) {
        $errors[] = "Username and Password are required.";
    }

    // Validate documents
    $allowed_ext = ["pdf","jpg","jpeg","png"];
    foreach (["birth_cert","parent_id"] as $file) {
        if ($_FILES[$file]["error"] !== 0) {
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

    if (!empty($errors)) {
        foreach ($errors as $e) {
            echo "<p style='color:red; font-weight:bold;'>$e</p>";
        }
    } else {

        // Load JSON files
        $parents = readJSON("data/parents.json");
        $children = readJSON("data/children.json");

        // Ensure parent folder exists
        $upload_dir = "uploads/" . $_POST['parent_username'] . "/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        // Save uploaded files
        $birth_cert_file = $upload_dir . "birth_cert_" . time() . "_" . $_FILES['birth_cert']['name'];
        move_uploaded_file($_FILES['birth_cert']['tmp_name'], $birth_cert_file);

        $parent_id_file = $upload_dir . "parent_id_" . time() . "_" . $_FILES['parent_id']['name'];
        move_uploaded_file($_FILES['parent_id']['tmp_name'], $parent_id_file);

        // Save parent to JSON
        $parents[] = [
            "username" => $_POST['parent_username'],
            "password" => $_POST['parent_password'],
            "email" => $_POST['parent_email'],
            "full_name" => $_POST['parent_name'],
            "phone" => $_POST['parent_phone'],
            "relationship" => $_POST['parent_relationship'],
            "address" => $_POST['parent_address']
        ];
        writeJSON("data/parents.json", $parents);

        // Save child to JSON
        $children[] = [
            "parent_username" => $_POST['parent_username'],
            "child_name" => $_POST['child_name'],
            "dob" => $_POST['child_dob'],
            "gender" => $_POST['child_gender'],
            "grade_category" => $_POST['grade_category'],
            "address" => $_POST['child_address'],
            "birth_certificate" => $birth_cert_file,
            "parent_id" => $parent_id_file,
            "status" => "Awaiting approval"
        ];
        writeJSON("data/children.json", $children);

        echo "<p style='color:green; font-weight:bold;'>Admission submitted successfully! You may now log in.</p>";
    }
}
?>

<?php include "includes/menu-bar.php"; ?>

<div class="container">
    <h2>Parent & Child Admission</h2>

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
