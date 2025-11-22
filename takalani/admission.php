<?php
// Set up error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session to potentially retrieve logged-in user data
session_start();

// Define the file path for JSON storage and file upload directory
$admissionFile = __DIR__ . '/admissions.json';
$uploadDir = __DIR__ . '/uploads/';

// Ensure the upload directory exists
if (!is_dir($uploadDir)) {
    // Attempt to create the directory with full permissions recursively
    if (!mkdir($uploadDir, 0777, true)) {
        // Fallback or error handling if directory creation fails
        die("Fatal Error: Failed to create upload directory.");
    }
}

// Load existing admissions data
$admissions = file_exists($admissionFile)
    ? json_decode(file_get_contents($admissionFile), true)
    : [];

$success = "";
$error = "";
$submissionSummary = null; // To store and display the summary after successful submission

// Define Grade Categories (Age Ranges for validation)
$gradeCategories = [
    // Age in years: 6/12 = 0.5, 12/12 = 1.0, etc.
    'Infants' => ['min' => 0.5, 'max' => 1, 'label' => 'Infants (6-12 months)'],
    'Toddlers' => ['min' => 1, 'max' => 3, 'label' => 'Toddlers (1-3 years)'],
    'Playgroup' => ['min' => 3, 'max' => 4, 'label' => 'Playgroup (3-4 years)'],
    'Pre-School' => ['min' => 4, 'max' => 5, 'label' => 'Pre-School (4-5 years)'],
];

// PHP Age Calculation Function (returns age in years with fraction)
function calculateAge($dob) {
    if (empty($dob)) return 0;
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $interval = $today->diff($birthDate);
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
        return false;
    }

    $file = $_FILES[$fileInputName];

    // REQUIRED FILE CHECK (Returns false if no file selected)
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
         return false;
    }

    // Check for other upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "File upload error for $fileInputName: Code " . $file['error'];
        return false;
    }

    // Validation: Max size 2MB
    $maxSize = 2 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        $errors[] = "$fileInputName exceeds the maximum size of 2MB.";
        return false;
    }

    // Validation: Allowed formats (PDF, JPG, PNG)
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    // Use mime_content_type for reliable check
    $fileType = (function_exists('mime_content_type')) ? mime_content_type($file['tmp_name']) : $file['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
         $errors[] = "$fileInputName has an invalid file type ('$fileType'). Only PDF, JPG, or PNG are allowed.";
        return false;
    }
    
    // Generate a unique filename and move the file
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
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
$documentFields = [
    'childIDBirthCertificate' => 'Child ID / Birth Certificate',
    'parentID' => 'Parent ID',
    'proofOfResidence' => 'Proof of Residence',
    'proofOfRegistration' => 'Proof of Registration / Application',
];

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
    
    // Validation: DOB cannot be a future date
    if (!empty($dob) && strtotime($dob) > time()) {
        $validationErrors[] = "Date of Birth cannot be a future date.";
    }

    // Validation: Grade Category Validation (Age matching)
    $ageInYears = 0;
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

    // Validation: Email format
    if (!empty($emailAddress) && !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        $validationErrors[] = "Email Address must be a valid format.";
    }

    // Validation: Phone number format
    if (!empty($phoneNumber) && !preg_match('/^[0-9\s\-\+\(\)]+$/', $phoneNumber)) { // Allows numbers and common formatting symbols
        $validationErrors[] = "Phone Number must be numeric and properly formatted.";
    }

    // 3. Supporting Documents Upload and Validation
    $uploadedFiles = [];
    
    foreach ($documentFields as $fieldKey => $fieldName) {
        $fileName = handleFileUpload($fieldKey, $uploadDir, $validationErrors);
        if ($fileName) {
            $uploadedFiles[$fieldKey] = $fileName;
        } else {
             // Check if a required file is missing (no file selected or moved).
            if(!isset($_FILES[$fieldKey]) || $_FILES[$fieldKey]['error'] == UPLOAD_ERR_NO_FILE) {
                // Ensure we only mark files as required if there are no other validation errors yet (to prioritize specific file errors)
                if (empty(array_filter($validationErrors, function($e) use ($fieldName) { return strpos($e, $fieldName) !== false; }))) {
                    $validationErrors[] = "$fieldName upload is required.";
                }
            }
        }
    }

    // 4. Final Submission Logic
    if (empty($validationErrors)) {
        $applicationID = uniqid("APP_");
        $newAdmission = [
            "applicationID" => $applicationID,
            "status" => "pending", // Default status
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

        // Append to existing array and save
        $admissions[] = $newAdmission;

        if (file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT))) {
            $success = "✅ Application submitted successfully! Your Application ID is **{$applicationID}**.";
            $submissionSummary = $newAdmission;
            // Clear POST data to prevent resubmission on refresh
            $_POST = array(); 
        } else {
            // Cleanup uploaded files if JSON save failed
            foreach ($uploadedFiles as $filename) {
                @unlink($uploadDir . $filename);
            }
            $error = "⚠ Unable to save application data. Please try again.";
        }
    } else {
        $error = "❌ Submission failed due to " . count($validationErrors) . " error(s)". (count($validationErrors) > 0 ? ": <br><ul><li>".implode("</li><li>", $validationErrors)."</li></ul>" : "");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SCHOOL ADMISSION FORM</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    
    <?php require_once 'menu-bar.php'; ?> 

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
            <?php if (strpos($error, '<ul>') === false && !empty($validationErrors)): ?> 
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
                               value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>" onchange="validateDOB(this); validateGradeCategory(); checkFormValidity();">
                    <div id="error-dob" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label>Gender *</label>
                    <div class="radio-group">
                        <input type="radio" id="genderMale" name="gender" value="Male" required <?= (($_POST['gender'] ?? '') === 'Male') ? 'checked' : '' ?> onclick="validateField(this); checkFormValidity()"> <label for="genderMale" style="display:inline;">Male</label>
                        <input type="radio" id="genderFemale" name="gender" value="Female" <?= (($_POST['gender'] ?? '') === 'Female') ? 'checked' : '' ?> onclick="validateField(this); checkFormValidity()"> <label for="genderFemale" style="display:inline;">Female</label>
                        <input type="radio" id="genderOther" name="gender" value="Other" <?= (($_POST['gender'] ?? '') === 'Other') ? 'checked' : '' ?> onclick="validateField(this); checkFormValidity()"> <label for="genderOther" style="display:inline;">Other</label>
                    </div>
                    <div id="error-gender" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="gradeApplyingFor">Grade Applying For *</label>
                    <select id="gradeApplyingFor" name="gradeApplyingFor" required 
                                onchange="validateGradeCategory(); checkFormValidity()">
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

    <?php include 'footer.php'; ?>

    <script>
        // JS CONSTANTS (Mirrored from PHP for client-side speed)
        const GRADE_CATEGORIES = <?= json_encode($gradeCategories) ?>;
        const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
        const ALLOWED_MIME_TYPES = ['application/pdf', 'image/jpeg', 'image/png'];
        const REQUIRED_FIELDS = [
            'childFullName', 'dob', 'gender', 'gradeApplyingFor', 'childResidentialAddress', 
            'parentFullName', 'relationshipToChild', 'emailAddress', 'phoneNumber', 'parentResidentialAddress',
            'childIDBirthCertificate', 'parentID', 'proofOfResidence', 'proofOfRegistration'
        ];

        document.addEventListener('DOMContentLoaded', () => {
            checkFormValidity();
            document.getElementById('admissionForm').addEventListener('input', checkFormValidity);
            document.getElementById('admissionForm').addEventListener('change', checkFormValidity);
            
            // Re-attach listeners for radio/files
            document.getElementsByName('gender').forEach(radio => {
                radio.addEventListener('change', checkFormValidity);
            });
            document.querySelectorAll('input[type="file"]').forEach(fileInput => {
                fileInput.addEventListener('change', checkFormValidity);
            });
        });

        function calculateAgeInYears(dobString) {
            const birthDate = new Date(dobString);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            return age + (m + (today.getDate() - birthDate.getDate()) / 30.44) / 12; // Approx. months/days fraction
        }
        
        // --- Real-Time Validation Functions ---
        function validateField(input) {
            const errorElement = document.getElementById(`error-${input.id || input.name}`);
            let isChecked = true;

            if (input.type === 'radio') {
                isChecked = document.querySelector(`input[name="${input.name}"]:checked`);
            }
            
            if (input.required && (input.value.trim() === '' || !isChecked)) {
                errorElement.textContent = `This field is required.`;
                return false;
            } else {
                errorElement.textContent = '';
            }
            return errorElement.textContent === '';
        }

        function validateEmail(input) {
            const isValid = validateField(input);
            const errorElement = document.getElementById('error-emailAddress');
            if (isValid && (!input.value.includes('@') || !input.value.includes('.'))) {
                errorElement.textContent = 'Email must include "@" and a valid domain.';
                return false;
            }
            return errorElement.textContent === '';
        }

        function validatePhone(input) {
            const isValid = validateField(input);
            const errorElement = document.getElementById('error-phoneNumber');
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
            
            errorElement.textContent = '';
            gradeSelect.querySelectorAll('option').forEach(opt => {
                if (opt.value !== 'Select Category') {
                    opt.disabled = false;
                }
            });

            if (dobInput.value) {
                const ageInYears = calculateAgeInYears(dobInput.value);
                let selectedCategoryMatches = true;

                // Disable categories that don't match the age
                gradeSelect.querySelectorAll('option').forEach(opt => {
                    if (opt.value !== 'Select Category') {
                        const min = parseFloat(opt.dataset.min);
                        const max = parseFloat(opt.dataset.max);
                        
                        if (ageInYears < min - 0.0001 || ageInYears >= max + 0.0001) { 
                            opt.disabled = true;
                        }

                        // Check if the currently selected category is invalid
                        if (opt.selected && opt.value !== 'Select Category' && opt.disabled) {
                            errorElement.textContent = "Selected category does not match child’s age.";
                            selectedCategoryMatches = false;
                        }
                    }
                });

                if (gradeSelect.value !== 'Select Category' && !selectedCategoryMatches) {
                    gradeSelect.value = 'Select Category'; 
                    errorElement.textContent = "The previously selected grade category was invalid for the child's age and has been reset. Please choose a new one.";
                    return false;
                }
                
                // If DOB is valid but no category is selected
                if (gradeSelect.value === 'Select Category') {
                    errorElement.textContent = 'Grade Applying For is required.';
                    return false;
                }
                
                return errorElement.textContent === '';
            } else {
                if(gradeSelect.value !== 'Select Category') {
                    errorElement.textContent = 'Please select a Date of Birth first.';
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

            if (input.required && !input.files.length) {
                errorElement.textContent = `${input.id.replace(/([A-Z])/g, ' $1').trim()} is required.`;
                return false;
            }

            if (!input.files.length) return true; // File is not required or no file selected

            const file = input.files[0];

            // Size check
            if (file.size > MAX_FILE_SIZE) {
                errorElement.textContent = `File size (${(file.size / (1024 * 1024)).toFixed(2)}MB) exceeds 2MB limit.`;
                input.value = '';
                return false;
            }

            // Type check 
            if (file.type && !ALLOWED_MIME_TYPES.includes(file.type)) {
                errorElement.textContent = `Invalid file type: ${file.type}. Only PDF, JPG, PNG allowed.`;
                input.value = '';
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
                         isValid = validateFile(input);
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
                } else if (id === 'gender') {
                    const genderRadio = document.querySelector('input[name="gender"]:checked');
                    isValid = genderRadio !== null;
                    const errorElement = document.getElementById(`error-gender`);
                    if (!isValid) {
                        errorElement.textContent = `Gender is required.`;
                        allValid = false;
                        totalErrors.push(errorElement.textContent);
                    } else {
                        errorElement.textContent = ``;
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
                    const uniqueErrors = [...new Set(totalErrors)];
                    errorDisplay.style.display = 'block';
                    errorDisplay.innerHTML = `<p><strong>${uniqueErrors.length} required fields need attention:</strong></p><ul>` + uniqueErrors.map(err => `<li>${err}</li>`).join('') + '</ul>';
                } else {
                    errorDisplay.style.display = 'none';
                    errorDisplay.innerHTML = '';
                }
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
             validateDOB(document.getElementById('dob'));
             validateGradeCategory();
             checkFormValidity();
        });
        
    </script>
</body>
</html>