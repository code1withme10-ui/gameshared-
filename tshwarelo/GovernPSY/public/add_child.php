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
    
    $applications = [];
    if (file_exists($appsFile)) {
        $applications = json_decode(file_get_contents($appsFile), true);
        if (!is_array($applications)) $applications = [];
    }

    $applicationId = "GP-" . time() . rand(10, 99);

    $newChild = [
        'application_id'  => $applicationId,
        'email'           => $_SESSION['parent_email'], 
        'parent_name'     => $_SESSION['user_name'],
        'child_name'      => $_POST['child_name'],
        'gender'          => $_POST['gender'], 
        'dob'             => $_POST['dob'],
        'grade'           => $_POST['grade'],
        'medical_info'    => $_POST['medical_info'],
        'status'          => 'pending',
        'submission_date' => date("Y-m-d H:i:s"),
        'documents'       => []
    ];

    if (isset($_FILES['doc_birth']) && $_FILES['doc_birth']['error'] == 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = pathinfo($_FILES['doc_birth']['name'], PATHINFO_EXTENSION);
        $fileName = $applicationId . "_birth." . $ext;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['doc_birth']['tmp_name'], $targetPath)) {
            $newChild['documents']['doc_birth'] = $targetPath;
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
    <title>Add Another Child | Govern Psy</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <main class="container">
        <div style="margin-top: 20px; margin-bottom: 20px;">
            <a href="parent_dashboard.php" style="color: #003366; text-decoration: none; font-weight: bold;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <header class="page-banner">
            <h1>Register Another Child</h1>
            <p>Parent Account: <strong><?php echo htmlspecialchars($_SESSION['parent_email']); ?></strong></p>
        </header>
        
        <form action="add_child.php" method="POST" enctype="multipart/form-data" style="margin-top: 20px; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <fieldset style="border: none; padding: 0;">
                <legend style="font-size: 1.2rem; font-weight: bold; color: #003366; margin-bottom: 20px;">
                    <i class="fas fa-child"></i> Child Details
                </legend>
                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Child Full Name *</label>
                        <input type="text" name="child_name" required style="width: 100%; padding: 8px;">
                    </div>
                    
                    <div class="form-group">
                        <label>Gender *</label>
                        <select name="gender" required style="width: 100%; padding: 8px;">
                            <option value="">-- Select --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date of Birth *</label>
                        <input type="date" name="dob" id="childDob" onchange="calculateGrade()" required style="width: 100%; padding: 8px;">
                        <small id="ageText" style="display: block; margin-top: 5px; font-weight: bold;"></small>
                    </div>

                    <div class="form-group">
                        <label>Grade Applying For *</label>
                        <select name="grade" id="childGrade" required disabled style="width: 100%; padding: 8px; background-color: #f0f0f0;">
                            <option value="">-- Select DOB First --</option>
                            <option value="Toddlers">Toddlers (2-3 years)</option>
                            <option value="Playgroup">Playgroup (3-4 years)</option>
                            <option value="Pre-School">Pre-School (4-5 years)</option>
                            <option value="Foundation">Foundation Phase (6+ years)</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Medical Allergies / Info *</label>
                        <textarea name="medical_info" required placeholder="List allergies or type 'None'" style="width: 100%; padding: 10px; height: 80px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
                    </div>
                </div>
            </fieldset>

            <fieldset style="border: none; padding: 0; margin-top: 25px;">
                <legend style="font-size: 1.2rem; font-weight: bold; color: #003366; margin-bottom: 15px;">
                    <i class="fas fa-file-upload"></i> Required Documents
                </legend>
                <div class="form-group">
                    <label>Child's Birth Certificate (PDF/JPG/PNG) *</label>
                    <input type="file" name="doc_birth" accept=".pdf,.jpg,.png" required>
                </div>
            </fieldset>

            <div style="margin-top: 35px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary" style="flex: 2; padding: 12px; cursor: pointer; background: #003366; color: white; border: none; border-radius: 5px; font-weight: bold;">Submit New Application</button>
                <a href="parent_dashboard.php" class="btn btn-secondary" style="flex: 1; text-align: center; border: 1px solid #ccc; padding: 12px; text-decoration: none; color: #333; border-radius: 5px;">Cancel</a>
            </div>
        </form>
    </main>

    <script>
    function calculateGrade() {
        const dobInput = document.getElementById('childDob').value;
        const gradeSelect = document.getElementById('childGrade');
        const ageText = document.getElementById('ageText');

        if (!dobInput) {
            gradeSelect.disabled = true;
            gradeSelect.value = "";
            gradeSelect.style.backgroundColor = "#f0f0f0";
            return;
        }

        const dob = new Date(dobInput);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        // Logic for children under 2
        if (age < 2) {
            ageText.style.color = "#dc3545"; 
            ageText.innerText = "Child is " + age + " years old. Minimum age is 2 years.";
            gradeSelect.disabled = true;
            gradeSelect.value = "";
            gradeSelect.style.backgroundColor = "#f0f0f0";
            return;
        }

        // Logic for valid age
        ageText.style.color = "#28a745"; 
        ageText.innerText = "Calculated Age: " + age + " years";
        gradeSelect.disabled = false;
        gradeSelect.style.backgroundColor = "#ffffff";

        if (age >= 2 && age <= 3) {
            gradeSelect.value = "Toddlers";
        } else if (age == 4) {
            gradeSelect.value = "Playgroup";
        } else if (age == 5) {
            gradeSelect.value = "Pre-School";
        } else {
            gradeSelect.value = "Foundation";
        }
    }
    </script>
</body>
</html>