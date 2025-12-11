<?php
// Set up error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -----------------------------------------------------------------------------
// FIX: COMMENTED OUT AUTHORIZATION CHECK TO ALLOW PUBLIC VIEWING
/*
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'parent') {
    header("Location: login.php");
    exit();
}
*/
// -----------------------------------------------------------------------------

// Define the file path for JSON storage and file upload directory
$admissionFile = __DIR__ . '/../data/admissions.json';
$uploadDir = __DIR__ . '/../data/uploads/'; 

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
$nextChildIndex = 0; 

// Function to generate a unique ID for the application
function generateUniqueId($admissions) {
    do {
        // Generate a 12-character alphanumeric ID
        $id = substr(md5(uniqid(rand(), true)), 0, 12);
    } while (array_key_exists($id, array_column($admissions, 'applicationID', 'applicationID')));
    return $id;
}

// Function to handle file uploads
function handleUpload($file, $uploadDir, $prefix) {
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        // Use a unique name to prevent collisions
        $newFileName = $prefix . '_' . uniqid() . '.' . $fileExt;
        $targetPath = $uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $newFileName; // Return just the file name
        } else {
            return false; // Move failed
        }
    }
    return null; // No file uploaded or upload error
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Parent Data (pre-filled from session, but submitted here for clarity)
    $parentData = [
        // NOTE: This will only work if the user is logged in. 
        // If the user is NOT logged in, $_SESSION['user'] will be null, and 
        // this will cause issues when saving the application, but I'm 
        // keeping this logic as requested (no other changes).
        'parentName'        => $_SESSION['user']['parentName'] ?? 'Guest',
        'parentSurname'     => $_SESSION['user']['parentSurname'] ?? 'User',
        'email'             => $_SESSION['user']['email'] ?? trim($_POST['email'] ?? 'guest@example.com'),
        'contact'           => $_SESSION['user']['phone'] ?? trim($_POST['contact'] ?? 'N/A'),
        // The rest of the required parent data from the form
        'parentIDNumber'    => trim($_POST['parentIDNumber'] ?? ''),
        'parentAddress'     => trim($_POST['parentAddress'] ?? '')
    ];

    // --- File Upload: Parent ID Document ---
    $parentDocName = null;
    if (isset($_FILES['parentIdDocument']) && $_FILES['parentIdDocument']['error'] !== UPLOAD_ERR_NO_FILE) {
        $parentDocName = handleUpload($_FILES['parentIdDocument'], $uploadDir, 'parent');
        if ($parentDocName === false) {
            $error = "Failed to upload Parent ID Document. Check directory permissions.";
        }
    } else {
        $error = "Parent ID Document is required.";
    }

    // --- Children Data Collection ---
    $childrenData = [];
    $numChildren = count($_POST['childName'] ?? []);

    for ($i = 0; $i < $numChildren; $i++) {
        $childName = trim($_POST['childName'][$i] ?? '');
        $childSurname = trim($_POST['childSurname'][$i] ?? '');
        
        $childDocName = null;
        if (isset($_FILES['childIdDocument']['tmp_name'][$i]) && $_FILES['childIdDocument']['error'][$i] === UPLOAD_ERR_OK) {
            $childFile = [
                'name'     => $_FILES['childIdDocument']['name'][$i],
                'type'     => $_FILES['childIdDocument']['type'][$i],
                'tmp_name' => $_FILES['childIdDocument']['tmp_name'][$i],
                'error'    => $_FILES['childIdDocument']['error'][$i],
                'size'     => $_FILES['childIdDocument']['size'][$i]
            ];
            $childDocName = handleUpload($childFile, $uploadDir, 'child');
        }

        if (empty($childName) || empty($childSurname) || $childDocName === null) {
             // Skip invalid/incomplete child entries, or set an error
             if (empty($error)) {
                 $error = "Please ensure all child name fields and documents are completed.";
             }
             continue; // Skip this child if data is incomplete
        }

        $childrenData[] = [
            'childFirstName'    => $childName,
            'childSurname'      => $childSurname,
            'childAge'          => trim($_POST['childAge'][$i] ?? ''),
            'childGender'       => trim($_POST['childGender'][$i] ?? ''),
            'gradeApplyingFor'  => trim($_POST['gradeApplyingFor'][$i] ?? ''),
            'childIdDocument'   => $childDocName,
            'medicalNotes'      => trim($_POST['medicalNotes'][$i] ?? ''),
            'status'            => 'Pending' // Initial status
        ];
    }
    
    // --- Final Submission ---
    if (empty($error) && !empty($childrenData)) {
        
        $newAdmissions = [];
        foreach ($childrenData as $childAdmission) {
            $newAdmission = array_merge([
                'applicationID'         => generateUniqueId($admissions),
                'timestamp'             => date('Y-m-d H:i:s'),
                'parentUsername'        => $_SESSION['user']['username'] ?? 'guest_submission', // Uses guest username if not logged in
                'parentIDDocument'      => $parentDocName,
            ], $parentData, $childAdmission);

            $admissions[] = $newAdmission;
            $newAdmissions[] = $newAdmission; // For summary
        }

        if (file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT))) {
            $success = "Application(s) submitted successfully!";
            $submissionSummary = $newAdmissions; // Use for confirmation message
            // Reset POST data to clear the form fields on success
            $_POST = [];
            $nextChildIndex = 0; // Reset counter for empty form
        } else {
            $error = "Failed to save data. Check file permissions for: " . $admissionFile;
        }

    } elseif (empty($error) && empty($childrenData)) {
        $error = "No children data was submitted. Please ensure all required fields are filled.";
    }

}

// Determine the index for dynamically generating child sections in HTML
$nextChildIndex = count($_POST['childName'] ?? [0]); 
if ($nextChildIndex === 0 && empty($submissionSummary)) {
    $nextChildIndex = 1; // Start with one blank form if no previous data
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Application</title>
    <link rel="stylesheet" href="/public/css/styles.css">
    <style>
        /* CSS for the dynamically added child sections */
        .child-section {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .remove-child-btn {
            float: right;
            background: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .file-upload-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php 
// Include menu-bar
require_once "../app/menu-bar.php"; 
?>

<main>
    <h2 style="text-align:center;">Admission Application</h2>
    <p style="text-align:center;">**Note:** Fields marked with an asterisk (*) are required.</p>
    
    <?php if ($error): ?>
        <p style="color:red; text-align:center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green; text-align:center; font-weight:bold;"><?= htmlspecialchars($success) ?></p>
        
        <?php if ($submissionSummary): ?>
            <div style="border:1px solid #ddd; padding:15px; margin:20px auto; max-width:600px; text-align:left; background:#e9fbe9;">
                <h3>Application Summary:</h3>
                <?php foreach ($submissionSummary as $app): ?>
                    <p>
                        ✅ Child: <strong><?= htmlspecialchars($app['childFirstName'] . ' ' . $app['childSurname']) ?></strong> 
                        (Grade <?= htmlspecialchars($app['gradeApplyingFor']) ?>). 
                        Application ID: <?= htmlspecialchars($app['applicationID']) ?>
                    </p>
                <?php endforeach; ?>
                <p style="margin-top:10px;">You can track the status of your application(s) by logging into your 
                    <a href="login.php" style="color:blue;">Guardian Dashboard</a>.
                </p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form id="admissionForm" method="POST" enctype="multipart/form-data" style="max-width:700px; margin:auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        
        <h3 style="border-bottom: 1px solid #ddd; padding-bottom: 5px;">Guardian Details</h3>
        
        <p>
            **Note:** If you are logged in, Name, Surname, Email, and Contact are pre-filled from your registration.
        </p>
        
        <label for="parentIDNumber">Guardian ID/Passport Number: *</label><br>
        <input type="text" id="parentIDNumber" name="parentIDNumber" required 
               value="<?= htmlspecialchars($_POST['parentIDNumber'] ?? '') ?>" /><br /><br />
        
        <label for="parentAddress">Current Residential Address: *</label><br>
        <input type="text" id="parentAddress" name="parentAddress" required 
               value="<?= htmlspecialchars($_POST['parentAddress'] ?? '') ?>" /><br /><br />

        <label for="parentIdDocument" class="file-upload-label">Upload Guardian ID/Passport Document (PDF/JPEG): *</label>
        <input type="file" id="parentIdDocument" name="parentIdDocument" accept=".pdf,.jpg,.jpeg" required /><br /><br />

        <h3 style="border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-top: 30px;">Child/Children Details</h3>
        
        <div id="childrenContainer">
            <?php for ($i = 0; $i < $nextChildIndex; $i++): ?>
                <div class="child-section" id="childSection_<?= $i ?>">
                    <h4>Child <?= $i + 1 ?></h4>
                    <?php if ($nextChildIndex > 1): ?>
                        <button type="button" class="remove-child-btn" data-index="<?= $i ?>">Remove</button>
                    <?php endif; ?>
                    
                    <label for="childName_<?= $i ?>">Child First Name: *</label><br>
                    <input type="text" id="childName_<?= $i ?>" name="childName[]" required 
                           value="<?= htmlspecialchars($_POST['childName'][$i] ?? '') ?>" /><br /><br />

                    <label for="childSurname_<?= $i ?>">Child Surname: *</label><br>
                    <input type="text" id="childSurname_<?= $i ?>" name="childSurname[]" required 
                           value="<?= htmlspecialchars($_POST['childSurname'][$i] ?? '') ?>" /><br /><br />
                           
                    <label for="childAge_<?= $i ?>">Age (Years): *</label><br />
                    <input type="number" id="childAge_<?= $i ?>" name="childAge[]" min="0" max="10" required 
                           value="<?= htmlspecialchars($_POST['childAge'][$i] ?? '') ?>" /><br /><br />
                           
                    <label for="childGender_<?= $i ?>">Gender: *</label><br />
                    <select id="childGender_<?= $i ?>" name="childGender[]" required>
                        <option value="">-- Select Gender --</option>
                        <option value="Male" <?= (($_POST['childGender'][$i] ?? '') === 'Male') ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= (($_POST['childGender'][$i] ?? '') === 'Female') ? 'selected' : '' ?>>Female</option>
                    </select><br /><br />
                    
                    <label for="gradeApplyingFor_<?= $i ?>">Grade Applying For: *</label><br />
                    <select id="gradeApplyingFor_<?= $i ?>" name="gradeApplyingFor[]" required>
                        <option value="">-- Select Grade --</option>
                        <option value="Grade R" <?= (($_POST['gradeApplyingFor'][$i] ?? '') === 'Grade R') ? 'selected' : '' ?>>Grade R</option>
                        <option value="Grade 1" <?= (($_POST['gradeApplyingFor'][$i] ?? '') === 'Grade 1') ? 'selected' : '' ?>>Grade 1</option>
                        <option value="Grade 2" <?= (($_POST['gradeApplyingFor'][$i] ?? '') === 'Grade 2') ? 'selected' : '' ?>>Grade 2</option>
                    </select><br /><br />
                    
                    <label for="childIdDocument_<?= $i ?>" class="file-upload-label">Upload Child Birth Certificate/ID (PDF/JPEG): *</label>
                    <input type="file" id="childIdDocument_<?= $i ?>" name="childIdDocument[]" accept=".pdf,.jpg,.jpeg" required /><br /><br />
                    
                    <label for="medicalNotes_<?= $i ?>">Medical Information / Allergies:</label><br />
                    <textarea id="medicalNotes_<?= $i ?>" name="medicalNotes[]" rows="3" cols="40"><?= htmlspecialchars($_POST['medicalNotes'][$i] ?? '') ?></textarea><br /><br />
                </div>
            <?php endfor; ?>
        </div>

        <button type="button" id="addChildBtn" style="margin-top: 10px; background: #4D96FF;">+ Add Another Child</button><br /><br />
        
        <input type="submit" id="submitBtn" value="Submit Application(s)" disabled style="margin-top: 20px;" />
    </form>
</main>

<?php 
// Include footer.php only if it exists
if (file_exists('footer.php')) {
    include 'footer.php'; 
}
?>

<script>
    let childCount = <?= $nextChildIndex ?>;
    const childrenContainer = document.getElementById('childrenContainer');
    const template = childrenContainer.querySelector('.child-section') ? childrenContainer.querySelector('.child-section').outerHTML : '';


    function checkFormValidity() {
        const form = document.getElementById('admissionForm');
        // Check HTML5 validity for all fields
        const isHtmlValid = form.checkValidity();
        
        // Check if files are uploaded (required fields are handled by HTML5, but we check specific files here)
        const parentDoc = document.getElementById('parentIdDocument');
        let isParentDocPresent = parentDoc && parentDoc.files.length > 0;

        let areChildDocsPresent = true;
        document.querySelectorAll('[name="childIdDocument[]"]').forEach(input => {
            if (input.files.length === 0) {
                areChildDocsPresent = false;
            }
        });

        const isFullyValid = isHtmlValid && isParentDocPresent && areChildDocsPresent;
        document.getElementById('submitBtn').disabled = !isFullyValid;
    }

    function attachValidationListeners(index) {
        // Attach input listeners to all required fields in the new section
        const section = document.getElementById('childSection_' + index);
        if (section) {
            section.querySelectorAll('input[required], select[required]').forEach(input => {
                input.addEventListener('input', checkFormValidity);
            });
            // Attach listener to the file input
            const childDocInput = section.querySelector('[name="childIdDocument[]"]');
            if (childDocInput) {
                childDocInput.addEventListener('change', checkFormValidity);
            }
        }
    }

    function addChildSection() {
        if (childCount >= 5) {
            alert("You can only apply for a maximum of 5 children at once.");
            return;
        }

        // Create the new section from the template
        let newHtml = template.replace(new RegExp('_' + (childCount-1), 'g'), '_' + childCount) // Replace index in IDs/FORs
                            .replace(new RegExp('Child ' + childCount, 'g'), 'Child ' + (childCount + 1)); // Update section title

        // Insert new HTML and attach listeners
        childrenContainer.insertAdjacentHTML('beforeend', newHtml);
        attachValidationListeners(childCount);
        
        // Show the remove button on the original section (index 0) if it was hidden
        const firstRemoveBtn = document.querySelector('#childSection_0 .remove-child-btn');
        if (firstRemoveBtn) {
             firstRemoveBtn.style.display = 'block';
        }
        
        childCount++;
        checkFormValidity();
    }

    function removeChildSection(indexToRemove) {
        if (childCount <= 1) return; // Cannot remove the last child

        const sectionToRemove = document.getElementById('childSection_' + indexToRemove);
        if (sectionToRemove) {
            sectionToRemove.remove();
            childCount--;

            // Re-index remaining sections to maintain sequential numbering
            const remainingSections = childrenContainer.querySelectorAll('.child-section');
            remainingSections.forEach((section, newIndex) => {
                const oldIndex = parseInt(section.id.split('_')[1]);
                if (oldIndex !== newIndex) {
                    // Update section ID and title
                    section.id = 'childSection_' + newIndex;
                    section.querySelector('h4').textContent = 'Child ' + (newIndex + 1);
                    
                    // Update all IDs and names inside the section 
                    section.querySelectorAll('[id^="childName_"], [id^="childSurname_"], [id^="childAge_"], [id^="childGender_"], [id^="gradeApplyingFor_"], [id^="childIdDocument_"]').forEach(element => {
                         const oldId = element.id;
                         const newId = oldId.replace(`_${oldIndex}`, `_${newIndex}`);
                         
                         // Update id
                         element.id = newId;
                         
                         // Update for attribute in label if it exists
                         const label = section.querySelector(`label[for="${oldId}"]`);
                         if (label) {
                            label.setAttribute('for', newId);
                         }
                    });

                    // Update remove button data-index
                    const removeBtn = section.querySelector('.remove-child-btn');
                    if (removeBtn) {
                        removeBtn.dataset.index = newIndex;
                    }
                }
            });

            // If only one child remains
            if (childCount === 1) {
                // Hide the remove button on the single remaining child section
                const removeBtn = document.querySelector('#childSection_0 .remove-child-btn');
                if (removeBtn) {
                    removeBtn.style.display = 'none';
                }
            }
            
            checkFormValidity();
        }
    }

    // --- Initialization ---
    document.addEventListener('DOMContentLoaded', () => {
        // Since we are using PHP loops to generate HTML, we attach listeners to all existing sections
        for (let i = 0; i < childCount; i++) {
            attachValidationListeners(i);
        }

        // Global listener for all inputs to check validity (including parent ID doc)
        document.getElementById('admissionForm').addEventListener('input', checkFormValidity);
        
        // File input listeners (only 'change' fires correctly on file selection)
        document.getElementById('parentIdDocument').addEventListener('change', checkFormValidity);
        
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