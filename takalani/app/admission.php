<?php
// Set up error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CRITICAL FIX 1: Robust session start (required for authorization and user data retrieval)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Authorization Check: Must be logged in as a parent
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'parent') {
    header("Location: login.php");
    exit();
}

// Define the file path for JSON storage and file upload directory
// CRITICAL FIX 2: Corrected data path (Removed redundant '/../data/../data/')
$admissionFile = __DIR__ . '/../data/admissions.json';
$uploadDir = __DIR__ . '/../data/uploads/'; // CRITICAL FIX 2: Corrected uploads path

// Ensure the upload directory exists
if (!is_dir($uploadDir)) {
    // Attempt to create the directory recursively with read/write permissions (0777)
    if (!mkdir($uploadDir, 0777, true)) {
        // Provide a clearer error message in case of failure
        die("Fatal Error: Failed to create upload directory. Check XAMPP/Windows permissions on the /data/ folder.");
    }
}

// Load existing admissions data
$admissions = file_exists($admissionFile)
    ? json_decode(file_get_contents($admissionFile), true)
    : [];

if ($admissions === null) {
    $admissions = [];
}

$success = "";
$error = "";
$submissionSummary = null;

// Define Grade Categories for consistency (Must be available for JavaScript validation logic)
$gradeCategories = [
    'Infants' => ['min' => 0.5, 'max' => 1, 'label' => 'Infants (6-12 months)'], 
    'Toddlers' => ['min' => 1, 'max' => 3, 'label' => 'Toddlers (1-3 years)'],
    'Playgroup' => ['min' => 3, 'max' => 4, 'label' => 'Playgroup (3-4 years)'],
    'Pre-School' => ['min' => 4, 'max' => 5, 'label' => 'Pre-School (4-5 years)']
];

// --- Form Submission Logic ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Helper function to safely get POST data
    function getPostData($key, $default = '') {
        return trim($_POST[$key] ?? $default);
    }
    
    // Helper function to safely get child data array
    function getChildData($key) {
        return $_POST[$key] ?? [];
    }

    // Parent Information (Pulled from POST, not session in this form)
    $parentData = [
        'parentFirstName'   => getPostData('parentFirstName'),
        'parentSurname'     => getPostData('parentSurname'),
        'contactNumber'     => getPostData('contactNumber'),
        'emailAddress'      => getPostData('emailAddress'),
        'residentialAddress'=> getPostData('residentialAddress'),
        'parentIdNumber'    => getPostData('parentIdNumber'),
    ];

    // Child Information (Handle multiple children)
    $childrenData = [];
    $childFirstNames = getChildData('childFirstName');
    $childSurnames = getChildData('childSurname');
    $dateOfBirths = getChildData('dateOfBirth');
    $grades = getChildData('gradeApplyingFor');
    $genders = getChildData('childGender');
    $medicalInfos = getChildData('medicalInfo');
    
    $isValid = true;

    // Process each child
    foreach ($childFirstNames as $index => $firstName) {
        if (empty($firstName) || empty($dateOfBirths[$index])) {
             $error = "Child information is incomplete.";
             $isValid = false;
             break;
        }

        // Calculate age
        $dob = new DateTime($dateOfBirths[$index]);
        $now = new DateTime();
        $ageInterval = $now->diff($dob);
        $ageInYears = $ageInterval->y + ($ageInterval->m / 12) + ($ageInterval->d / 365.25);
        
        // Basic Age/Grade Validation
        $gradeKey = $grades[$index] ?? null;
        if ($gradeKey && isset($gradeCategories[$gradeKey])) {
            $minAge = $gradeCategories[$gradeKey]['min'];
            $maxAge = $gradeCategories[$gradeKey]['max'];
            
            if ($ageInYears < $minAge || $ageInYears > $maxAge) {
                $error = "Child #".($index + 1)." age ({".round($ageInYears, 2)." years}) is outside the acceptable range (".$minAge."-".$maxAge." years) for **{$gradeKey}**. Please check the date of birth or grade selection.";
                $isValid = false;
                break;
            }
        } else {
             $error = "Invalid grade selected for Child #".($index + 1).".";
             $isValid = false;
             break;
        }


        // File Uploads - Child ID Document
        $childDocFileName = null;
        $childDocKey = 'childIdDocument_' . $index;
        if (isset($_FILES[$childDocKey]) && $_FILES[$childDocKey]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$childDocKey]['tmp_name'];
            $fileExtension = pathinfo($_FILES[$childDocKey]['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('child_') . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $childDocFileName = $newFileName;
            } else {
                $error = "Failed to move child ID file for Child #".($index + 1).". Check directory permissions.";
                $isValid = false;
                break;
            }
        } else {
             $error = "Child ID Document is required for Child #".($index + 1).".";
             $isValid = false;
             break;
        }


        $childrenData[] = [
            'firstName'         => $firstName,
            'surname'           => $childSurnames[$index] ?? '',
            'dateOfBirth'       => $dateOfBirths[$index],
            'ageInYears'        => round($ageInYears, 2),
            'gender'            => $genders[$index] ?? 'N/A',
            'gradeApplyingFor'  => $gradeKey,
            'medicalInfo'       => $medicalInfos[$index] ?? 'None',
            'childIdDocument'   => $childDocFileName,
        ];
    }
    
    // File Uploads - Parent ID Document
    $parentIdDocFileName = null;
    if ($isValid) {
        if (isset($_FILES['parentIdDocument']) && $_FILES['parentIdDocument']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['parentIdDocument']['tmp_name'];
            $fileExtension = pathinfo($_FILES['parentIdDocument']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('parent_') . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $parentIdDocFileName = $newFileName;
            } else {
                $error = "Failed to move parent ID file. Check directory permissions.";
                $isValid = false;
            }
        } else {
            $error = "Parent ID Document is required.";
            $isValid = false;
        }
    }


    // --- Save Data ---
    if ($isValid) {
        // Generate a simple unique ID (using timestamp + a random number)
        $applicationID = uniqid(); 

        $newAdmission = [
            'applicationID' => $applicationID,
            'timestamp'     => date('Y-m-d H:i:s'),
            'status'        => 'Pending', // Initial status
            'parent'        => array_merge($parentData, ['parentIdDocument' => $parentIdDocFileName]),
            'children'      => $childrenData,
        ];
        
        $admissions[] = $newAdmission;

        if (file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT))) {
            $success = "✅ Your application(s) have been successfully submitted! The total number of children submitted is: " . count($childrenData) . ". You will receive an update in your **Guardian Dashboard** once reviewed.";
            // Prepare summary for display
            $submissionSummary = [
                'parentName' => $parentData['parentFirstName'] . ' ' . $parentData['parentSurname'],
                'children' => $childrenData,
                'applicationID' => $applicationID
            ];
            
        } else {
            $error = "❌ Failed to save application data. Check file permissions on the data folder.";
        }
    }
}

// --- User Data Pre-fill (From Session) ---
$sessionUser = $_SESSION['user'] ?? [];
$defaultParent = [
    'parentFirstName'   => $sessionUser['parentName'] ?? '',
    'parentSurname'     => $sessionUser['parentSurname'] ?? '',
    'contactNumber'     => $sessionUser['phone'] ?? '',
    'emailAddress'      => $sessionUser['email'] ?? '',
    // Residential Address and ID number are typically not in the session
    'residentialAddress'=> '', 
    'parentIdNumber'    => '',
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admission Application</title>
    <link rel="stylesheet" href="/public/css/styles.css">
    <style>
        .child-section { 
            border: 1px solid #ddd; 
            padding: 20px; 
            margin-top: 15px; 
            border-radius: 8px; 
            background-color: #f9f9f9;
        }
        .error-message { color: red; font-weight: bold; margin-bottom: 15px; }
        .success-message { color: green; font-weight: bold; margin-bottom: 15px; }
        .summary-card { border: 2px solid green; padding: 20px; margin-top: 20px; background-color: #e6ffe6; border-radius: 8px; }
        .required-file-note { color: #555; font-size: 0.9em; margin-top: 5px; }
    </style>
</head>
<body>

<?php 
// CRITICAL FIX 3: Corrected mismatched quote
require_once "../app/menu-bar.php"; 
?>

<main style="max-width: 800px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h2 style="text-align: center;">New Admission Application</h2>

    <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success && $submissionSummary): ?>
        <div class="success-message"><?= $success ?></div>
        <div class="summary-card">
            <h4>Application Summary (ID: <?= htmlspecialchars($submissionSummary['applicationID']) ?>)</h4>
            <p><strong>Parent:</strong> <?= htmlspecialchars($submissionSummary['parentName']) ?></p>
            <p><strong>Children Submitted:</strong></p>
            <ul>
                <?php foreach ($submissionSummary['children'] as $child): ?>
                    <li><?= htmlspecialchars($child['firstName'] . ' ' . $child['surname']) ?> (Grade: <?= htmlspecialchars($child['gradeApplyingFor']) ?>)</li>
                <?php endforeach; ?>
            </ul>
            <p style="margin-top: 10px;">Check your <a href="parent.php">Guardian Dashboard</a> for status updates.</p>
        </div>
    <?php else: ?>
    
        <form id="admissionForm" method="POST" enctype="multipart/form-data" style="text-align:left;">
            
            <fieldset>
                <legend>Guardian/Parent Information</legend>
                
                <label for="parentFirstName">First Name *</label>
                <input type="text" id="parentFirstName" name="parentFirstName" value="<?= htmlspecialchars($defaultParent['parentFirstName']) ?>" required>

                <label for="parentSurname">Surname *</label>
                <input type="text" id="parentSurname" name="parentSurname" value="<?= htmlspecialchars($defaultParent['parentSurname']) ?>" required>
                
                <label for="contactNumber">Contact Number *</label>
                <input type="tel" id="contactNumber" name="contactNumber" pattern="[0-9]{10}" placeholder="e.g., 0712345678" value="<?= htmlspecialchars($defaultParent['contactNumber']) ?>" required>

                <label for="emailAddress">Email Address *</label>
                <input type="email" id="emailAddress" name="emailAddress" value="<?= htmlspecialchars($defaultParent['emailAddress']) ?>" required>
                
                <label for="parentIdNumber">ID Number *</label>
                <input type="text" id="parentIdNumber" name="parentIdNumber" pattern="[0-9]{13}" placeholder="13-digit ID" required>
                <p class="required-file-note">A 13-digit ID number is required.</p>

                <label for="parentIdDocument">Upload Parent ID Document *</label>
                <input type="file" id="parentIdDocument" name="parentIdDocument" accept=".pdf, .jpg, .jpeg, .png" required>
                <p class="required-file-note">PDF, JPG, or PNG only.</p>

                <label for="residentialAddress">Residential Address *</label>
                <textarea id="residentialAddress" name="residentialAddress" rows="3" required></textarea>
            </fieldset>

            <div id="childrenContainer">
                <fieldset class="child-section" id="childSection_0">
                    <legend>Child 1 Information</legend>
                    <label for="childFirstName_0">First Name *</label>
                    <input type="text" id="childFirstName_0" name="childFirstName[]" required>

                    <label for="childSurname_0">Surname *</label>
                    <input type="text" id="childSurname_0" name="childSurname[]" required>

                    <label for="dateOfBirth_0">Date of Birth *</label>
                    <input type="date" id="dateOfBirth_0" name="dateOfBirth[]" required>

                    <label for="childGender_0">Gender *</label>
                    <select id="childGender_0" name="childGender[]" required>
                        <option value="">-- Select --</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>

                    <label for="gradeApplyingFor_0">Grade Applying For *</label>
                    <select id="gradeApplyingFor_0" name="gradeApplyingFor[]" required>
                        <option value="">-- Select Grade --</option>
                        <?php foreach ($gradeCategories as $key => $details): ?>
                            <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($details['label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="required-file-note" id="gradeMessage_0"></p>

                    <label for="childIdDocument_0">Upload Child's ID/Birth Certificate *</label>
                    <input type="file" id="childIdDocument_0" name="childIdDocument_0" accept=".pdf, .jpg, .jpeg, .png" required>
                    <p class="required-file-note">PDF, JPG, or PNG only.</p>

                    <label for="medicalInfo_0">Medical Information / Allergies</label>
                    <textarea id="medicalInfo_0" name="medicalInfo[]" rows="2"></textarea>
                    
                    <button type="button" class="remove-child-btn" data-index="0" style="display:none; margin-top:10px;">Remove Child</button>
                </fieldset>
            </div>
            
            <button type="button" id="addChildBtn" style="margin-top: 20px;">+ Add Another Child</button>

            <button type="submit" id="submitBtn" style="display: block; width: 100%; margin-top: 30px;" disabled>Submit Application</button>
        </form>

    <?php endif; ?>
</main>

<script>
    const gradeCategories = <?= json_encode($gradeCategories) ?>;
    let childCount = 1;

    // --- Core Validation Functions ---

    /** Calculates age in years from DOB to today. */
    function calculateAge(dobString) {
        if (!dobString) return 0;
        const dob = new Date(dobString);
        const now = new Date();
        
        let age = now.getFullYear() - dob.getFullYear();
        const monthDiff = now.getMonth() - dob.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && now.getDate() < dob.getDate())) {
            age--;
        }
        
        // Calculate a more precise age for the pre-school context
        const diffInMs = now.getTime() - dob.getTime();
        return diffInMs / (1000 * 60 * 60 * 24 * 365.25); 
    }

    /** Validates if the selected grade matches the calculated age. */
    function validateGradeCategory(gradeElement, dobElement, index) {
        const gradeKey = gradeElement.value;
        const dobString = dobElement.value;
        const messageElement = document.getElementById(`gradeMessage_${index}`);

        messageElement.textContent = ''; 
        gradeElement.setCustomValidity('');

        if (gradeKey && dobString) {
            const age = calculateAge(dobString);
            const category = gradeCategories[gradeKey];

            if (category) {
                const minAge = category.min;
                const maxAge = category.max;
                
                if (age < minAge || age > maxAge) {
                    const ageDisplay = age.toFixed(2);
                    messageElement.textContent = `❌ Age mismatch! Your child's age is ${ageDisplay} years. This grade requires ${minAge} to ${maxAge} years.`;
                    messageElement.style.color = 'red';
                    gradeElement.setCustomValidity(`Age ${ageDisplay} is outside the range ${minAge}-${maxAge} for this grade.`);
                } else {
                    const ageDisplay = age.toFixed(2);
                    messageElement.textContent = `✅ Age (${ageDisplay} years) is within the recommended range (${minAge}-${maxAge} years).`;
                    messageElement.style.color = 'green';
                }
            }
        }
    }

    /** Checks if all required fields are filled and validation passes. */
    function checkFormValidity() {
        const form = document.getElementById('admissionForm');
        const submitBtn = document.getElementById('submitBtn');
        
        // Check standard browser validity for all required fields
        let isFormValid = form.checkValidity();
        
        // Custom check for Grade/Age validation messages (to ensure customValidity is respected)
        document.querySelectorAll('p[id^="gradeMessage_"]').forEach(p => {
            if (p.style.color === 'red') {
                isFormValid = false;
            }
        });
        
        submitBtn.disabled = !isFormValid;
    }

    /** Attaches listeners for age/grade validation. */
    function attachValidationListeners(index) {
        const dobElement = document.getElementById(`dateOfBirth_${index}`);
        const gradeElement = document.getElementById(`gradeApplyingFor_${index}`);
        const childDocElement = document.getElementById(`childIdDocument_${index}`); // File Input

        if (dobElement && gradeElement) {
            const handler = () => {
                validateGradeCategory(gradeElement, dobElement, index);
                checkFormValidity(); 
            };

            // Recalculate and validate on change for DOB and Grade
            dobElement.addEventListener('change', handler);
            gradeElement.addEventListener('change', handler);
            
            // Initial run if fields are pre-filled (useful after validation failure)
            handler(); 
        }
        
        // Add input listener for child document file input
        if (childDocElement) {
             childDocElement.addEventListener('change', checkFormValidity);
        }
    }
    
    // --- Dynamic Child Section Management ---

    function addChildSection() {
        const index = childCount;
        const container = document.getElementById('childrenContainer');
        
        const newSection = document.createElement('fieldset');
        newSection.className = 'child-section';
        newSection.id = `childSection_${index}`;
        newSection.innerHTML = `
            <legend>Child ${index + 1} Information</legend>
            <label for="childFirstName_${index}">First Name *</label>
            <input type="text" id="childFirstName_${index}" name="childFirstName[]" required>

            <label for="childSurname_${index}">Surname *</label>
            <input type="text" id="childSurname_${index}" name="childSurname[]" required>

            <label for="dateOfBirth_${index}">Date of Birth *</label>
            <input type="date" id="dateOfBirth_${index}" name="dateOfBirth[]" required>

            <label for="childGender_${index}">Gender *</label>
            <select id="childGender_${index}" name="childGender[]" required>
                <option value="">-- Select --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="gradeApplyingFor_${index}">Grade Applying For *</label>
            <select id="gradeApplyingFor_${index}" name="gradeApplyingFor[]" required>
                <option value="">-- Select Grade --</option>
                <?php foreach ($gradeCategories as $key => $details): ?>
                    <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($details['label']) ?></option>
                <?php endforeach; ?>
            </select>
            <p class="required-file-note" id="gradeMessage_${index}"></p>

            <label for="childIdDocument_${index}">Upload Child's ID/Birth Certificate *</label>
            <input type="file" id="childIdDocument_${index}" name="childIdDocument_${index}" accept=".pdf, .jpg, .jpeg, .png" required>
            <p class="required-file-note">PDF, JPG, or PNG only.</p>
            
            <label for="medicalInfo_${index}">Medical Information / Allergies</label>
            <textarea id="medicalInfo_${index}" name="medicalInfo[]" rows="2"></textarea>
            
            <button type="button" class="remove-child-btn" data-index="${index}" style="margin-top:10px;">Remove Child</button>
        `;
        
        container.appendChild(newSection);
        attachValidationListeners(index);
        
        // Show the remove button on all previous children
        document.querySelectorAll('.remove-child-btn').forEach(btn => btn.style.display = 'inline-block');
        
        childCount++;
        checkFormValidity();
    }
    
    function removeChildSection(indexToRemove) {
        const section = document.getElementById(`childSection_${indexToRemove}`);
        if (section) {
            section.remove();
            childCount--;
            
            // Re-index remaining sections
            const remainingSections = document.querySelectorAll('.child-section');
            remainingSections.forEach((section, newIndex) => {
                const oldIndex = parseInt(section.id.split('_')[1]);
                if (oldIndex > indexToRemove) {
                    section.id = `childSection_${newIndex}`;
                    section.querySelector('legend').textContent = `Child ${newIndex + 1} Information`;
                    
                    // Update all IDs and names within the section
                    section.querySelectorAll('[id$="_' + oldIndex + '"]').forEach(element => {
                         const baseId = element.id.slice(0, -2);
                         element.id = `${baseId}_${newIndex}`;
                         // File inputs need a new name for the PHP to correctly process the uploaded file array
                         if(element.type === 'file') {
                             element.name = `${baseId}_${newIndex}`;
                         }
                    });
                    
                    // Update remove button index
                    const removeBtn = section.querySelector('.remove-child-btn');
                    if (removeBtn) {
                        removeBtn.dataset.index = newIndex;
                    }
                }
            });
            
            // Hide remove button if only one child remains
            if (childCount === 1) {
                document.querySelector('#childSection_0 .remove-child-btn').style.display = 'none';
            }
            
            checkFormValidity();
        }
    }

    // --- Initialization ---
    document.addEventListener('DOMContentLoaded', () => {
        // Set up the first child section (index 0)
        attachValidationListeners(0);
        
        // Global listener for all inputs to check validity (including parent ID doc)
        document.getElementById('admissionForm').addEventListener('input', checkFormValidity);
        
        // File input listeners (only 'change' fires correctly on file selection)
        document.getElementById('parentIdDocument').addEventListener('change', checkFormValidity);
        document.getElementById('childIdDocument_0').addEventListener('change', checkFormValidity);

        // Listener for adding a new child
        document.getElementById('addChildBtn').addEventListener('click', addChildSection);
        
        // Listener for removing a child section
        document.getElementById('childrenContainer').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-child-btn')) {
                const indexToRemove = parseInt(event.target.dataset.index);
                removeChildSection(indexToRemove);
            }
        });

        // Initial validity check
        checkFormValidity();
    });

</script>
</body>
</html>