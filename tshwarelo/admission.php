<?php
session_start();

// ----------------- Paths -----------------
$users_file = __DIR__ . '/storage/users.json';           // storage/users.json (same-level storage folder)
$uploads_fs_dir = __DIR__ . '/public/uploads/';         // filesystem path where files are stored
$uploads_url_dir = 'uploads/';                          // URL path used in HTML (relative to public/index.php)

// ensure uploads folder exists
if (!file_exists($uploads_fs_dir)) {
    mkdir($uploads_fs_dir, 0777, true);
}

// ----------------- Helpers: JSON read/write -----------------
function get_apps($file) {
    if (!file_exists($file) || filesize($file) == 0) {
        return [];
    }
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function save_apps($file, $data) {
    // write atomically
    $tmp = $file . '.tmp';
    file_put_contents($tmp, json_encode($data, JSON_PRETTY_PRINT));
    rename($tmp, $file);
}

// ----------------- Form handling -----------------
$message = "";
$old = []; // to re-populate form values if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // collect and sanitize
    $parentName    = trim($_POST['parentName'] ?? '');
    $relationship  = trim($_POST['relationship'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $password      = trim($_POST['password'] ?? '');
    $phone         = trim($_POST['phone'] ?? '');
    $parentAddress = trim($_POST['parentAddress'] ?? '');

    $childName = trim($_POST['childName'] ?? '');
    $dobRaw    = trim($_POST['dob'] ?? '');
    $gender    = trim($_POST['gender'] ?? '');
    $grade     = trim($_POST['grade'] ?? '');

    $old = $_POST;

    // basic required checks
    if ($parentName === '' || $email === '' || $password === '' || $childName === '' || $dobRaw === '' || $grade === '') {
        $message = "<p style='color:red;'>Please fill all required fields.</p>";
    } else {
        // normalize dob to YYYY-MM-DD if possible
        try {
            $dobDt = new DateTime($dobRaw);
            $dob = $dobDt->format('Y-m-d');
        } catch (Exception $e) {
            $dob = '';
        }

        if ($dob === '') {
            $message = "<p style='color:red;'>Invalid date of birth.</p>";
        } else {
            // calculate age in months
            $birthDate = new DateTime($dob);
            $today = new DateTime();
            $diff = $today->diff($birthDate);
            $ageMonths = ($diff->y * 12) + $diff->m;

            // validate grade vs age
            $validGrade = false;
            if ($grade === "Infants" && $ageMonths >= 6 && $ageMonths <= 12) $validGrade = true;
            if ($grade === "Toddlers" && $ageMonths >= 12 && $ageMonths <= 36) $validGrade = true;
            if ($grade === "Playgroup" && $ageMonths >= 36 && $ageMonths <= 48) $validGrade = true;
            if ($grade === "Preschool" && $ageMonths >= 48 && $ageMonths <= 60) $validGrade = true;

            if (!$validGrade) {
                $message = "<p style='color:red;'>Selected category does NOT match child age (age = {$ageMonths} months).</p>";
            } else {
                // -------------- handle uploads --------------
                $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
                $maxSize = 2 * 1024 * 1024; // 2MB

                // birth certificate
                $birthFilename = '';
                if (isset($_FILES['birthCert']) && $_FILES['birthCert']['error'] === 0) {
                    $birthFile = $_FILES['birthCert'];
                    $birthExt = strtolower(pathinfo($birthFile['name'], PATHINFO_EXTENSION));
                    if (!in_array($birthExt, $allowed) || $birthFile['size'] > $maxSize) {
                        $message = "<p style='color:red;'>Invalid Birth Certificate file (allowed: pdf/jpg/png, max 2MB)</p>";
                    } else {
                        $birthFilename = uniqid('birth_') . '.' . $birthExt;
                        move_uploaded_file($birthFile['tmp_name'], $uploads_fs_dir . $birthFilename);
                    }
                } else {
                    // required in your original form — if missing, show error
                    $message = $message ?: "<p style='color:red;'>Birth Certificate upload failed or missing.</p>";
                }

                // parent ID
                $idFilename = '';
                if (isset($_FILES['parentID']) && $_FILES['parentID']['error'] === 0) {
                    $idFile = $_FILES['parentID'];
                    $idExt = strtolower(pathinfo($idFile['name'], PATHINFO_EXTENSION));
                    if (!in_array($idExt, $allowed) || $idFile['size'] > $maxSize) {
                        $message = "<p style='color:red;'>Invalid Parent ID file (allowed: pdf/jpg/png, max 2MB)</p>";
                    } else {
                        $idFilename = uniqid('id_') . '.' . $idExt;
                        move_uploaded_file($idFile['tmp_name'], $uploads_fs_dir . $idFilename);
                    }
                } else {
                    $message = $message ?: "<p style='color:red;'>Parent ID upload failed or missing.</p>";
                }

                // If uploads ok (no message set)
                if ($message === '') {
                    // ---------------- save to JSON ----------------
                    $apps = get_apps($users_file);

                    // check if parent email already exists (in parent.email)
                    $emailExists = false;
                    foreach ($apps as $item) {
                        if (isset($item['parent']['email']) && strtolower($item['parent']['email']) === strtolower($email)) {
                            $emailExists = true;
                            break;
                        }
                    }

                    if ($emailExists) {
                        $message = "<p style='color:red;'>Email already registered.</p>";
                    } else {
                        // structure: keep parent object and children array (future-proof)
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
                            "children" => [
                                [
                                    "childId" => uniqid("child-"),
                                    "name" => $childName,
                                    "dob" => $dob,
                                    "gender" => $gender,
                                    "grade" => $grade,
                                    "ageMonths" => $ageMonths,
                                    "address" => $parentAddress,
                                    "status" => "Pending",
                                    "documents" => [
                                        "birthCertificate" => $birthFilename
                                    ]
                                ]
                            ],
                            "documents" => [
                                "parentID" => $idFilename
                            ]
                        ];

                        $apps[] = $newApp;
                        save_apps($users_file, $apps);

                        $message = "<p style='color:green;'>Application Submitted Successfully! Your Application is now Pending.</p>";

                        // clear old values
                        $old = [];
                    }
                }
            }
        }
    }
}

// for navbar condition (if you use it)
$is_logged_in = isset($_SESSION['email']) || isset($_SESSION['user_email']);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admission Form</title>
    <!-- front-controller friendly path -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="page-container">
    <h1>School Admission Form</h1>

    <?php echo $message; ?>

    <p>Already have an account? <a href="index.php?page=login">Login here</a></p>

    <form method="POST" enctype="multipart/form-data">
        <!-- ==================== CHILD SECTION ==================== -->
        <h2>SECTION 1: CHILD DETAILS</h2>

        <label>Child Full Name:</label>
        <input type="text" name="childName" required value="<?php echo htmlspecialchars($old['childName'] ?? ''); ?>"><br><br>

        <label>Date of Birth:</label>
        <input type="date" id="dob" name="dob" required value="<?php echo htmlspecialchars($old['dob'] ?? ''); ?>"><br><br>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male" <?php if (($old['gender'] ?? '') === 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if (($old['gender'] ?? '') === 'Female') echo 'selected'; ?>>Female</option>
        </select><br><br>

        <label>Grade Applying For:</label>
        <select id="grade" name="grade" required>
            <option value="">Select Category</option>
            <option value="Infants" <?php if (($old['grade'] ?? '') === 'Infants') echo 'selected'; ?>>Infants (6–12 months)</option>
            <option value="Toddlers" <?php if (($old['grade'] ?? '') === 'Toddlers') echo 'selected'; ?>>Toddlers (1–3 years)</option>
            <option value="Playgroup" <?php if (($old['grade'] ?? '') === 'Playgroup') echo 'selected'; ?>>Playgroup (3–4 years)</option>
            <option value="Preschool" <?php if (($old['grade'] ?? '') === 'Preschool') echo 'selected'; ?>>Pre-School (4–5 years)</option>
        </select><br><br>

        <p id="gradeError" style="color:red;"></p>

        <!-- ==================== PARENT SECTION ==================== -->
        <h2>SECTION 2: PARENT / GUARDIAN DETAILS</h2>

        <label>Parent Full Name:</label>
        <input type="text" name="parentName" required value="<?php echo htmlspecialchars($old['parentName'] ?? ''); ?>"><br><br>

        <label>Relationship:</label>
        <input type="text" name="relationship" required value="<?php echo htmlspecialchars($old['relationship'] ?? ''); ?>"><br><br>

        <label>Email Address (Username):</label>
        <input type="email" name="email" required value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"><br><br>

        <label>Create Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Phone Number:</label>
        <input type="text" name="phone" required value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>"><br><br>

        <label>Residential Address:</label>
        <input type="text" name="parentAddress" required value="<?php echo htmlspecialchars($old['parentAddress'] ?? ''); ?>"><br><br>

        <!-- ==================== UPLOAD SECTION ==================== -->
        <h2>SECTION 3: UPLOAD DOCUMENTS</h2>

        <label>Birth Certificate (pdf/jpg/png, max 2MB):</label>
        <input type="file" name="birthCert" required><br><br>

        <label>Parent ID (pdf/jpg/png, max 2MB):</label>
        <input type="file" name="parentID" required><br><br>

        <button type="submit">SUBMIT APPLICATION</button>
    </form>
</div>

<footer>
    <p>&copy; 2026 Humulani Pre School</p>
</footer>

<script>
document.getElementById('dob').addEventListener('change', function() {
    const dob = new Date(this.value);
    const today = new Date();
    if (!dob || isNaN(dob.getTime())) return;

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
