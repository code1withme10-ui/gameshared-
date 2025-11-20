<?php
// Set up error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session to potentially retrieve logged-in user data
session_start();

// Define the file path for JSON storage and file upload directory
$admissionFile = __DIR__ . '/admissions.json'; // Ensure this file is initialized with []
$uploadDir = __DIR__ . '/uploads/'; // Ensure this directory exists and is writable

// Ensure the upload directory exists
$setupError = null;
if (!is_dir($uploadDir)) {
    // Attempt to create the uploads directory with maximum permissions (0777)
    if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
        $setupError = "Fatal Setup Error: Failed to create 'uploads' directory. Check folder permissions.";
    }
}

// Load existing admissions data
$admissions = file_exists($admissionFile)
    ? json_decode(file_get_contents($admissionFile), true)
    : [];

$success = "";
$error = "";
$validationErrors = [];
$submissionSummary = null;

// Define Grade Categories for consistency (Min/Max are in years)
$gradeCategories = [
    'Infants' => ['min' => 0.5, 'max' => 1, 'label' => 'Infants (6-12 months)'], 
    'Toddlers' => ['min' => 1, 'max' => 3, 'label' => 'Toddlers (1-3 years)'],
    'Playgroup' => ['min' => 3, 'max' => 4, 'label' => 'Playgroup (3-4 years)'],
    'Pre-School' => ['min' => 4, 'max' => 5, 'label' => 'Pre-School (4-5 years)'],
];

// Document fields list for validation and summary display
$documentFields = [
    'childIDBirthCertificate' => 'Child ID / Birth Certificate',
    'parentID' => 'Parent ID',
    'proofOfResidence' => 'Proof of Residence', 
    'proofOfRegistration' => 'Proof of Registration / Application', 
];

// Session data for pre-filling parent fields
$loggedInParent  = $_SESSION['user']['parentName'] ?? null;
$loggedInEmail   = $_SESSION['user']['email'] ?? null;
$loggedInContact = $_SESSION['user']['phone'] ?? null;

/**
 * Calculates age in years (including fractional months).
 */
function calculateAge($dob) {
    if (empty($dob)) return 0;
    try {
        $birthDate = new DateTime($dob);
        $today = new DateTime();
        $interval = $today->diff($birthDate);
        return $interval->y + ($interval->m / 12) + ($interval->d / 365.25);
    } catch (\Exception $e) {
        return 0;
    }
}

/**
 * Checks if the age falls within the specified grade category range.
 */
function isAgeInGradeCategory($ageInYears, $categoryKey, $categories) {
    if (!isset($categories[$categoryKey])) return false;
    $min = $categories[$categoryKey]['min'];
    $max = $categories[$categoryKey]['max'];
    // Use floating point comparison accounting for precision
    return $ageInYears >= $min - 0.0001 && $ageInYears < $max + 0.0001;
}

/**
 * Handles file upload, validation (size/type), and moving the file.
 */
function handleFileUpload($fileInputName, $uploadDir, &$errors) {
    if (!isset($_FILES[$fileInputName])) {
        return false;
    }

    $file = $_FILES[$fileInputName];
    $fieldName = $GLOBALS['documentFields'][$fileInputName] ?? $fileInputName;

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return false;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "File upload error for $fieldName (Code: " . $file['error'] . ").";
        return false;
    }

    // Validation: Max size 2MB
    $maxSize = 2 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        $errors[] = "$fieldName exceeds the maximum size of 2MB.";
        return false;
    }

    // Validation: Allowed formats (PDF, JPG, PNG)
    $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    $fileType = mime_content_type($file['tmp_name']); 
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedMimeTypes)) {
         $errors[] = "$fieldName has an invalid file type. Only PDF, JPG, or PNG are allowed.";
        return false;
    }
    
    $fileName = uniqid($fileInputName . '_', true) . '.' . $extension;
    $destination = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $fileName;
    } else {
        $errors[] = "Failed to save uploaded file for $fieldName. Check directory permissions for 'uploads'.";
        return false;
    }
}

// --- Submission Handling ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Parent Information Validation and Data Collection (Captured once)
    $parentFullName = trim($_POST['parentFullName'] ?? '');
    $relationshipToChild = trim($_POST['relationshipToChild'] ?? '');
    $emailAddress = trim($_POST['emailAddress'] ?? '');
    $phoneNumber = trim($_POST['phoneNumber'] ?? '');
    $parentResidentialAddress = trim($_POST['parentResidentialAddress'] ?? '');
    $emergencyContact = trim($_POST['emergencyContact'] ?? ''); // Added back for completeness, though not strictly required by the list

    if (empty($parentFullName)) $validationErrors[] = "Parent Full Name is required.";
    if (empty($relationshipToChild)) $validationErrors[] = "Relationship to Child is required.";
    if (empty($emailAddress)) $validationErrors[] = "Email Address is required.";
    if (empty($phoneNumber)) $validationErrors[] = "Phone Number is required.";
    if (empty($parentResidentialAddress)) $validationErrors[] = "Home Address is required.";
    // if (empty($emergencyContact)) $validationErrors[] = "Emergency Contact is required."; // Based on your previous code

    // Email validation
    if (!empty($emailAddress) && !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        $validationErrors[] = "Email Address must be a valid format.";
    }

    // Phone number validation
    if (!empty($phoneNumber) && !preg_match('/^[0-9\s\-\+\(\)]{7,20}$/', $phoneNumber)) { 
        $validationErrors[] = "Phone Number must be properly formatted (numeric and basic symbols).";
    }

    // 2. Document Uploads (Captured once per application)
    $uploadedFiles = [];
    foreach ($documentFields as $fieldKey => $fieldName) {
        $fileName = handleFileUpload($fieldKey, $uploadDir, $validationErrors);
        if ($fileName) {
            $uploadedFiles[$fieldKey] = $fileName;
        } else {
            // Check if a required file is missing
            if((!isset($_FILES[$fieldKey]) || $_FILES[$fieldKey]['error'] == UPLOAD_ERR_NO_FILE) && count($_FILES) > 0) {
                 $validationErrors[] = "$fieldName upload is required.";
            }
        }
    }
    
    // 3. Child Information Validation and Data Collection (Handles multiple children)
    $childrenData = [];
    $rawChildren = $_POST['children'] ?? [];

    if (empty($rawChildren)) {
         $validationErrors[] = "At least one child's details must be submitted.";
    }

    foreach ($rawChildren as $index => $child) {
        $childFirstName = trim($child['childFirstName'] ?? '');
        $childSurname = trim($child['childSurname'] ?? '');
        $childFullName = $childFirstName . " " . $childSurname;
        $dob = trim($child['dob'] ?? '');
        $gender = trim($child['gender'] ?? '');
        $gradeApplyingFor = trim($child['gradeApplyingFor'] ?? '');
        $childResidentialAddress = trim($child['childResidentialAddress'] ?? ''); 
        $previousSchool = trim($child['previousSchool'] ?? ''); 
        
        $prefix = "Child #$index: "; // For detailed error messages

        if (empty($childFirstName)) $validationErrors[] = $prefix . "First Name is required.";
        if (empty($childSurname)) $validationErrors[] = $prefix . "Surname is required.";
        if (empty($dob)) $validationErrors[] = $prefix . "Date of Birth is required.";
        if (empty($gender)) $validationErrors[] = $prefix . "Gender is required.";
        if (empty($gradeApplyingFor) || $gradeApplyingFor === 'Select Category') $validationErrors[] = $prefix . "Grade Applying For is required.";
        if (empty($childResidentialAddress)) $validationErrors[] = $prefix . "Residential Address is required.";
        
        // DOB cannot be a future date
        if (!empty($dob) && strtotime($dob) > time()) {
            $validationErrors[] = $prefix . "Date of Birth cannot be a future date.";
        }

        $ageInYears = 0;
        // Grade Category Validation (Age matching)
        if (!empty($dob) && !empty($gradeApplyingFor) && $gradeApplyingFor !== 'Select Category') {
            $ageInYears = calculateAge($dob);
            if (!isAgeInGradeCategory($ageInYears, $gradeApplyingFor, $gradeCategories)) {
                $validationErrors[] = $prefix . "Selected category does not match child’s age (" . number_format($ageInYears, 2) . " years).";
            }
        }

        // Store submitted child data only if name is present
        if (!empty($childFirstName)) {
             $childrenData[] = [
                "fullName" => $childFullName,
                "dateOfBirth" => $dob,
                "gender" => $gender,
                "gradeApplyingFor" => $gradeApplyingFor,
                "residentialAddress" => $childResidentialAddress,
                "previousSchool" => $previousSchool,
                "ageInYears" => number_format($ageInYears, 2),
            ];
        }
    }
    
    // 4. Final Submission Logic
    if (empty($validationErrors) && !isset($setupError)) {
        
        $applicationIDs = [];
        $savedAdmissions = [];

        // Save one JSON object per child/application
        foreach ($childrenData as $child) {
            $applicationID = uniqid("APP_");
            $newAdmission = [
                "applicationID" => $applicationID,
                "status" => "pending", 
                "timestamp" => date("Y-m-d H:i:s"),
                "child" => $child,
                "parent" => [
                    "fullName" => $parentFullName,
                    "relationshipToChild" => $relationshipToChild,
                    "emailAddress" => $emailAddress,
                    "phoneNumber" => $phoneNumber,
                    "residentialAddress" => $parentResidentialAddress,
                ],
                "documents" => $uploadedFiles, // Documents linked to all children in this application
            ];

            $admissions[] = $newAdmission;
            $applicationIDs[] = $applicationID;
            $savedAdmissions[] = $newAdmission;
        }

        // Save data to JSON file
        if (file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT))) {
            $success = "✅ Application(s) submitted successfully! Your Application ID(s): **" . implode(', ', $applicationIDs) . "**.";
            $submissionSummary = $savedAdmissions; 
            $_POST = array(); // Clear POST data after success
        } else {
            $error = "⚠ Unable to save application data. Check write permissions for **$admissionFile**.";
        }
    } else {
        $error = "❌ Submission failed. Please review the form and resolve all validation errors.";
        if (isset($setupError)) {
            $error .= " " . $setupError;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SCHOOL ADMISSION FORM</title>
    <style>
        /* CSS for the page layout and style */
        body { font-family: Arial, sans-serif; background: #f9f9f9; color: #333; margin: 0; }
        main { max-width: 800px; margin: 30px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1, h2, h3 { text-align: center; color: #007bff; }
        hr { border: 0; height: 1px; background: #ccc; margin: 15px 0; }
        .section { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="tel"], input[type="date"], select, textarea { 
            width: 100%; padding: 10px; margin: 5px 0 10px 0; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; 
        }
        button { background: #007bff; color: white; font-weight: bold; border: none; padding: 15px; cursor: pointer; width: 100%; border-radius: 5px; margin-top: 10px;}
        button[type="submit"] { background: #28a745; }
        button[type="submit"]:disabled { background: #6c757d; cursor: not-allowed; }
        .error-message { color: red; font-size: 0.9em; margin-top: -8px; margin-bottom: 10px; }
        .message.success { color: green; font-weight: bold; }
        .message.error { color: red; font-weight: bold; }
        .validation-errors { color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px; border-radius: 5px; background: #ffebeb; }
        .summary-box { border: 2px solid #007bff; padding: 20px; margin-top: 20px; border-radius: 8px; background: #e9f5ff; }
        .summary-box p { margin: 5px 0; }
        .file-preview { margin-top: 10px; max-width: 100%; height: auto; border: 1px solid #ccc; max-height: 150px; object-fit: contain; }
        .file-upload-block { border: 1px dashed #ccc; padding: 10px; margin-bottom: 10px; }
        .child-block { border: 2px solid #007bff; padding: 15px; margin-bottom: 15px; border-radius: 8px; background: #f0f8ff; }
        .child-block h3 { margin-top: 0; color: #007bff; text-align: left; border-bottom: 1px solid #007bff; padding-bottom: 5px; }
        .add-child { background: #007bff; color: white; margin-bottom: 15px; }
    </style>
</head>
<body>
    <main>
        <h1>SCHOOL ADMISSION FORM</h1>
        <hr>

        <?php 
        // Display Menu Bar if file exists in your structure
        if (file_exists('menu-bar.php')) { require_once 'menu-bar.php'; } 
        ?>

        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
            <?php if ($submissionSummary): ?>
                <div class="summary-box">
                    <h2>Application Summary (<?= count($submissionSummary) ?> Child(ren))</h2>
                    <h3>Parent Details (Common to all applications)</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($submissionSummary[0]['parent']['fullName']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($submissionSummary[0]['parent']['emailAddress']) ?></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($submissionSummary[0]['parent']['phoneNumber']) ?></p>
                    <h3 style="margin-top: 20px;">Submitted Children:</h3>
                    <?php foreach($submissionSummary as $app): ?>
                        <div style="border: 1px dashed #ccc; padding: 10px; margin-bottom: 10px;">
                            <p><strong>Child Name:</strong> <?= htmlspecialchars($app['child']['fullName']) ?></p>
                            <p><strong>Application ID:</strong> <?= htmlspecialchars($app['applicationID']) ?></p>
                            <p><strong>DOB/Age:</strong> <?= htmlspecialchars($app['child']['dateOfBirth']) ?> / <?= htmlspecialchars($app['child']['ageInYears']) ?> years</p>
                            <p><strong>Grade:</strong> <?= htmlspecialchars($app['child']['gradeApplyingFor']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($setupError) && !$success): ?>
             <div class="message error">**Setup Error:** <?= htmlspecialchars($setupError) ?></div>
        <?php endif; ?>

        <?php if ($error && !$success): ?>
            <div class="message error"><?= $error ?></div>
            <?php if (!empty($validationErrors)): ?>
                <div class="validation-errors">
                    <p>Please fix the following issues:</p>
                    <ul>
                        <?php foreach ($validationErrors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="admissionForm">
            
            <div class="section">
                <h2>SECTION 2: PARENT / GUARDIAN DETAILS</h2>
                <hr>

                <div class="form-group">
                    <label for="parentFullName">Parent Full Name *</label>
                    <input type="text" id="parentFullName" name="parentFullName" placeholder="Parent Full Name" required 
                           value="<?= htmlspecialchars($loggedInParent ?? $_POST['parentFullName'] ?? '') ?>" 
                           <?= $loggedInParent ? 'readonly' : '' ?> oninput="checkFormValidity()">
                    <div id="error-parentFullName" class="error-message"></div>
                </div>
                
                <div class="form-group">
                    <label for="relationshipToChild">Relationship to Child *</label>
                    <input type="text" id="relationshipToChild" name="relationshipToChild" placeholder="E.g., Mother, Father, Guardian" required 
                           value="<?= htmlspecialchars($_POST['relationshipToChild'] ?? '') ?>" oninput="checkFormValidity()">
                    <div id="error-relationshipToChild" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="emailAddress">Email Address *</label>
                    <input type="email" id="emailAddress" name="emailAddress" placeholder="Email Address" required 
                           value="<?= htmlspecialchars($loggedInEmail ?? $_POST['emailAddress'] ?? '') ?>"
                           <?= $loggedInEmail ? 'readonly' : '' ?> oninput="checkFormValidity()">
                    <div id="error-emailAddress" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="phoneNumber">Phone Number *</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Phone Number" required 
                           value="<?= htmlspecialchars($loggedInContact ?? $_POST['phoneNumber'] ?? '') ?>"
                           <?= $loggedInContact ? 'readonly' : '' ?> oninput="checkFormValidity()">
                    <div id="error-phoneNumber" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="parentResidentialAddress">Home Address *</label>
                    <textarea id="parentResidentialAddress" name="parentResidentialAddress" placeholder="Home Address" required oninput="checkFormValidity()"><?= htmlspecialchars($_POST['parentResidentialAddress'] ?? '') ?></textarea>
                    <div id="error-parentResidentialAddress" class="error-message"></div>
                </div>
            </div>

            <div class="section">
                <h2>SECTION 1: CHILD DETAILS</h2>
                <hr>
                
                <div id="children-container">
                    </div>

                <button type="button" class="add-child" onclick="addChild()">+ Add Another Child</button>
            </div>


            <div class="section">
                <h2>SECTION 3: UPLOAD SUPPORTING DOCUMENTS</h2>
                <hr>
                <p style="font-size: 0.9em; color: #555;">Allowed formats: **PDF, JPG, PNG**. Max size: **2MB** per file. These documents are applied to all children in this application.</p>

                <div class="form-group file-upload-block">
                    <label for="childIDBirthCertificate">Child ID / Birth Certificate *</label>
                    <input type="file" id="childIDBirthCertificate" name="childIDBirthCertificate" required 
                           accept=".pdf, .jpg, .jpeg, .png" onchange="checkFormValidity()">
                    <div id="error-childIDBirthCertificate" class="error-message"></div>
                    <img id="preview-childIDBirthCertificate" class="file-preview" style="display:none;">
                </div>

                <div class="form-group file-upload-block">
                    <label for="parentID">Parent ID *</label>
                    <input type="file" id="parentID" name="parentID" required 
                           accept=".pdf, .jpg, .jpeg, .png" onchange="checkFormValidity()">
                    <div id="error-parentID" class="error-message"></div>
                    <img id="preview-parentID" class="file-preview" style="display:none;">
                </div>

                <div class="form-group file-upload-block">
                    <label for="proofOfResidence">Proof of Residence *</label>
                    <input type="file" id="proofOfResidence" name="proofOfResidence" required 
                           accept=".pdf, .jpg, .jpeg, .png" onchange="checkFormValidity()">
                    <div id="error-proofOfResidence" class="error-message"></div>
                    <img id="preview-proofOfResidence" class="file-preview" style="display:none;">
                </div>
                
                 <div class="form-group file-upload-block">
                    <label for="proofOfRegistration">Proof of Registration / Application *</label>
                    <input type="file" id="proofOfRegistration" name="proofOfRegistration" required 
                           accept=".pdf, .jpg, .jpeg, .png" onchange="checkFormValidity()">
                    <div id="error-proofOfRegistration" class="error-message"></div>
                    <img id="preview-proofOfRegistration" class="file-preview" style="display:none;">
                </div>
            </div>

            <div class="section">
                <h2>SECTION 4: FORM VALIDATION AND SUBMISSION</h2>
                <hr>
                
                <div id="validation-errors-display" class="validation-errors" style="display:none;">
                    </div>

                <button type="submit" id="submitBtn" disabled>SUBMIT APPLICATION</button>
            </div>
        </form>
    </main>

    <script>
        const GRADE_CATEGORIES = <?= json_encode($gradeCategories) ?>;
        const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
        const ALLOWED_MIME_TYPES = ['application/pdf', 'image/jpeg', 'image/png'];
        
        // Base list of required fields for client-side validation
        const BASE_REQUIRED_FIELDS = [
            'parentFullName', 'relationshipToChild', 'emailAddress', 'phoneNumber', 'parentResidentialAddress',
            'childIDBirthCertificate', 'parentID', 'proofOfResidence', 'proofOfRegistration'
        ];
        
        // Fields required for EACH child block
        const CHILD_REQUIRED_FIELDS = ['childFirstName', 'childSurname', 'dob', 'gender', 'gradeApplyingFor', 'childResidentialAddress'];

        // --- Child Block Template ---
        function getChildBlockTemplate(index, postData = {}) {
            const data = postData[`children`] ? postData[`children`][index] : {};
            const genderChecked = (val) => (data.gender === val ? 'checked' : '');
            const gradeSelected = (val) => (data.gradeApplyingFor === val ? 'selected' : '');
            
            const template = document.createElement('div');
            template.classList.add('child-block');
            template.dataset.childIndex = index;
            template.innerHTML = `
                <h3>Child #${index + 1} Details 
                    ${index > 0 ? `<button type="button" onclick="removeChild(${index})" style="width: auto; padding: 5px 10px; float: right; background: #dc3545; border: none;">Remove</button>` : ''}
                </h3>
                
                <div class="form-group">
                    <label for="childFirstName_${index}">Child First Name *</label>
                    <input type="text" id="childFirstName_${index}" name="children[${index}][childFirstName]" placeholder="Child First Name" required 
                           value="${data.childFirstName || ''}" oninput="checkFormValidity()">
                    <div id="error-childFirstName_${index}" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="childSurname_${index}">Child Surname *</label>
                    <input type="text" id="childSurname_${index}" name="children[${index}][childSurname]" placeholder="Child Surname" required 
                           value="${data.childSurname || ''}" oninput="checkFormValidity()">
                    <div id="error-childSurname_${index}" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="dob_${index}">Date of Birth *</label>
                    <input type="date" id="dob_${index}" name="children[${index}][dob]" required 
                           value="${data.dob || ''}" onchange="checkFormValidity()">
                    <div id="error-dob_${index}" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label>Gender *</label>
                    <div>
                        <input type="radio" id="genderMale_${index}" name="children[${index}][gender]" value="Male" required onclick="checkFormValidity()" ${genderChecked('Male')}> <label for="genderMale_${index}" style="display:inline;">Male</label>
                        <input type="radio" id="genderFemale_${index}" name="children[${index}][gender]" value="Female" onclick="checkFormValidity()" ${genderChecked('Female')}> <label for="genderFemale_${index}" style="display:inline;">Female</label>
                        <input type="radio" id="genderOther_${index}" name="children[${index}][gender]" value="Other" onclick="checkFormValidity()" ${genderChecked('Other')}> <label for="genderOther_${index}" style="display:inline;">Other</label>
                    </div>
                    <div id="error-gender_${index}" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="gradeApplyingFor_${index}">Grade Applying For *</label>
                    <select id="gradeApplyingFor_${index}" name="children[${index}][gradeApplyingFor]" required 
                            onchange="checkFormValidity()">
                        <option value="Select Category">⬇ Select Category *</option>
                        ${Object.keys(GRADE_CATEGORIES).map(key => `
                            <option value="${key}" 
                                    data-min="${GRADE_CATEGORIES[key].min}" 
                                    data-max="${GRADE_CATEGORIES[key].max}"
                                    ${gradeSelected(key)}
                                    >${GRADE_CATEGORIES[key].label}</option>
                        `).join('')}
                    </select>
                    <div id="error-gradeApplyingFor_${index}" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="childResidentialAddress_${index}">Residential Address *</label>
                    <textarea id="childResidentialAddress_${index}" name="children[${index}][childResidentialAddress]" placeholder="Residential Address" required oninput="checkFormValidity()">${data.childResidentialAddress || ''}</textarea>
                    <div id="error-childResidentialAddress_${index}" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="previousSchool_${index}">Previous School (Optional)</label>
                    <input type="text" id="previousSchool_${index}" name="children[${index}][previousSchool]" placeholder="Previous School"
                           value="${data.previousSchool || ''}">
                </div>
            `;
            return template;
        }
        
        // --- Child Management Functions ---

        function addChild() {
            const container = document.getElementById('children-container');
            // Find the next highest index to avoid collisions
            const existingIndices = Array.from(container.querySelectorAll('.child-block')).map(b => parseInt(b.dataset.childIndex));
            const nextIndex = existingIndices.length > 0 ? Math.max(...existingIndices) + 1 : 0;
            
            const newBlock = getChildBlockTemplate(nextIndex, <?= json_encode($_POST) ?>);
            container.appendChild(newBlock);
            checkFormValidity();
        }

        function removeChild(index) {
            const blockToRemove = document.querySelector(`.child-block[data-child-index="${index}"]`);
            if (blockToRemove) {
                blockToRemove.remove();
            }
            checkFormValidity();
        }

        // Initialize form with one child block or repopulate from failed POST
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('children-container');
            const childrenPostData = <?= json_encode($_POST['children'] ?? []) ?>;
            
            if (Object.keys(childrenPostData).length > 0) {
                Object.keys(childrenPostData).forEach((key) => {
                    const index = parseInt(key);
                    const newBlock = getChildBlockTemplate(index, <?= json_encode($_POST) ?>);
                    container.appendChild(newBlock);
                });
            } else {
                const initialBlock = getChildBlockTemplate(0, <?= json_encode($_POST) ?>);
                container.appendChild(initialBlock);
            }
            
            // Set up main listeners
            document.getElementById('admissionForm').addEventListener('input', checkFormValidity);
            document.getElementById('admissionForm').addEventListener('change', checkFormValidity);
            checkFormValidity(); // Initial check to disable button
        });


        // --- Validation Functions (Updated for indexing) ---

        function calculateAgeInYears(dobString) {
            const birthDate = new Date(dobString);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            return age + (m / 12) + ((today.getDate() - birthDate.getDate()) / 365.25);
        }

        function validateField(input) {
            const id = input.id || input.name;
            const errorElement = document.getElementById(`error-${id}`);
            if (!errorElement) return true; // Ignore if error element doesn't exist
            let value = input.value ? input.value.trim() : '';

            if (input.type === 'radio') {
                 const radioGroup = document.querySelector(`input[name="${input.name}"]:checked`);
                 if (!radioGroup) {
                    errorElement.textContent = `This field is required.`;
                    return false;
                 }
            } else if (value === '' || (input.tagName === 'SELECT' && value === 'Select Category')) {
                errorElement.textContent = `${input.placeholder || input.id.replace(/([A-Z])/g, ' $1').trim()} is required.`;
                return false;
            } 
            
            errorElement.textContent = '';
            return true;
        }
        
        function validateEmail(input) {
            let isValid = validateField(input);
            const errorElement = document.getElementById('error-emailAddress');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 

            if (isValid && !input.readOnly && !emailPattern.test(input.value)) {
                errorElement.textContent = 'Email must include "@" and a valid domain.';
                isValid = false;
            }
            if(isValid) errorElement.textContent = '';
            return isValid;
        }

        function validatePhone(input) {
            let isValid = validateField(input);
            const errorElement = document.getElementById('error-phoneNumber');
            const phonePattern = /^[0-9\s\-\+\(\)]{7,20}$/; 

            if (isValid && !input.readOnly && !phonePattern.test(input.value)) {
                errorElement.textContent = 'Phone number must be numeric and properly formatted.';
                isValid = false;
            }
            if(isValid) errorElement.textContent = '';
            return isValid;
        }
        
        function validateChildDOB(index) {
            const input = document.getElementById(`dob_${index}`);
            let isValid = validateField(input);
            const errorElement = document.getElementById(`error-dob_${index}`);
            
            if (isValid && new Date(input.value) > new Date()) {
                errorElement.textContent = 'Date of birth cannot be a future date.';
                isValid = false;
            }
            if(isValid) errorElement.textContent = '';
            return isValid;
        }

        function validateChildGradeCategory(index) {
            const dobInput = document.getElementById(`dob_${index}`);
            const gradeSelect = document.getElementById(`gradeApplyingFor_${index}`);
            const errorElement = document.getElementById(`error-gradeApplyingFor_${index}`);
            
            errorElement.textContent = '';
            let isValid = gradeSelect.value !== 'Select Category';

            if (!dobInput || !validateChildDOB(index)) {
                // Disable options if DOB is missing/invalid
                gradeSelect.querySelectorAll('option').forEach(opt => {
                    if (opt.value !== 'Select Category') opt.disabled = true;
                });
                if(isValid) { 
                   errorElement.textContent = 'Please select a valid Date of Birth first.';
                   isValid = false;
                }
                return isValid; 
            }

            const ageInYears = calculateAgeInYears(dobInput.value);
            let selectedCategoryMatches = false;

            // Enable/disable options based on age
            gradeSelect.querySelectorAll('option').forEach(opt => {
                if (opt.value !== 'Select Category') {
                    const min = parseFloat(opt.dataset.min);
                    const max = parseFloat(opt.dataset.max);
                    
                    if (ageInYears < min || ageInYears >= max + 0.0001) { 
                        opt.disabled = true;
                    } else {
                        opt.disabled = false;
                        if (opt.selected) selectedCategoryMatches = true;
                    }
                }
            });

            if (gradeSelect.value !== 'Select Category') {
                if (!selectedCategoryMatches) {
                    errorElement.textContent = `Selected category does not match child’s age (Calculated: ${ageInYears.toFixed(2)} years).`;
                    isValid = false;
                }
            } else if (!isValid) {
                 errorElement.textContent = 'Grade Applying For is required.';
            }

            return isValid;
        }

        function validateFile(input) {
            const errorElement = document.getElementById(`error-${input.id}`);
            const previewImage = document.getElementById(`preview-${input.id}`);
            errorElement.textContent = '';
            if(previewImage) {
                previewImage.style.display = 'none';
                previewImage.src = '';
            }

            if (input.required && !input.files.length) {
                errorElement.textContent = `${input.id.replace(/([A-Z])/g, ' $1').trim()} is required.`;
                return false;
            } else if (!input.files.length) {
                return true; 
            }

            const file = input.files[0];

            if (file.size > MAX_FILE_SIZE) {
                errorElement.textContent = `File size (${(file.size / (1024 * 1024)).toFixed(2)}MB) exceeds 2MB limit.`;
                return false;
            }

            if (!ALLOWED_MIME_TYPES.includes(file.type)) {
                errorElement.textContent = `Invalid file type: ${file.type}. Only PDF, JPG, PNG allowed.`;
                return false;
            }
            
            if (file.type.startsWith('image/') && previewImage) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
            
            return true;
        }


        // --- Global Form Validity Check ---
        function checkFormValidity() {
            let allValid = true;
            let totalErrors = [];
            
            // 1. Validate Parent and Document Fields 
            BASE_REQUIRED_FIELDS.forEach(id => {
                const input = document.getElementById(id);
                if (!input) return;

                let isValid = true;
                if (input.type === 'email') {
                    isValid = validateEmail(input);
                } else if (input.type === 'tel') {
                    isValid = validatePhone(input);
                } else if (input.type === 'file') {
                    isValid = validateFile(input);
                } else {
                    isValid = validateField(input);
                }
                
                if (!isValid) {
                    allValid = false;
                }
            });

            // 2. Validate Child Blocks 
            const childBlocks = document.querySelectorAll('.child-block');
            let childCountValid = childBlocks.length > 0;
            if (!childCountValid) {
                allValid = false;
                totalErrors.push("At least one child's details must be submitted.");
            }

            childBlocks.forEach(block => {
                const index = block.dataset.childIndex;
                CHILD_REQUIRED_FIELDS.forEach(field => {
                    const id = `${field}_${index}`;
                    const input = document.getElementById(id);
                    if (!input) return;

                    let isValid = true;
                    if (field === 'dob') {
                        isValid = validateChildDOB(index);
                    } else if (field === 'gradeApplyingFor') {
                        isValid = validateChildGradeCategory(index);
                    } else if (field === 'gender') {
                        const genderInput = document.querySelector(`input[name="children[${index}][${field}]"]:checked`);
                        isValid = !!genderInput;
                        if (!isValid) validateField(document.querySelector(`input[name="children[${index}][${field}]"]`));
                    } else {
                        isValid = validateField(input);
                    }

                    if (!isValid) {
                        allValid = false;
                    }
                });
            });

            // Re-collect all current displayed errors (to handle dynamic changes)
            totalErrors = [];
            document.querySelectorAll('.error-message').forEach(errElement => {
                if (errElement.textContent.trim() !== '') {
                    // Try to make the error message specific
                    const parentBlock = errElement.closest('.child-block');
                    if (parentBlock) {
                        const index = parentBlock.dataset.childIndex;
                        const fieldName = errElement.id.replace('error-', '').replace(`_${index}`, '').replace(/([A-Z])/g, ' $1').trim();
                        totalErrors.push(`Child #${parseInt(index) + 1} (${fieldName}): ${errElement.textContent}`);
                    } else {
                        totalErrors.push(errElement.textContent);
                    }
                }
            });

            // 3. Manage Submit Button and Global Error Display
            const submitBtn = document.getElementById('submitBtn');
            const errorDisplay = document.getElementById('validation-errors-display');
            
            if (allValid && childCountValid && totalErrors.length === 0) {
                submitBtn.disabled = false;
                errorDisplay.style.display = 'none';
            } else {
                submitBtn.disabled = true;
                if (totalErrors.length > 0) {
                    const uniqueErrors = [...new Set(totalErrors)];
                    errorDisplay.style.display = 'block';
                    errorDisplay.innerHTML = '<p>Please resolve the following errors:</p><ul>' + uniqueErrors.map(err => `<li>${err}</li>`).join('') + '</ul>';
                } else {
                    errorDisplay.style.display = 'none';
                }
            }
        }
    </script>

    <?php 
    // Display Footer if file exists
    if (file_exists('footer.php')) { include 'footer.php'; } 
    ?>
</body>
</html>
