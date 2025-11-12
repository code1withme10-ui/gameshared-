<?php
session_start();

$admissionFile = __DIR__ . '/admissions.json';
$admissions = file_exists($admissionFile)
    ? json_decode(file_get_contents($admissionFile), true)
    : [];

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $childFirstName = trim($_POST["childFirstName"]);
    $childSurname = trim($_POST["childSurname"]);
    $dob = trim($_POST["dob"]);
    $gender = trim($_POST["gender"]);
    $age = trim($_POST["age"]);
    $parentName = trim($_POST["parentName"]);
    $parentContact = trim($_POST["parentContact"]);
    $parentEmail = trim($_POST["parentEmail"]);
    $address = trim($_POST["address"]);
    $medicalInfo = trim($_POST["medicalInfo"]);
    $emergencyContact = trim($_POST["emergencyContact"]);

    if ($childFirstName && $childSurname && $dob && $gender && $age && $parentName && $parentContact) {
        $admissions[] = [
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
            "timestamp" => date("Y-m-d H:i:s")
        ];

        if (file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT))) {
            $success = "✅ Admission form submitted successfully!";
        } else {
            $error = "⚠ Unable to save admission data. Please try again.";
        }
    } else {
        $error = "⚠ Please fill in all required fields marked with *.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admission - SubixStar Pre-School</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        main.form-container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 40px 35px;
        }

        h2 {
            text-align: center;
            color: #222;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #444;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            box-sizing: border-box;
        }

        button {
            display: block;
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background: linear-gradient(90deg, #4a90e2, #6dd5ed);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background: linear-gradient(90deg, #357abd, #50a7c2);
        }

        .success, .error {
            text-align: center;
            font-weight: bold;
            padding: 10px;
            border-radius: 6px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #777;
        }

        .section-title {
            margin-top: 30px;
            font-size: 17px;
            color: #2c3e50;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>

<?php require_once 'menu-bar.php'; ?>

<main class="form-container">
    <h2>Online Admission Form</h2>
    <p>Please fill out the required information to apply for admission at SubixStar Pre-School.</p>

    <?php if (!empty($success)): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">

        <div class="section-title">Child’s Information</div>
        <label>First Name *</label>
        <input type="text" name="childFirstName" required>

        <label>Surname *</label>
        <input type="text" name="childSurname" required>

        <label>Date of Birth *</label>
        <input type="date" name="dob" required>

        <label>Gender *</label>
        <select name="gender" required>
            <option value="">Select gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label>Age *</label>
        <input type="number" name="age" required>

        <div class="section-title">Parent/Guardian Information</div>
        <label>Full Name *</label>
        <input type="text" name="parentName" required>

        <label>Contact Number *</label>
        <input type="tel" name="parentContact" required>

        <label>Email Address</label>
        <input type="email" name="parentEmail">

        <label>Home Address *</label>
        <textarea name="address" rows="3" required></textarea>

        <div class="section-title">Additional Information</div>
        <label>Medical Information (Allergies, Conditions, etc.)</label>
        <textarea name="medicalInfo" rows="3"></textarea>

        <label>Emergency Contact (Name & Number)</label>
        <textarea name="emergencyContact" rows="2"></textarea>

        <button type="submit">Submit Admission</button>
    </form>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
