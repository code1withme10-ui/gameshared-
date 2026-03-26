<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appsFile = 'data/applications.json';
    $applications = file_exists($appsFile) ? json_decode(file_get_contents($appsFile), true) : [];
    if (!is_array($applications)) $applications = [];

    $applicationId = "WALK-" . time();

    // 2. Capture and Save Data
    $newEntry = [
        'application_id'  => $applicationId,
        'email'           => trim(strtolower($_POST['parent_email'])),
        'parent_name'     => $_POST['parent_name'],
        'parent_phone'    => $_POST['phone'],
        'child_name'      => $_POST['child_name'],
        'gender'          => $_POST['gender'],
        'dob'             => $_POST['dob'],
        'grade'           => $_POST['grade'],
        'medical_info'    => $_POST['medical_info'], // Captured Medical Info
        'status'          => 'Approved', 
        'submission_date' => date("Y-m-d H:i:s"),
        'documents'       => ['doc_birth' => 'Walk-in/Manual Entry']
    ];

    $applications[] = $newEntry;
    file_put_contents($appsFile, json_encode($applications, JSON_PRETTY_PRINT));
    
    header("Location: headmaster_portal.php?msg=walkin_success");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Walk-in Registration | Headmaster</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-page">
     <?php include 'includes/navbar.php'; ?>

    <main class="container" style="max-width: 800px; margin-top: 50px;">
        <div style="margin-bottom: 20px;">
            <a href="headmaster_portal.php" style="color: #003366; text-decoration: none; font-weight: bold;">
                <i class="fas fa-arrow-left"></i> Back to Portal
            </a>
        </div>

        <div class="card" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 5px solid #28a745;">
            <h2 style="color: #28a745; margin-bottom: 10px;"><i class="fas fa-walking"></i> Walk-in Registration</h2>
            
            <form action="admin_add_child.php" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Parent/Guardian Name</label>
                        <input type="text" name="parent_name" required style="width:100%; padding:10px;">
                    </div>
                    <div class="form-group">
                        <label>Parent Phone Number</label>
                        <input type="tel" name="phone" required placeholder="0123456789" style="width:100%; padding:10px;">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Parent Email Address</label>
                        <input type="email" name="parent_email" required style="width:100%; padding:10px;">
                    </div>

                    <div class="form-group">
                        <label>Child Full Name</label>
                        <input type="text" name="child_name" required style="width:100%; padding:10px;">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" required style="width:100%; padding:10px;">
                            <option value="">-- Select --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" id="dob_input" onchange="updateGradeOptions()" required style="width:100%; padding:10px;">
                    </div>

                    <div class="form-group">
                        <label>Grade Applying For <span id="age_badge" style="color:#28a745;"></span></label>
                        <select name="grade" id="grade_select" required style="width:100%; padding:10px;">
                            <option value="">Select DOB first...</option>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label>Medical Allergies / Info</label>
                        <textarea name="medical_info" placeholder="Enter any medical conditions or 'None'" style="width:100%; padding:10px; height: 80px;"></textarea>
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-success" style="width: 100%; background: #28a745; color: white; padding: 12px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                        <i class="fas fa-save"></i> Finalize & Register Child
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
    function updateGradeOptions() {
        const dobInput = document.getElementById('dob_input').value;
        const gradeSelect = document.getElementById('grade_select');
        const ageBadge = document.getElementById('age_badge');
        
        if (!dobInput) return;

        const birthDate = new Date(dobInput);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
            age--;
        }

        ageBadge.innerText = "(Age: " + age + ")";
        gradeSelect.innerHTML = ""; 

        let option = document.createElement("option");
        
        if (age >= 2 && age <= 3) {
            option.value = "Toddlers"; option.text = "Toddlers (2-3 years)";
        } else if (age > 3 && age <= 4) {
            option.value = "Playgroup"; option.text = "Playgroup (3-4 years)";
        } else if (age > 4 && age <= 5) {
            option.value = "Pre-School"; option.text = "Pre-School (4-5 years)";
        } else if (age >= 6 && age <= 9) {
            option.value = "Foundation"; option.text = "Foundation Phase (6-9 years)";
        } else {
            option.value = ""; option.text = "Outside enrollment range";
        }
        gradeSelect.appendChild(option);
    }
    </script>
</body>
</html>