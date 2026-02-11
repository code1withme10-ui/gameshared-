<?php
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/navbar.php';
require_once __DIR__ . '/../../services/JsonStorage.php';
session_start();

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? 'parent') !== 'parent') {
    header('Location: /login.php');
    exit;
}

$parentId = $_SESSION['user']['id'];
$childrenStorage = new JsonStorage(__DIR__ . '/../../../storage/children.json');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $grade = $_POST['grade'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $allergies = trim($_POST['allergies'] ?? '');
    $previousSchool = trim($_POST['previous_school'] ?? '');

    if (!$fullName) $errors[] = "Child full name is required.";
    if (!$dob) $errors[] = "Date of birth is required.";
    if (!$gender) $errors[] = "Gender is required.";
    if (!$grade) $errors[] = "Grade selection is required.";
    if (!$address) $errors[] = "Residential address is required.";

    // Age vs Grade
    $dobDate = new DateTime($dob);
    $today = new DateTime();
    $ageMonths = ($today->diff($dobDate)->y * 12) + $today->diff($dobDate)->m;

    $gradeValid = false;
    switch ($grade) {
        case 'Infants': $gradeValid = ($ageMonths >= 0 && $ageMonths <= 12); break;
        case 'Toddlers': $gradeValid = ($ageMonths >= 12 && $ageMonths <= 36); break;
        case 'Playgroup': $gradeValid = ($ageMonths >= 36 && $ageMonths <= 48); break;
        case 'Pre-School': $gradeValid = ($ageMonths >= 48 && $ageMonths <= 60); break;
    }

    if (!$gradeValid) $errors[] = "Selected category does not match child's age.";

    // File uploads
    $allowedTypes = ['image/jpeg','image/png','application/pdf'];
    $maxSize = 2*1024*1024;
    $uploads = ['birth_certificate','parent_id','clinical_report'];
    $uploadedFiles = [];

    foreach ($uploads as $fileKey) {
        if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] != 0) {
            $errors[] = ucfirst(str_replace('_',' ',$fileKey)) . " is required.";
        } else {
            $file = $_FILES[$fileKey];
            if (!in_array($file['type'], $allowedTypes)) {
                $errors[] = ucfirst(str_replace('_',' ',$fileKey)) . " must be PDF, JPG, PNG.";
            } elseif ($file['size'] > $maxSize) {
                $errors[] = ucfirst(str_replace('_',' ',$fileKey)) . " must be less than 2MB.";
            } else {
                $targetPath = __DIR__ . '/../../../public/uploads/' . basename($file['name']);
                move_uploaded_file($file['tmp_name'], $targetPath);
                $uploadedFiles[$fileKey] = '/uploads/' . basename($file['name']);
            }
        }
    }

    if (empty($errors)) {
        $children = $childrenStorage->read();
        $newChild = [
            'id' => uniqid('child_'),
            'parent_id' => $parentId,
            'full_name' => $fullName,
            'dob' => $dob,
            'gender' => $gender,
            'grade' => $grade,
            'address' => $address,
            'allergies' => $allergies,
            'previous_school' => $previousSchool,
            'status' => 'pending',
            'documents' => $uploadedFiles
        ];
        $children[] = $newChild;
        $childrenStorage->write($children);

        // Redirect to dashboard
        header('Location: /app/views/parent/dashboard.php');
        exit;
    }
}
?>

<div class="container" style="max-width:600px; margin:50px auto;">
    <h2>Admit Child</h2>

    <?php foreach ($errors as $e): ?>
        <p style="color:red;"><?php echo htmlspecialchars($e); ?></p>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="full_name" placeholder="Child Full Name" required>
        <input type="date" name="dob" placeholder="Date of Birth" required>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <select name="grade" required>
            <option value="">Select Category</option>
            <option value="Infants">Infants (0-12 months)</option>
            <option value="Toddlers">Toddlers (1-3 years)</option>
            <option value="Playgroup">Playgroup (3-4 years)</option>
            <option value="Pre-School">Pre-School (4-5 years)</option>
        </select>
        <input type="text" name="address" placeholder="Residential Address" required>
        <input type="text" name="allergies" placeholder="Medical Allergies">
        <input type="text" name="previous_school" placeholder="Previous School (Optional)">
        <label>Birth Certificate (PDF/JPG/PNG)</label>
        <input type="file" name="birth_certificate" required>
        <label>Parent ID (PDF/JPG/PNG)</label>
        <input type="file" name="parent_id" required>
        <label>Clinical Report (PDF/JPG/PNG)</label>
        <input type="file" name="clinical_report" required>
        <button type="submit">Submit Admission</button>
    </form>
</div>

<?php
require_once __DIR__ . '/../../partials/footer.php';
?>
