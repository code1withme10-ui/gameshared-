<?php
// Set up error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session to potentially retrieve logged-in user data (though not strictly required by the prompt)
session_start();

// Define the file path for JSON storage and file upload directory
$admissionFile = __DIR__ . '/admissions.json';
$uploadDir = __DIR__ . '/uploads/';

// Ensure the upload directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Load existing admissions data
$admissions = file_exists($admissionFile)
    ? json_decode(file_get_contents($admissionFile), true)
    : [];

$success = "";
$error = "";
$submissionSummary = null; // To store and display the summary after successful submission

// Define Grade Categories for consistency
$gradeCategories = [
    'Infants' => ['min' => 0.5, 'max' => 1, 'label' => 'Infants (6-12 months)'], // 6/12 = 0.5 years
    'Toddlers' => ['min' => 1, 'max' => 3, 'label' => 'Toddlers (1-3 years)'],
    'Playgroup' => ['min' => 3, 'max' => 4, 'label' => 'Playgroup (3-4 years)'],
    'Pre-School' => ['min' => 4, 'max' => 5, 'label' => 'Pre-School (4-5 years)'],
];

// PHP Age Calculation Function (in years)
function calculateAge($dob) {
    if (empty($dob)) return 0;
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $interval = $today->diff($birthDate);
    // Return age in years, including a fraction for months
    return $interval->y + ($interval->m / 12);
}

// PHP Grade Validation Function
function isAgeInGradeCategory($ageInYears, $categoryKey, $categories) {
    if (!isset($categories[$categoryKey])) return false;
    $min = $categories[$categoryKey]['min'];
    $max = $categories[$categoryKey]['max'];
    // Use a small tolerance for floating point comparisons
    return $ageInYears >= $min - 0.0001 && $ageInYears < $max + 0.0001;
}

// PHP File Upload and Validation Function
function handleFileUpload($fileInputName, $uploadDir, &$errors) {
    if (!isset($_FILES[$fileInputName])) {
        $errors[] = "Required document '$fileInputName' is missing.";
        return false;
    }

    $file = $_FILES[$fileInputName];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "File upload error for $fileInputName: " . $file['error'];
        return false;
    }

    // Validation: Max size 2MB
    $maxSize = 2 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        $errors[] = "$fileInputName exceeds the maximum size of 2MB.";
        return false;
    }

    // Validation: Allowed formats
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    $fileType = mime_content_type($file['tmp_name']); // A more reliable way to check MIME type
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Fallback: Check extension if mime_content_type is not working/disabled
    if (!in_array($fileType, $allowedTypes) && !in_array('image/' . $extension, $allowedTypes) && !in_array('application/' . $extension, $allowedTypes)) {
         $errors[] = "$fileInputName has an invalid file type. Only PDF, JPG, or PNG are allowed.";
        return false;
    }
    
    // Generate a unique filename and move the file
    $fileName = uniqid($fileInputName . '_', true) . '.' . $extension;
    $destination = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $fileName; // Return the saved filename
    } else {
        $errors[] = "Failed to move uploaded file for $fileInputName.";
        return false;
    }
}

// --- Submission Handling ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $validationErrors = [];
    $applicationData = [];

    // 1. Child Information Validation and Data Collection
    $childFullName = trim($_POST['childFullName'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $gradeApplyingFor = trim($_POST['gradeApplyingFor'] ?? '');
    $childResidentialAddress = trim($_POST['childResidentialAddress'] ?? '');
    $previousSchool = trim($_POST['previousSchool'] ?? ''); // Optional

    if (empty($childFullName)) $validationErrors[] = "Child Full Name is required.";
    if (empty($dob)) $validationErrors[] = "Date of Birth is required.";
    if (empty($gender)) $validationErrors[] = "Gender is required.";
    if (empty($gradeApplyingFor) || $gradeApplyingFor === 'Select Category') $validationErrors[] = "Grade Applying For is required.";
    if (empty($childResidentialAddress)) $validationErrors[] = "Child Residential Address is required.";
    
    // DOB cannot be a future date
    if (!empty($dob) && strtotime($dob) > time()) {
        $validationErrors[] = "Date of Birth cannot be a future date.";
    }

    // Grade Category Validation (Age matching)
    if (!empty($dob) && !empty($gradeApplyingFor) && $gradeApplyingFor !== 'Select Category') {
        $ageInYears = calculateAge($dob);
        if (!isAgeInGradeCategory($ageInYears, $gradeApplyingFor, $gradeCategories)) {
            $validationErrors[] = "Selected category does not match child’s age (Calculated age: " . number_format($ageInYears, 2) . " years).";
        }
    }

    // 2. Parent Information Validation and Data Collection
    $parentFullName = trim($_POST['parentFullName'] ?? '');
    $relationshipToChild = trim($_POST['relationshipToChild'] ?? '');
    $emailAddress = trim($_POST['emailAddress'] ?? '');
    $phoneNumber = trim($_POST['phoneNumber'] ?? '');
    $parentResidentialAddress = trim($_POST['parentResidentialAddress'] ?? '');

    if (empty($parentFullName)) $validationErrors[] = "Parent Full Name is required.";
    if (empty($relationshipToChild)) $validationErrors[] = "Relationship to Child is required.";
    if (empty($emailAddress)) $validationErrors[] = "Email Address is required.";
    if (empty($phoneNumber)) $validationErrors[] = "Phone Number is required.";
    if (empty($parentResidentialAddress)) $validationErrors[] = "Parent Residential Address is required.";

    // Email validation
    if (!empty($emailAddress) && !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        $validationErrors[] = "Email Address must be a valid format.";
    }

    // Phone number validation (simple numeric check)
    $cleanPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
    if (!empty($phoneNumber) && !preg_match('/^[0-9\s\-\+\(\)]+$/', $phoneNumber)) { // Basic pattern validation
        $validationErrors[] = "Phone Number must be numeric and properly formatted.";
    }

    // 3. Supporting Documents Upload and Validation
    $uploadedFiles = [];
    
    // File upload requirements: Birth Certificate, Parent/Guardian ID. 
    // The page layout lists 4: Child ID/Birth Certificate, Parent ID, Proof of Residence, Proof of Registration.
    // We will follow the Page Layout for completeness.
    $documentFields = [
        'childIDBirthCertificate' => 'Child ID / Birth Certificate',
        'parentID' => 'Parent ID',
        'proofOfResidence' => 'Proof of Residence',
        'proofOfRegistration' => 'Proof of Registration / Application',
    ];

    foreach ($documentFields as $fieldKey => $fieldName) {
        $fileName = handleFileUpload($fieldKey, $uploadDir, $validationErrors);
        if ($fileName) {
            $uploadedFiles[$fieldKey] = $fileName;
        } else {
             // If errors occurred, they were added inside handleFileUpload. 
             // We still check if a required file is missing (no file selected or moved).
            if(!isset($_FILES[$fieldKey]) || $_FILES[$fieldKey]['error'] == UPLOAD_ERR_NO_FILE) {
                 $validationErrors[] = "$fieldName upload is required.";
            }
        }
    }

    // 4. Final Submission Logic
    if (empty($validationErrors)) {
        $applicationID = uniqid("APP_");
        $newAdmission = [
            "applicationID" => $applicationID,
            "status" => "pending",
            "timestamp" => date("Y-m-d H:i:s"),
            "child" => [
                "fullName" => $childFullName,
                "dateOfBirth" => $dob,
                "gender" => $gender,
                "gradeApplyingFor" => $gradeApplyingFor,
                "residentialAddress" => $childResidentialAddress,
                "previousSchool" => $previousSchool,
                "ageInYears" => number_format($ageInYears, 2),
            ],
            "parent" => [
                "fullName" => $parentFullName,
                "relationshipToChild" => $relationshipToChild,
                "emailAddress" => $emailAddress,
                "phoneNumber" => $phoneNumber,
                "residentialAddress" => $parentResidentialAddress,
            ],
            "documents" => $uploadedFiles,
        ];

        // Append to existing array
        $admissions[] = $newAdmission;

        // Save data to JSON file
        if (file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT))) {
            $success = "✅ Application submitted successfully! Your Application ID is **{$applicationID}**.";
            $submissionSummary = $newAdmission;
            // Clear POST to prevent resubmission on refresh
            $_POST = array(); 
        } else {
            $error = "⚠ Unable to save application data. Please try again.";
        }
    } else {
        $error = "❌ Submission failed due to " . count($validationErrors) . " error(s). Please review the form.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SCHOOL ADMISSION FORM</title>
    <style>
        /* Minimalist styles for structure - you should use a separate CSS file */
        body { font-family: Arial, sans-serif; background: #f9f9f9; color: #333; margin: 0; }
        main { max-width: 800px; margin: 30px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1, h2, h3 { text-align: center; color: #007bff; }
        .section { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="tel"], input[type="date"], select, textarea { 
            width: 100%; padding: 10px; margin: 5px 0 10px 0; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; 
        }
        button[type="submit"] { background: #28a745; color: white; font-weight: bold; border: none; padding: 15px; cursor: pointer; width: 100%; border-radius: 5px; }
        button[type="submit"]:disabled { background: #6c757d; cursor: not-allowed; }
        .error-message { color: red; font-size: 0.9em; margin-top: -10px; margin-bottom: 10px; }
        .message.success { color: green; font-weight: bold; }
        .message.error { color: red; font-weight: bold; }
        .validation-errors { color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .summary-box { border: 2px solid #007bff; padding: 20px; margin-top: 20px; border-radius: 8px; background: #e9f5ff; }
        .summary-box p { margin: 5px 0; }
        .file-preview { margin-top: 10px; max-width: 100%; height: auto; border: 1px solid #ccc; }
        .file-upload-block { border: 1px dashed #ccc; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <main>
        <h1>SCHOOL ADMISSION FORM</h1>
        <hr>

        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
            <?php if ($submissionSummary): ?>
                <div class="summary-box">
                    <h2>Application Submission Summary (ID: <?= $submissionSummary['applicationID'] ?>)</h2>
                    
                    <h3>Child Details</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($submissionSummary['child']['fullName']) ?></p>
                    <p><strong>DOB:</strong> <?= htmlspecialchars($submissionSummary['child']['dateOfBirth']) ?> (Age: <?= htmlspecialchars($submissionSummary['child']['ageInYears']) ?> years)</p>
                    <p><strong>Grade:</strong> <?= htmlspecialchars($submissionSummary['child']['gradeApplyingFor']) ?></p>

                    <h3>Parent Details</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($submissionSummary['parent']['fullName']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($submissionSummary['parent']['emailAddress']) ?></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($submissionSummary['parent']['phoneNumber']) ?></p>

                    <h3>Documents Uploaded</h3>
                    <ul>
                        <?php foreach($submissionSummary['documents'] as $key => $filename): ?>
                            <li><?= htmlspecialchars($documentFields[$key] ?? $key) ?>: **<?= htmlspecialchars($filename) ?>**</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
            <?php if (!empty($validationErrors)): ?>
                <div class="validation-errors">
                    <p>Please fix the following errors:</p>
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
                <h2>SECTION 1: CHILD DETAILS</h2>
                <hr>

                <div class="form-group">
                    <label for="childFullName">Child Full Name *</label>
                    <input type="text" id="childFullName" name="childFullName" required 
                           value="<?= htmlspecialchars($_POST['childFullName'] ?? '') ?>" oninput="validateField(this)">
                    <div id="error-childFullName" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth *</label>
                    <input type="date" id="dob" name="dob" required 
                           value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>" onchange="validateDOB(this); validateGradeCategory()">
                    <div id="error-dob" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label>Gender *</label>
                    <div>
                        <input type="radio" id="genderMale" name="gender" value="Male" required onclick="validateField(this)"> <label for="genderMale" style="display:inline;">Male</label>
                        <input type="radio" id="genderFemale" name="gender" value="Female" onclick="validateField(this)"> <label for="genderFemale" style="display:inline;">Female</label>
                        <input type="radio" id="genderOther" name="gender" value="Other" onclick="validateField(this)"> <label for="genderOther" style="display:inline;">Other</label>
                    </div>
                    <div id="error-gender" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="gradeApplyingFor">Grade Applying For *</label>
                    <select id="gradeApplyingFor" name="gradeApplyingFor" required 
                            onchange="validateGradeCategory()">
                        <option value="Select Category">⬇ Select Category *</option>
                        <?php foreach ($gradeCategories as $key => $details): ?>
                            <option value="<?= $key ?>" 
                                    data-min="<?= $details['min'] ?>" 
                                    data-max="<?= $details['max'] ?>"
                                    <?= (($_POST['gradeApplyingFor'] ?? '') === $key) ? 'selected' : '' ?>
                                    ><?= $details['label'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="error-gradeApplyingFor" class="error-message"></div>
                    <p style="font-size: 0.9em; color: #555;">* Grade category will be disabled if age mismatch occurs.</p>
                </div>

                <div class="form-group">
                    <label for="childResidentialAddress">Residential Address *</label>
                    <textarea id="childResidentialAddress" name="childResidentialAddress" required oninput="validateField(this)"><?= htmlspecialchars($_POST['childResidentialAddress'] ?? '') ?></textarea>
                    <div id="error-childResidentialAddress" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="previousSchool">Previous School (Optional)</label>
                    <input type="text" id="previousSchool" name="previousSchool" 
                           value="<?= htmlspecialchars($_POST['previousSchool'] ?? '') ?>">
                </div>
            </div>

            <div class="section">
                <h2>SECTION 2: PARENT / GUARDIAN DETAILS</h2>
                <hr>

                <div class="form-group">
                    <label for="parentFullName">Parent Full Name *</label>
                    <input type="text" id="parentFullName" name="parentFullName" required 
                           value="<?= htmlspecialchars($_POST['parentFullName'] ?? '') ?>" oninput="validateField(this)">
                    <div id="error-parentFullName" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="relationshipToChild">Relationship to Child *</label>
                    <input type="text" id="relationshipToChild" name="relationshipToChild" required 
                           value="<?= htmlspecialchars($_POST['relationshipToChild'] ?? '') ?>" oninput="validateField(this)">
                    <div id="error-relationshipToChild" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="emailAddress">Email Address *</label>
                    <input type="email" id="emailAddress" name="emailAddress" required 
                           value="<?= htmlspecialchars($_POST['emailAddress'] ?? '') ?>" oninput="validateEmail(this)">
                    <div id="error-emailAddress" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="phoneNumber">Phone Number *</label>
                    <input type="tel" id="phoneNumber" name="phoneNumber" required 
                           value="<?= htmlspecialchars($_POST['phoneNumber'] ?? '') ?>" oninput="validatePhone(this)">
                    <div id="error-phoneNumber" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="parentResidentialAddress">Home Address *</label>
                    <textarea id="parentResidentialAddress" name="parentResidentialAddress" required oninput="validateField(this)"><?= htmlspecialchars($_POST['parentResidentialAddress'] ?? '') ?></textarea>
                    <div id="error-parentResidentialAddress" class="error-message"></div>
                </div>
            </div>

            <div class="section">
                <h2>SECTION 3: UPLOAD SUPPORTING DOCUMENTS</h2>
                <hr>
                <p style="font-size: 0.9em; color: #555;">Allowed formats: **PDF, JPG, PNG**. Max size: **2MB** per file. Image files will show a preview.</p>

                <div class="form-group file-upload-block">
                    <label for="childIDBirthCertificate">Child ID / Birth Certificate *</label>
                    <input type="file" id="childIDBirthCertificate" name="childIDBirthCertificate" required 
                           accept=".pdf, .jpg, .jpeg, .png" onchange="validateFile(this)">
                    <div id="error-childIDBirthCertificate" class="error-message"></div>
                    <img id="preview-childIDBirthCertificate" class="file-preview" style="display:none;">
                </div>

                <div class="form-group file-upload-block">
                    <label for="parentID">Parent ID *</label>
                    <input type="file" id="parentID" name="parentID" required 
                           accept=".pdf, .jpg, .jpeg, .png" onchange="validateFile(this)">
                    <div id="error-parentID" class="error-message"></div>
                    <img id="preview-parentID" class="file-preview" style="display:none;">
                </div>

                <div class="form-group file-upload-block">
                    <label for="proofOfResidence">Proof of Residence *</label>
                    <input type="file" id="proofOfResidence" name="proofOfResidence" required 
                           accept=".pdf, .jpg, .jpeg, .png" onchange="validateFile(this)">
                    <div id="error-proofOfResidence" class="error-message"></div>
                    <img id="preview-proofOfResidence" class="file-preview" style="display:none;">
                </div>
                
                 <div class="form-group file-upload-block">
                    <label for="proofOfRegistration">Proof of Registration / Application *</label>
                    <input type="file" id="proofOfRegistration" name="proofOfRegistration" required 
                           accept=".pdf, .jpg, .jpeg, .png" onchange="validateFile(this)">
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
        const REQUIRED_FIELDS = [
            'childFullName', 'dob', 'gender', 'gradeApplyingFor', 'childResidentialAddress', 
            'parentFullName', 'relationshipToChild', 'emailAddress', 'phoneNumber', 'parentResidentialAddress',
            'childIDBirthCertificate', 'parentID', 'proofOfResidence', 'proofOfRegistration'
        ];

        document.addEventListener('DOMContentLoaded', () => {
            // Initial check on load
            checkFormValidity();
            // Attach event listeners for initial validation
            document.getElementById('admissionForm').addEventListener('input', checkFormValidity);
            document.getElementById('admissionForm').addEventListener('change', checkFormValidity);
        });

        function calculateAgeInYears(dobString) {
            const birthDate = new Date(dobString);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            // Add fractional part for months/days
            return age + (m + (today.getDate() - birthDate.getDate()) / 30.44) / 12;
        }
        
        // --- Real-Time Validation Functions ---
        function validateField(input) {
            const errorElement = document.getElementById(`error-${input.id || input.name}`);
            if (input.value.trim() === '' || (input.type === 'radio' && !document.querySelector(`input[name="${input.name}"]:checked`))) {
                errorElement.textContent = `${input.placeholder || input.name.replace(/([A-Z])/g, ' $1').trim()} is required.`;
            } else {
                errorElement.textContent = '';
            }
            return errorElement.textContent === '';
        }

        function validateEmail(input) {
            const isValid = validateField(input);
            const errorElement = document.getElementById('error-emailAddress');
            if (isValid && !input.value.includes('@') || !input.value.includes('.')) {
                errorElement.textContent = 'Email must include "@" and a valid domain.';
                return false;
            }
            return errorElement.textContent === '';
        }

        function validatePhone(input) {
            const isValid = validateField(input);
            const errorElement = document.getElementById('error-phoneNumber');
            // Basic pattern: allows numbers, spaces, +, -, ()
            const phonePattern = /^[0-9\s\-\+\(\)]+$/;
            if (isValid && !phonePattern.test(input.value)) {
                errorElement.textContent = 'Phone number must be numeric and properly formatted.';
                return false;
            }
            return errorElement.textContent === '';
        }

        function validateDOB(input) {
            const isValid = validateField(input);
            const errorElement = document.getElementById('error-dob');
            if (isValid && new Date(input.value) > new Date()) {
                errorElement.textContent = 'Date of birth cannot be a future date.';
                return false;
            }
            return errorElement.textContent === '';
        }

        function validateGradeCategory() {
            const dobInput = document.getElementById('dob');
            const gradeSelect = document.getElementById('gradeApplyingFor');
            const errorElement = document.getElementById('error-gradeApplyingFor');
            
            // Clear existing error and disable states
            errorElement.textContent = '';
            gradeSelect.querySelectorAll('option').forEach(opt => {
                if (opt.value !== 'Select Category') {
                    opt.disabled = false;
                }
            });

            if (dobInput.value) {
                const ageInYears = calculateAgeInYears(dobInput.value);
                let selectedCategoryMatches = true;

                // 1. Disable categories that don't match the age
                gradeSelect.querySelectorAll('option').forEach(opt => {
                    if (opt.value !== 'Select Category') {
                        const min = parseFloat(opt.dataset.min);
                        const max = parseFloat(opt.dataset.max);
                        
                        // Use a small tolerance for floating point comparison
                        if (ageInYears < min || ageInYears >= max + 0.0001) { 
                            opt.disabled = true;
                        }

                        // 2. Check if the currently selected category is valid
                        if (opt.selected && opt.value !== 'Select Category' && opt.disabled) {
                            errorElement.textContent = "Selected category does not match child’s age.";
                            selectedCategoryMatches = false;
                        }
                    }
                });
                
                if (gradeSelect.value !== 'Select Category' && !selectedCategoryMatches) {
                    // Force reset if invalid category was pre-selected
                    gradeSelect.value = 'Select Category'; 
                }
                
                return errorElement.textContent === '';
            } else {
                if(gradeSelect.value !== 'Select Category') {
                    errorElement.textContent = 'Please select a Date of Birth first to validate the Grade Category.';
                    return false;
                }
            }
            
            return gradeSelect.value !== 'Select Category';
        }

        function validateFile(input) {
            const errorElement = document.getElementById(`error-${input.id}`);
            const previewImage = document.getElementById(`preview-${input.id}`);
            errorElement.textContent = '';
            previewImage.style.display = 'none';
            previewImage.src = '';

            if (!input.files.length) {
                // Not strictly an error unless required, but good for real-time validation
                errorElement.textContent = `${input.id.replace(/([A-Z])/g, ' $1').trim()} is required.`;
                return false;
            }

            const file = input.files[0];

            // Size check
            if (file.size > MAX_FILE_SIZE) {
                errorElement.textContent = `File size (${(file.size / (1024 * 1024)).toFixed(2)}MB) exceeds 2MB limit.`;
                input.value = ''; // Clear file input
                return false;
            }

            // Type check (Client-side MIME type check - less secure than server, but provides quick feedback)
            if (!ALLOWED_MIME_TYPES.includes(file.type)) {
                errorElement.textContent = `Invalid file type: ${file.type}. Only PDF, JPG, PNG allowed.`;
                input.value = ''; // Clear file input
                return false;
            }
            
            // Image Preview
            if (file.type.startsWith('image/')) {
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

            // 1. Validate all required input fields
            REQUIRED_FIELDS.forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    let isValid;
                    if (input.type === 'email') {
                        isValid = validateEmail(input);
                    } else if (input.type === 'tel') {
                        isValid = validatePhone(input);
                    } else if (input.type === 'date') {
                        isValid = validateDOB(input);
                    } else if (input.type === 'select-one') {
                         isValid = validateGradeCategory();
                    } else if (input.type === 'file') {
                        // File check: just ensure file is selected and passes client-side validation
                        if(input.files.length === 0) {
                            document.getElementById(`error-${input.id}`).textContent = `${input.id.replace(/([A-Z])/g, ' $1').trim()} is required.`;
                            isValid = false;
                        } else {
                           isValid = validateFile(input);
                        }
                    } else if (input.type === 'radio') {
                         // Separate logic for radio buttons
                         isValid = document.querySelector(`input[name="${input.name}"]:checked`) !== null;
                         if (!isValid) {
                            document.getElementById(`error-${input.name}`).textContent = `Gender is required.`;
                         } else {
                            document.getElementById(`error-${input.name}`).textContent = ``;
                         }
                    } else {
                        isValid = validateField(input);
                    }

                    if (!isValid) {
                        allValid = false;
                        const errorElement = document.getElementById(`error-${input.id || input.name}`);
                        if (errorElement && errorElement.textContent) {
                            totalErrors.push(errorElement.textContent);
                        }
                    }
                }
            });

            // 2. Manage Submit Button and Global Error Display
            const submitBtn = document.getElementById('submitBtn');
            const errorDisplay = document.getElementById('validation-errors-display');
            
            if (allValid) {
                submitBtn.disabled = false;
                errorDisplay.style.display = 'none';
                errorDisplay.innerHTML = '';
            } else {
                submitBtn.disabled = true;
                if (totalErrors.length > 0) {
                    errorDisplay.style.display = 'block';
                    errorDisplay.innerHTML = '<p>Please fix the following errors:</p><ul>' + totalErrors.map(err => `<li>${err}</li>`).join('') + '</ul>';
                } else {
                    errorDisplay.style.display = 'none';
                    errorDisplay.innerHTML = '';
                }
            }
        }
    </script>
</body>
</html>
