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

$message = '';
$valid = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Parent Info ---
    $parentName = $_POST['parentName'] ?? '';
    $relationship = $_POST['relationship'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $password = $_POST['password'] ?? '';

    // --- Child Info ---
    $childName = $_POST['childName'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $grade = $_POST['grade'] ?? '';
    $allergies = $_POST['allergies'] ?? '';
    $medications = $_POST['medications'] ?? '';

    // --- Validate Parent Fields ---
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $message = '<p style="color:red;">Invalid email format.</p>'; $valid = false; }
    if (!preg_match('/^\d{10}$/', $phone)) { $message = '<p style="color:red;">Phone number must be 10 digits.</p>'; $valid = false; }
    if (empty($relationship)) { $message = '<p style="color:red;">Please select your relationship to the child.</p>'; $valid = false; }
    if (empty($address)) { $message = '<p style="color:red;">Please enter your residential address.</p>'; $valid = false; }

    // --- Validate DOB ---
    $dobDate = new DateTime($dob);
    $today = new DateTime();
    if ($dobDate > $today) { $message = '<p style="color:red;">Date of birth cannot be in the future.</p>'; $valid = false; }

    // --- Calculate Age ---
    $age = $today->diff($dobDate)->y;

    // --- Grade-Age Mapping ---
    $grade_age_map = [
        'Infants' => [0,1],
        'Toddlers' => [1,3],
        'Playgroup' => [3,4],
        'Pre-School' => [4,5]
    ];

    // --- Validate Age vs Grade ---
    if (!isset($grade_age_map[$grade]) || $age < $grade_age_map[$grade][0] || $age > $grade_age_map[$grade][1]) {
        $message = "<p style='color:red;'>Selected grade does not match child's age.</p>";
        $valid = false;
    }

    // --- Validate Files ---
    $allowed_types = ['image/jpeg','image/png','application/pdf'];
    $max_size = 2*1024*1024;
    $files = ['birthCert'=>'Child Birth Certificate','parentID'=>'Parent ID'];

    foreach($files as $inputName=>$label){
        if(isset($_FILES[$inputName]) && $_FILES[$inputName]['error']==0){
            $fileType = $_FILES[$inputName]['type'];
            $fileSize = $_FILES[$inputName]['size'];
            if(!in_array($fileType,$allowed_types)) { $message = "<p style='color:red;'>$label must be JPEG, PNG, or PDF.</p>"; $valid=false; }
            elseif($fileSize > $max_size) { $message = "<p style='color:red;'>$label must be less than 2MB.</p>"; $valid=false; }
            else { $uploadDir='uploads/'; if(!is_dir($uploadDir)) mkdir($uploadDir,0777,true); $filePath=$uploadDir.basename($_FILES[$inputName]['name']); move_uploaded_file($_FILES[$inputName]['tmp_name'],$filePath); ${$inputName.'_path'}=$filePath; }
        } else { $message = "<p style='color:red;'>$label is required.</p>"; $valid=false; }
    }

    if($valid){
        $users = get_users($data_file);
        $userExists = false;
        foreach($users as $user){ if($user['email']===$email){ $userExists=true; break; } }

        if($userExists){ $message='<p style="color:red;">This email is already registered. Please log in or use a different email.</p>'; }
        else {
            $new_user=[
                'id'=>uniqid('user-'),
                'parentName'=>$parentName,'relationship'=>$relationship,'email'=>$email,'phone'=>$phone,'address'=>$address,'password'=>$password,
                'children'=>[[
                    'childId'=>uniqid('child-'),'name'=>$childName,'dob'=>$dob,'age'=>$age,'gender'=>$gender,'grade'=>$grade,
                    'allergies'=>$allergies,'medications'=>$medications,
                    'documents'=>['birthCert'=>$birthCert_path,'parentID'=>$parentID_path],'status'=>'Pending'
                ]]
            ];
            $users[]=$new_user;
            save_users($data_file,$users);
            $message='<p style="color:green;">Admission Complete! Your account is created. <a href="login.php">Log in now</a>.</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Online Admission</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
<span class="navbar-title">Humulani Pre School</span>
<nav class="navbar">
<ul>
<li><a href="index.php">Home</a></li>
<li><a href="about.php">About Us</a></li>
<li><a href="info.php">Info</a></li>
<li><a href="gallery.php">Gallery</a></li>
<li><a href="admission.php">Admission</a></li>
<li><a href="contact.php">Contact</a></li>
<li><a href="login.php">Login</a></li>
</ul>
</nav>
</div>

<div class="page-container">
<h1>Online Admission</h1>
<?php echo $message; ?>

<form method="POST" action="admission.php" enctype="multipart/form-data">
<h2>Parent/Guardian Details (Your Account)</h2>
<label for="parentName">Parent/Guardian Name:</label>
<input type="text" id="parentName" name="parentName" required><br><br>
<label for="relationship">Relationship to Child:</label>
<select id="relationship" name="relationship" required>
<option value="">Select Relationship</option>
<option value="Mother">Mother</option>
<option value="Father">Father</option>
<option value="Guardian">Guardian</option>
</select><br><br>
<label for="email">Email (Your Username):</label>
<input type="email" id="email" name="email" required><br><br>
<label for="phone">Phone Number:</label>
<input type="text" id="phone" name="phone" required><br><br>
<label for="address">Residential Address:</label>
<textarea id="address" name="address" rows="3" required></textarea><br><br>
<label for="password">Password:</label>
<input type="password" id="password" name="password" required><br><br>
<hr>
<h2>Child Details (First Child)</h2>
<label for="childName">Child's Name:</label>
<input type="text" id="childName" name="childName" required><br><br>
<label for="dob">Date of Birth:</label>
<input type="date" id="dob" name="dob" required><br><br>
<label for="gender">Gender:</label>
<select id="gender" name="gender" required>
<option value="">Select Gender</option>
<option value="Male">Male</option>
<option value="Female">Female</option>
</select><br><br>
<label for="grade">Grade:</label>
<select id="grade" name="grade" required>
<option value="">Select Grade</option>
<option value="Infants">Infants (6–12 months)</option>
<option value="Toddlers">Toddlers (1–3 years)</option>
<option value="Playgroup">Playgroup (3–4 years)</option>
<option value="Pre-School">Pre-School (4–5 years)</option>
</select><br><br>
<label for="allergies">Allergies (if any):</label>
<textarea id="allergies" name="allergies" rows="3" placeholder="e.g., peanuts, pollen"></textarea><br><br>
<label for="medications">Medications (if any):</label>
<textarea id="medications" name="medications" rows="3" placeholder="e.g., inhaler, antibiotics"></textarea><br><br>
<h2>Upload Required Documents</h2>
<label for="birthCert">Child Birth Certificate:</label>
<input type="file" id="birthCert" name="birthCert" accept=".jpeg,.jpg,.png,.pdf" required><br><br>
<label for="parentID">Parent/Guardian ID:</label>
<input type="file" id="parentID" name="parentID" accept=".jpeg,.jpg,.png,.pdf" required><br><br>
<button type="submit">Complete Admission & Create Account</button>
</form>
<p>Already have an account? <a href="login.php">Login here</a></p>
</div>
<footer>
<p>&copy; 2026 Humulani Pre School</p>
</footer>

<script>
const dobInput = document.getElementById('dob');
const gradeSelect = document.getElementById('grade');

dobInput.addEventListener('change', () => {
    const dob = new Date(dobInput.value);
    const today = new Date();
    let ageYears = today.getFullYear() - dob.getFullYear();
    let ageMonths = today.getMonth() - dob.getMonth();
    if(today.getDate() < dob.getDate()) ageMonths--;
    if(ageMonths < 0) { ageYears--; ageMonths += 12; }
    const totalMonths = ageYears*12 + ageMonths;

    Array.from(gradeSelect.options).forEach(opt => {
        if(opt.value==='') return;
        if(opt.value==='Infants') opt.disabled = !(totalMonths>=6 && totalMonths<=12);
        else if(opt.value==='Toddlers') opt.disabled = !(ageYears>=1 && ageYears<=3);
        else if(opt.value==='Playgroup') opt.disabled = !(ageYears>=3 && ageYears<4);
        else if(opt.value==='Pre-School') opt.disabled = !(ageYears>=4 && ageYears<=5);
    });

    if(gradeSelect.selectedOptions[0].disabled) gradeSelect.value='';
});
</script>

</body>
</html>
