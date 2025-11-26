<?php
session_start();

// JSON file where all applications are stored
$data_file = 'users.json';

// ----------------------------------------------------------------------
// FUNCTIONS FOR JSON READ/WRITE
// ----------------------------------------------------------------------
function get_apps($file) {
    if (!file_exists($file) || filesize($file) == 0) {
        return [];
    }
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function save_apps($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// ----------------------------------------------------------------------
// HANDLE FORM SUBMISSION
// ----------------------------------------------------------------------
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ---------------------- 1. Collect Parent Inputs ----------------------
    $parentName = trim($_POST['parentName']);
    $relationship = trim($_POST['relationship']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $parentAddress = trim($_POST['parentAddress']);

    // ---------------------- 2. Collect Child Inputs -----------------------
    $childName = trim($_POST['childName']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $grade = $_POST['grade'];

    // Child address = parent address
    $childAddress = $parentAddress;

    // Calculate age in months
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $ageMonths = ($today->diff($birthDate)->y * 12) + $today->diff($birthDate)->m;

    // ---------------------- 3. Validate Age vs Grade ----------------------
    $validGrade = false;
    if ($grade == "Infants" && $ageMonths >= 6 && $ageMonths <= 12) $validGrade = true;
    if ($grade == "Toddlers" && $ageMonths >= 12 && $ageMonths <= 36) $validGrade = true;
    if ($grade == "Playgroup" && $ageMonths >= 36 && $ageMonths <= 48) $validGrade = true;
    if ($grade == "Preschool" && $ageMonths >= 48 && $ageMonths <= 60) $validGrade = true;

    if (!$validGrade) {
        $message = "<p style='color:red;'>Selected category does NOT match child age.</p>";
    }

    // ---------------------- 4. Files Upload ----------------------
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    $uploadDir = "uploads/";

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Birth Cert
    $birthFile = $_FILES["birthCert"];
    $birthExt = strtolower(pathinfo($birthFile["name"], PATHINFO_EXTENSION));
    $birthFilename = "";

    if ($birthFile["error"] === 0) {
        if (!in_array($birthExt, $allowed) || $birthFile["size"] > $maxSize) {
            $message = "<p style='color:red;'>Invalid Birth Certificate file.</p>";
        } else {
            $birthFilename = uniqid("birth_") . "." . $birthExt;
            move_uploaded_file($birthFile["tmp_name"], $uploadDir . $birthFilename);
        }
    } else {
        $message = "<p style='color:red;'>Birth Certificate upload failed.</p>";
    }

    // Parent ID
    $idFile = $_FILES["parentID"];
    $idExt = strtolower(pathinfo($idFile["name"], PATHINFO_EXTENSION));
    $idFilename = "";

    if ($idFile["error"] === 0) {
        if (!in_array($idExt, $allowed) || $idFile["size"] > $maxSize) {
            $message = "<p style='color:red;'>Invalid Parent ID file.</p>";
        } else {
            $idFilename = uniqid("id_") . "." . $idExt;
            move_uploaded_file($idFile["tmp_name"], $uploadDir . $idFilename);
        }
    } else {
        $message = "<p style='color:red;'>Parent ID upload failed.</p>";
    }

    // ---------------------- 5. Save to JSON ----------------------
    if ($message == "") {
        $apps = get_apps($data_file);

        // Safely check if parent email already exists
        $emailExists = false;
        foreach ($apps as $item) {
            if (isset($item['parent']['email']) && $item['parent']['email'] == $email) {
                $emailExists = true;
                break;
            }
        }

        if ($emailExists) {
            $message = "<p style='color:red;'>Email already registered.</p>";
        } else {
            $newApp = [
                "applicationID" => uniqid("APP-"),
                "status" => "Pending",
                "parent" => [
                    "name" => $parentName,
                    "relationship" => $relationship,
                    "email" => $email,
                    "password" => $password,
                    "phone" => $phone,
                    "address" => $parentAddress
                ],
                "child" => [
                    "name" => $childName,
                    "dob" => $dob,
                    "gender" => $gender,
                    "grade" => $grade,
                    "ageMonths" => $ageMonths,
                    "address" => $childAddress
                ],
                "documents" => [
                    "birthCertificate" => $birthFilename,
                    "parentID" => $idFilename
                ]
            ];

            $apps[] = $newApp;
            save_apps($data_file, $apps);

            $message = "<p style='color:green;'>Application Submitted Successfully! Your Application is now Pending.</p>";
        }
    }
}

// Check if user is logged in for navbar
$is_logged_in = isset($_SESSION['user_email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admission Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ==================== NAVBAR ==================== -->
<div class="navbar">
    <span class="navbar-title">Humulani Pre School</span>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if ($is_logged_in): ?>
                <li><a href="dashboard.php?action=logout">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<div class="page-container">

<h1>School Admission Form</h1>
<?php echo $message; ?>

<p>Already have an account? <a href="login.php">Login here</a></p>

<form method="POST" enctype="multipart/form-data">

    <!-- ==================== CHILD SECTION ==================== -->
    <h2>SECTION 1: CHILD DETAILS</h2>

    <label>Child Full Name:</label>
    <input type="text" name="childName" required><br><br>

    <label>Date of Birth:</label>
    <input type="date" id="dob" name="dob" required><br><br>

    <label>Gender:</label>
    <select name="gender" required>
        <option value="">Select Gender</option>
        <option>Male</option>
        <option>Female</option>
    </select><br><br>

    <label>Grade Applying For:</label>
    <select id="grade" name="grade" required>
        <option value="">Select Category</option>
        <option value="Infants">Infants (6–12 months)</option>
        <option value="Toddlers">Toddlers (1–3 years)</option>
        <option value="Playgroup">Playgroup (3–4 years)</option>
        <option value="Preschool">Pre-School (4–5 years)</option>
    </select><br><br>

    <p id="gradeError" style="color:red;"></p>

    <!-- ==================== PARENT SECTION ==================== -->
    <h2>SECTION 2: PARENT / GUARDIAN DETAILS</h2>

    <label>Parent Full Name:</label>
    <input type="text" name="parentName" required><br><br>

    <label>Relationship:</label>
    <input type="text" name="relationship" required><br><br>

    <label>Email Address (Username):</label>
    <input type="email" name="email" required><br><br>

    <label>Create Password:</label>
    <input type="password" name="password" required><br><br>

    <label>Phone Number:</label>
    <input type="text" name="phone" required><br><br>

    <label>Residential Address:</label>
    <input type="text" name="parentAddress" required><br><br>

    <!-- ==================== UPLOAD SECTION ==================== -->
    <h2>SECTION 3: UPLOAD DOCUMENTS</h2>

    <label>Birth Certificate:</label>
    <input type="file" name="birthCert" required><br><br>

    <label>Parent ID:</label>
    <input type="file" name="parentID" required><br><br>

    <button type="submit">SUBMIT APPLICATION</button>
</form>

</div>

<!-- ==================== FOOTER ==================== -->
<footer>
    <p>&copy; 2026 Humulani Pre School</p>
</footer>

<!-- ==================== JS TO ENABLE GRADE BASED ON DOB ==================== -->
<script>
document.getElementById('dob').addEventListener('change', function() {
    const dob = new Date(this.value);
    const today = new Date();

    if (!dob) return;

    let ageMonths = (today.getFullYear() - dob.getFullYear()) * 12;
    ageMonths += today.getMonth() - dob.getMonth();
    if (today.getDate() < dob.getDate()) ageMonths--;

    const gradeSelect = document.getElementById('grade');
    const options = gradeSelect.options;
    let matched = false;

    for (let i = 1; i < options.length; i++) {
        options[i].disabled = true;

        const grade = options[i].value;
        if (grade === "Infants" && ageMonths >= 6 && ageMonths <= 12) options[i].disabled = false;
        if (grade === "Toddlers" && ageMonths >= 12 && ageMonths <= 36) options[i].disabled = false;
        if (grade === "Playgroup" && ageMonths >= 36 && ageMonths <= 48) options[i].disabled = false;
        if (grade === "Preschool" && ageMonths >= 48 && ageMonths <= 60) options[i].disabled = false;

        if (!options[i].disabled) matched = true;
    }

    if (!matched) {
        document.getElementById('gradeError').textContent = "No grade matches your child's age!";
    } else {
        document.getElementById('gradeError').textContent = "";
    }

    gradeSelect.value = "";
});
</script>

</body>
</html>
