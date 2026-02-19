<?php
require_once __DIR__ . '/../../middleware/auth.php';
requireRole('parent');

require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/navbar.php';
require_once __DIR__ . '/../../services/JsonStorage.php';

$parentId = $_SESSION['user']['id'];

$childrenStorage = new JsonStorage(__DIR__ . '/../../../storage/children.json');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName = trim($_POST['full_name'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $grade = $_POST['grade'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $allergies = trim($_POST['allergies'] ?? '');
    $previousSchool = trim($_POST['previous_school'] ?? '');

    // Basic validation
    if ($fullName === '') $errors[] = 'Child full name is required.';
    if ($dob === '') $errors[] = 'Date of birth is required.';
    if ($gender === '') $errors[] = 'Gender is required.';
    if ($grade === '') $errors[] = 'Grade selection is required.';
    if ($address === '') $errors[] = 'Residential address is required.';

    // DOB validation
    try {
        $dobDate = new DateTime($dob);
        $today = new DateTime();
        if ($dobDate > $today) {
            $errors[] = 'Date of birth cannot be in the future.';
        }
    } catch (Exception $e) {
        $errors[] = 'Invalid date of birth.';
    }

    // Age vs Grade validation (in months)
    if (empty($errors)) {
        $diff = $today->diff($dobDate);
        $ageMonths = ($diff->y * 12) + $diff->m;

        $gradeValid = match ($grade) {
            'Infants'     => ($ageMonths >= 0  && $ageMonths <= 12),
            'Toddlers'    => ($ageMonths > 12  && $ageMonths <= 36),
            'Playgroup'   => ($ageMonths > 36  && $ageMonths <= 48),
            'Pre-School'  => ($ageMonths > 48  && $ageMonths <= 60),
            default       => false,
        };

        if (!$gradeValid) {
            $errors[] = "Selected category does not match child's age.";
        }
    }

    // File uploads
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    $maxSize = 2 * 1024 * 1024;

    $uploadFields = [
        'birth_certificate' => 'Birth Certificate',
        'parent_id' => 'Parent ID',
        'clinical_report' => 'Clinical Report'
    ];

    $uploadedFiles = [];

    foreach ($uploadFields as $key => $label) {
        if (!isset($_FILES[$key]) || $_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "$label is required.";
            continue;
        }

        $file = $_FILES[$key];

        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = "$label must be PDF, JPG, or PNG.";
            continue;
        }

        if ($file['size'] > $maxSize) {
            $errors[] = "$label must be less than 2MB.";
            continue;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeName = uniqid($key . '_') . '.' . $extension;
        $targetPath = __DIR__ . '/../../../public/uploads/' . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            $errors[] = "Failed to upload $label.";
            continue;
        }

        $uploadedFiles[$key] = '/uploads/' . $safeName;
    }

    // Save to JSON
    if (empty($errors)) {
        $children = $childrenStorage->read();

        $children[] = [
            'id' => uniqid('child_'),
            'parent_id' => $parentId,
            'full_name' => $fullName,
            'dob' => $dob,
            'gender' => $gender,
            'grade' => $grade,
            'address' => $address,
            'allergies' => $allergies,
            'previous_school' => $previousSchool,
            'documents' => $uploadedFiles,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $childrenStorage->write($children);

        header('Location: /app/views/parent/dashboard.php');
        exit;
    }
}
?>

<div class="container" style="max-width:600px; margin:50px auto;">
    <h2>Admit Child</h2>

    <?php foreach ($errors as $error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="full_name" placeholder="Child Full Name" required>
        <input type="date" name="dob" required>

        <select name="gender" required>
            <option value="">Select Gender</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
        </select>

        <select name="grade" required>
            <option value="">Select Category</option>
            <option value="Infants">Infants (0–12 months)</option>
            <option value="Toddlers">Toddlers (1–3 years)</option>
            <option value="Playgroup">Playgroup (3–4 years)</option>
            <option value="Pre-School">Pre-School (4–5 years)</option>
        </select>

        <input type="text" name="address" placeholder="Residential Address" required>
        <input type="text" name="allergies" placeholder="Medical Allergies">
        <input type="text" name="previous_school" placeholder="Previous School (Optional)">

        <label>Birth Certificate</label>
        <input type="file" name="birth_certificate" required>

        <label>Parent ID</label>
        <input type="file" name="parent_id" required>

        <label>Clinical Report</label>
        <input type="file" name="clinical_report" required>

        <button type="submit">Submit Admission</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
