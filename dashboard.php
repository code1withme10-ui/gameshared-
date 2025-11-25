<?php
session_start();
$data_file = 'users.json';

// ---------------- Functions ----------------
function get_users($file) {
    if (!file_exists($file) || filesize($file) == 0) return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function save_users($file, $users) {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

function find_current_user_data($users, $email) {
    foreach ($users as $index => $user) {
        if ($user['email'] === $email) {
            return ['user' => $user, 'index' => $index];
        }
    }
    return null;
}

// ---------------- Security Check ----------------
if (!isset($_SESSION['user_email']) || (isset($_SESSION['role']) && $_SESSION['role'] === 'headmaster')) {
    header('Location: login.php');
    exit;
}

$users = get_users($data_file);
$current_user_data = find_current_user_data($users, $_SESSION['user_email']);

if (!$current_user_data) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$current_user_index = $current_user_data['index'];
$current_user = $current_user_data['user'];

$message = '';

// ---------------- Add Another Child ----------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addChild'])) {
    $newChildName = $_POST['newChildName'] ?? '';
    $newDOB = $_POST['newDOB'] ?? '';
    $newGender = $_POST['newGender'] ?? '';
    $newGrade = $_POST['newGrade'] ?? '';
    $newAllergies = $_POST['newAllergies'] ?? '';
    $newMedications = $_POST['newMedications'] ?? '';

    $dobDate = new DateTime($newDOB);
    $today = new DateTime();
    $interval = $today->diff($dobDate);
    $newAgeYears = $interval->y;

    $grade_age_map = [
        'Infants'     => [0,1],   // 0-1 year
        'Toddlers'    => [1,3],
        'Playgroup'   => [3,4],
        'Pre-School'  => [4,5]
    ];

    $valid = true;
    if ($dobDate > $today) {
        $message = '<p style="color:red;">Date of birth cannot be in the future.</p>';
        $valid = false;
    } elseif (!isset($grade_age_map[$newGrade]) || $newAgeYears < $grade_age_map[$newGrade][0] || $newAgeYears > $grade_age_map[$newGrade][1]) {
        $message = '<p style="color:red;">Selected grade does not match child\'s age.</p>';
        $valid = false;
    }

    // ---------------- Handle file uploads ----------------
    $documents = [];
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    $max_size = 2 * 1024 * 1024; // 2MB
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    if ($valid) {
        // Child Birth Certificate
        if (isset($_FILES['newBirthCert']) && $_FILES['newBirthCert']['error'] == 0) {
            if (!in_array($_FILES['newBirthCert']['type'], $allowed_types)) {
                $message = '<p style="color:red;">Birth Certificate must be JPEG, PNG, or PDF.</p>';
                $valid = false;
            } elseif ($_FILES['newBirthCert']['size'] > $max_size) {
                $message = '<p style="color:red;">Birth Certificate must be less than 2MB.</p>';
                $valid = false;
            } else {
                $birthPath = $uploadDir . basename($_FILES['newBirthCert']['name']);
                move_uploaded_file($_FILES['newBirthCert']['tmp_name'], $birthPath);
                $documents['birthCert'] = $birthPath;
            }
        } else {
            $message = '<p style="color:red;">Birth Certificate is required.</p>';
            $valid = false;
        }

        // Parent ID
        if (isset($_FILES['newParentID']) && $_FILES['newParentID']['error'] == 0) {
            if (!in_array($_FILES['newParentID']['type'], $allowed_types)) {
                $message = '<p style="color:red;">Parent ID must be JPEG, PNG, or PDF.</p>';
                $valid = false;
            } elseif ($_FILES['newParentID']['size'] > $max_size) {
                $message = '<p style="color:red;">Parent ID must be less than 2MB.</p>';
                $valid = false;
            } else {
                $parentIDPath = $uploadDir . basename($_FILES['newParentID']['name']);
                move_uploaded_file($_FILES['newParentID']['tmp_name'], $parentIDPath);
                $documents['parentID'] = $parentIDPath;
            }
        } else {
            $message = '<p style="color:red;">Parent ID is required.</p>';
            $valid = false;
        }
    }

    if ($valid && $current_user) {
        $new_child = [
            'childId' => uniqid('child-'),
            'name' => $newChildName,
            'dob' => $newDOB,
            'age' => $newAgeYears,
            'gender' => $newGender,
            'grade' => $newGrade,
            'allergies' => $newAllergies,
            'medications' => $newMedications,
            'documents' => $documents,
            'reports' => [],
            'status' => 'pending'
        ];

        $users[$current_user_index]['children'][] = $new_child;
        save_users($data_file, $users);
        $message = '<p style="color:green;">' . htmlspecialchars($newChildName) . ' has been successfully added! Admission status is Pending.</p>';
        $current_user = $users[$current_user_index];
    }
}

// ---------------- Logout ----------------
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

$is_logged_in = isset($_SESSION['user_email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Parent Dashboard</title>
<link rel="stylesheet" href="style.css">
<script>
// JavaScript for dynamic grade selection based on DOB
function updateGradeOptions() {
    const dobInput = document.getElementById('newDOB').value;
    const gradeSelect = document.getElementById('newGrade');
    const options = gradeSelect.options;

    // Reset options
    for (let i=0; i<options.length; i++){
        options[i].disabled = false;
    }

    if (!dobInput) return;

    const dob = new Date(dobInput);
    const today = new Date();

    let ageYears = today.getFullYear() - dob.getFullYear();
    let ageMonths = today.getMonth() - dob.getMonth();
    if (today.getDate() < dob.getDate()) ageMonths--;
    if (ageMonths < 0) {
        ageYears--;
        ageMonths += 12;
    }

    const grade_age_map = {
        'Infants': [0,1],
        'Toddlers': [1,3],
        'Playgroup': [3,4],
        'Pre-School': [4,5]
    };

    for (let i=1; i<options.length; i++) {
        const grade = options[i].value;
        if (grade_age_map[grade]) {
            const min = grade_age_map[grade][0];
            const max = grade_age_map[grade][1];
            if (ageYears < min || ageYears > max) {
                options[i].disabled = true;
            }
        }
    }

    gradeSelect.value = '';
}
</script>
</head>
<body>

<div class="navbar">
    <span class="navbar-title">Humulani Pre School</span>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="dashboard.php?action=logout">Logout</a></li>
        </ul>
    </nav>
</div>

<div class="page-container">
<h1>Welcome, <?php echo htmlspecialchars($_SESSION['parent_name'] ?? 'Parent'); ?>!</h1>
<hr>
<?php echo $message; ?>

<h2>Your Children</h2>
<div id="childrenList">
<?php if ($current_user && !empty($current_user['children'])): ?>
    <?php foreach ($current_user['children'] as $child): ?>
        <div>
            <h3>Child: <?php echo htmlspecialchars($child['name']); ?></h3>
            <p>Date of Birth: <?php echo htmlspecialchars($child['dob']); ?></p>
            <p>Age: <?php echo htmlspecialchars($child['age']); ?></p>
            <p>Gender: <?php echo htmlspecialchars($child['gender']); ?></p>
            <p>Grade: <?php echo htmlspecialchars($child['grade']); ?></p>
            <p>Allergies: <?php echo htmlspecialchars($child['allergies'] ?: 'None'); ?></p>
            <p>Medications: <?php echo htmlspecialchars($child['medications'] ?: 'None'); ?></p>
            <p><strong>Admission Status:</strong> 
                <span class="status-<?php echo strtolower($child['status'] ?? 'pending'); ?>">
                    <?php echo htmlspecialchars(ucfirst($child['status'] ?? 'Pending')); ?>
                </span>
            </p>
            <p><strong>Documents:</strong>
                <?php if (!empty($child['documents'])): ?>
                    <ul>
                        <?php foreach ($child['documents'] as $label => $path): ?>
                            <li><a href="<?php echo htmlspecialchars($path); ?>" target="_blank"><?php echo ucfirst($label); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    None
                <?php endif; ?>
            </p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>You have not registered any children yet. Use the form below to add your first child.</p>
<?php endif; ?>
</div>

<hr>

<h2>Add Another Child</h2>
<form method="POST" action="dashboard.php" enctype="multipart/form-data">
    <input type="hidden" name="addChild" value="1">

    <label for="newChildName">Child's Name:</label>
    <input type="text" id="newChildName" name="newChildName" required><br><br>

    <label for="newDOB">Date of Birth:</label>
    <input type="date" id="newDOB" name="newDOB" required onchange="updateGradeOptions()"><br><br>

    <label for="newGender">Gender:</label>
    <select id="newGender" name="newGender" required>
        <option value="">Select Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select><br><br>

    <label for="newGrade">Grade:</label>
    <select id="newGrade" name="newGrade" required>
        <option value="">Select Grade</option>
        <option value="Infants">Infants (0–1 year)</option>
        <option value="Toddlers">Toddlers (1–3 years)</option>
        <option value="Playgroup">Playgroup (3–4 years)</option>
        <option value="Pre-School">Pre-School (4–5 years)</option>
    </select><br><br>

    <label for="newAllergies">Allergies (if any):</label>
    <textarea id="newAllergies" name="newAllergies" rows="3" placeholder="e.g., peanuts, pollen"></textarea><br><br>

    <label for="newMedications">Medications (if any):</label>
    <textarea id="newMedications" name="newMedications" rows="3" placeholder="e.g., inhaler, antibiotics"></textarea><br><br>

    <!-- New file uploads -->
    <label for="newBirthCert">Child Birth Certificate:</label>
    <input type="file" id="newBirthCert" name="newBirthCert" accept=".jpeg,.jpg,.png,.pdf" required><br><br>

    <label for="newParentID">Parent/Guardian ID:</label>
    <input type="file" id="newParentID" name="newParentID" accept=".jpeg,.jpg,.png,.pdf" required><br><br>

    <button type="submit">Add Child</button>
</form>

</div>

<footer>
<p>&copy; 2026 Humulani Pre School</p>
</footer>

</body>
</html>
