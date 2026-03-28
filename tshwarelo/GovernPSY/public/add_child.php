<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'parent') {
    header("Location: login.php");
    exit();
}

// 2. The Logic to Save the New Child
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appsFile = 'data/applications.json';
    $uploadDir = 'uploads/';
    
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $applications = [];
    if (file_exists($appsFile)) {
        $applications = json_decode(file_get_contents($appsFile), true);
        if (!is_array($applications)) $applications = [];
    }

    $applicationId = "GP-" . time() . rand(10, 99);

    $newChild = [
        'application_id'  => $applicationId,
        'email'           => $_SESSION['parent_email'] ?? $_SESSION['email'], 
        'parent_name'     => $_SESSION['user_name'],
        // FIX: Added the parent phone number to the application record
        'parent_phone'    => $_SESSION['parent_phone'] ?? ($_SESSION['user_phone'] ?? 'N/A'),
        'child_name'      => $_POST['child_name'],
        'gender'          => $_POST['gender'], 
        'dob'             => $_POST['dob'],
        'grade'           => $_POST['grade'],
        'medical_info'    => $_POST['medical_info'],
        'status'          => 'pending',
        'submission_date' => date("Y-m-d H:i:s"),
        'documents'       => [
            'doc_birth' => null,
            'doc_parent_id' => null
        ]
    ];

    // Handle Child Birth Certificate (Optional)
    if (isset($_FILES['doc_birth']) && $_FILES['doc_birth']['error'] == 0) {
        $ext = pathinfo($_FILES['doc_birth']['name'], PATHINFO_EXTENSION);
        $path = $uploadDir . $applicationId . "_birth." . $ext;
        if (move_uploaded_file($_FILES['doc_birth']['tmp_name'], $path)) {
            $newChild['documents']['doc_birth'] = $path;
        }
    }

    // Handle Parent ID (Optional)
    if (isset($_FILES['doc_parent_id']) && $_FILES['doc_parent_id']['error'] == 0) {
        $ext = pathinfo($_FILES['doc_parent_id']['name'], PATHINFO_EXTENSION);
        $path = $uploadDir . $applicationId . "_parent_id." . $ext;
        if (move_uploaded_file($_FILES['doc_parent_id']['tmp_name'], $path)) {
            $newChild['documents']['doc_parent_id'] = $path;
        }
    }

    $applications[] = $newChild;
    file_put_contents($appsFile, json_encode($applications, JSON_PRETTY_PRINT));

    header("Location: parent_dashboard.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Child | Govern Psy</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root { --navy: #003366; --red: #C41E3A; --gold: #FFD700; }
        body { font-family: 'Montserrat', sans-serif; background: #f4f7f6; margin: 0; color: #333; }
        .page-banner { background: var(--navy); color: white; padding: 40px 20px; text-align: center; border-bottom: 5px solid var(--red); }
        .container { max-width: 850px; margin: 40px auto; padding: 0 20px; }
        .form-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-section-title { font-size: 1.2rem; font-weight: 800; color: var(--navy); margin-bottom: 25px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 8px; color: #555; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-family: inherit; }
        .btn-submit { background: var(--navy); color: white; border: none; padding: 15px 30px; border-radius: 8px; font-weight: 700; width: 100%; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background: var(--red); transform: translateY(-2px); }
        .doc-hint { font-size: 0.75rem; color: #888; margin-top: 5px; display: block; }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <header class="page-banner">
        <h1 style="margin:0; font-weight:900; text-transform: uppercase;">Child Registration</h1>
        <p style="opacity:0.8;">Applying for academic year <?php echo date("Y"); ?></p>
    </header>

    <main class="container">
        <div style="margin-bottom: 20px;">
            <a href="parent_dashboard.php" style="color: var(--navy); text-decoration: none; font-weight: 700;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="form-card">
            <form action="add_child.php" method="POST" enctype="multipart/form-data">
                
                <div class="form-section-title"><i class="fas fa-child"></i> Child Details</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Child's Full Name *</label>
                        <input type="text" name="child_name" class="form-control" required placeholder="As it appears on Birth Certificate">
                    </div>
                    <div class="form-group">
                        <label>Gender *</label>
                        <select name="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth *</label>
                        <input type="date" name="dob" id="childDob" class="form-control" required onchange="calculateGrade()">
                        <small id="ageText" style="display:block; margin-top:5px; font-weight:bold;"></small>
                    </div>
                </div>

                <div class="form-group">
                    <label>Grade Applying For *</label>
                    <select name="grade" id="childGrade" class="form-control" required>
                        <option value="">Select DOB First</option>
                        <option value="Toddlers">Toddlers (2-3 years)</option>
                        <option value="Playgroup">Playgroup (3-4 years)</option>
                        <option value="Pre-School">Pre-School (4-5 years)</option>
                        <option value="Foundation">Foundation Phase (6+ years)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Medical Allergies / Critical Info *</label>
                    <textarea name="medical_info" class="form-control" style="height:80px;" required placeholder="E.g. Peanuts, Asthma, or 'None'"></textarea>
                </div>

                <div class="form-section-title" style="margin-top:40px;"><i class="fas fa-file-upload"></i> Support Documents (Optional)</div>
                <p style="font-size: 0.85rem; color: #666; margin-bottom: 20px;">You can skip this and upload later from your dashboard if needed.</p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Child's Birth Certificate</label>
                        <input type="file" name="doc_birth" class="form-control" accept=".pdf,.jpg,.png">
                        <span class="doc-hint">PDF, JPG, or PNG</span>
                    </div>
                    <div class="form-group">
                        <label>Parent/Guardian ID</label>
                        <input type="file" name="doc_parent_id" class="form-control" accept=".pdf,.jpg,.png">
                        <span class="doc-hint">Proof of Identity</span>
                    </div>
                </div>

                <div style="margin-top: 40px;">
                    <button type="submit" class="btn-submit">SUBMIT APPLICATION</button>
                </div>
            </form>
        </div>
    </main>

    <footer style="text-align: center; margin-top: 50px; padding: 30px; color: #888; font-size: 0.8rem;">
        &copy; <?php echo date("Y"); ?> GOVERN PSY EDUCATIONAL CENTER. All Rights Reserved.
    </footer>

    <script>
    function calculateGrade() {
        const dobInput = document.getElementById('childDob').value;
        const gradeSelect = document.getElementById('childGrade');
        const ageText = document.getElementById('ageText');

        if (!dobInput) return;

        const dob = new Date(dobInput);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) { age--; }

        if (age < 2) {
            ageText.style.color = "#C41E3A";
            ageText.innerText = "Child is " + age + ". Min age is 2.";
            gradeSelect.value = "";
            return;
        }

        ageText.style.color = "#28a745";
        ageText.innerText = "Age detected: " + age + " years";

        if (age >= 2 && age <= 3) gradeSelect.value = "Toddlers";
        else if (age == 4) gradeSelect.value = "Playgroup";
        else if (age == 5) gradeSelect.value = "Pre-School";
        else gradeSelect.value = "Foundation";
    }
    </script>
</body>
</html>