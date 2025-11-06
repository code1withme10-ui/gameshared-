<?php
session_start();

$admissionFile = _DIR_ . '/admissions.json';
$admissions = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $childName = trim($_POST["childName"]);
    $childAge = trim($_POST["childAge"]);
    $parentName = trim($_POST["parentName"]);
    $parentContact = trim($_POST["parentContact"]);
    $address = trim($_POST["address"]);
    $medicalInfo = trim($_POST["medicalInfo"]);
    $emergencyContact = trim($_POST["emergencyContact"]);

    if ($childName && $childAge && $parentName && $parentContact) {
        $admissions[] = [
            "childName" => $childName,
            "childAge" => $childAge,
            "parentName" => $parentName,
            "parentContact" => $parentContact,
            "address" => $address,
            "medicalInfo" => $medicalInfo,
            "emergencyContact" => $emergencyContact,
            "timestamp" => date("Y-m-d H:i:s")
        ];
        file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT));
        $success = "✅ Admission form submitted successfully!";
    } else {
        $error = "⚠ Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admission - SubixStar Pre-School</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php require_once 'menu-bar.php'; ?>

<main class="form-container">
    <h2>Online Admission Form</h2>
    <p>Please complete this form to apply for your child's admission to SubixStar Pre-School.</p>

    <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="POST">
        <label>Child’s Full Name:</label>
        <input type="text" name="childName" required>

        <label>Child’s Age:</label>
        <input type="number" name="childAge" required>

        <label>Parent/Guardian Name:</label>
        <input type="text" name="parentName" required>

        <label>Parent Contact Number:</label>
        <input type="tel" name="parentContact" required>

        <label>Home Address:</label>
        <textarea name="address" rows="3"></textarea>

        <label>Medical Information (Allergies, Conditions, etc.):</label>
        <textarea name="medicalInfo" rows="3"></textarea>

        <label>Emergency Contact (Name & Number):</label>
        <textarea name="emergencyContact" rows="2"></textarea>

        <button type="submit">Submit Admission</button>
    </form>
</main>

<footer>
    <p>© 2025 SubixStar Pre-School</p>
</footer>
</body>
</html>