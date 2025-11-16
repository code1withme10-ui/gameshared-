<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$admissionFile = __DIR__ . '/admissions.json';
$admissions = file_exists($admissionFile)
    ? json_decode(file_get_contents($admissionFile), true)
    : [];

$success = "";
$error = "";

$loggedInParent  = $_SESSION['user']['parentName'] ?? null;
$loggedInEmail   = $_SESSION['user']['email'] ?? null;
$loggedInContact = $_SESSION['user']['phone'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $children = $_POST['children'] ?? [];

    foreach ($children as $child) {
        $childFirstName = trim($child["childFirstName"] ?? "");
        $childSurname = trim($child["childSurname"] ?? "");
        $dob = trim($child["dob"] ?? "");
        $gender = trim($child["gender"] ?? "");
        $age = trim($child["age"] ?? "");
        $parentName = $loggedInParent ?: trim($_POST["parentName"]);
        $parentContact = $loggedInContact ?: trim($_POST["parentContact"]);
        $parentEmail = $loggedInEmail ?: trim($_POST["parentEmail"]);
        $address = trim($_POST["address"] ?? "");
        $medicalInfo = trim($child["medicalInfo"] ?? "");
        $emergencyContact = trim($_POST["emergencyContact"] ?? "");

        if ($childFirstName && $childSurname && $dob && $gender && $age && $parentName && $parentContact) {
            $newAdmission = [
                "id" => uniqid("child_"),
                "childFirstName" => $childFirstName,
                "childSurname" => $childSurname,
                "dob" => $dob,
                "gender" => $gender,
                "age" => $age,
                "parentName" => $parentName,
                "parentContact" => $parentContact,
                "parentEmail" => $parentEmail,
                "address" => $address,
                "medicalInfo" => $medicalInfo,
                "emergencyContact" => $emergencyContact,
                "status" => "Pending",
                "timestamp" => date("Y-m-d H:i:s"),
                "lastNotification" => null,
                "notificationAt" => null
            ];

            $admissions[] = $newAdmission;
        }
    }

    if (file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT))) {
        $success = "✅ All admission forms were submitted successfully!";
    } else {
        $error = "⚠ Unable to save admission data. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admission Form - SubixStar Pre-School</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* inline small styles kept from your original */
        body { font-family: Arial, sans-serif; background: #f9f9f9; color: #333; margin: 0; }
        main { max-width: 900px; margin: 30px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        input, select, textarea, button { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #007bff; color: white; font-weight: bold; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        .child-block { background: #f1f1f1; padding: 15px; border-radius: 8px; margin-bottom: 10px; }
        .add-child { background: #28a745; margin-top: 10px; }
        .add-child:hover { background: #1e7e34; }
        .message { text-align: center; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <?php require_once 'menu-bar.php'; ?>
    <main>
        <h1>Admission Application</h1>

        <?php if ($success): ?>
            <div class="message" style="color:green;"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message" style="color:red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <h3>Parent Information</h3>
            <input type="text" name="parentName" placeholder="Parent Full Name *"
                value="<?= htmlspecialchars($loggedInParent ?? '') ?>" <?= $loggedInParent ? 'readonly' : '' ?> required>
            <input type="text" name="parentContact" placeholder="Parent Contact *"
                value="<?= htmlspecialchars($loggedInContact ?? '') ?>" <?= $loggedInContact ? 'readonly' : '' ?> required>
            <input type="email" name="parentEmail" placeholder="Parent Email *"
                value="<?= htmlspecialchars($loggedInEmail ?? '') ?>" <?= $loggedInEmail ? 'readonly' : '' ?> required>
            <textarea name="address" placeholder="Home Address"></textarea>
            <input type="text" name="emergencyContact" placeholder="Emergency Contact *" required>

            <h3>Children Information</h3>
            <div id="children-container">
                <div class="child-block">
                    <input type="text" name="children[0][childFirstName]" placeholder="Child First Name *" required>
                    <input type="text" name="children[0][childSurname]" placeholder="Child Surname *" required>
                    <input type="date" name="children[0][dob]" required>
                    <select name="children[0][gender]" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <input type="number" name="children[0][age]" placeholder="Age *" required>
                    <textarea name="children[0][medicalInfo]" placeholder="Medical Info"></textarea>
                </div>
            </div>

            <button type="button" class="add-child" onclick="addChild()">+ Add Another Child</button>
            <button type="submit">Submit Application</button>
        </form>
    </main>

    <script>
        let childCount = 1;
        function addChild() {
            const container = document.getElementById('children-container');
            const newChild = document.createElement('div');
            newChild.classList.add('child-block');
            newChild.innerHTML = `
                <input type="text" name="children[${childCount}][childFirstName]" placeholder="Child First Name *" required>
                <input type="text" name="children[${childCount}][childSurname]" placeholder="Child Surname *" required>
                <input type="date" name="children[${childCount}][dob]" required>
                <select name="children[${childCount}][gender]" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <input type="number" name="children[${childCount}][age]" placeholder="Age *" required>
                <textarea name="children[${childCount}][medicalInfo]" placeholder="Medical Info"></textarea>
            `;
            container.appendChild(newChild);
            childCount++;
        }
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
