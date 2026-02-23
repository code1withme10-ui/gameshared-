<?php
require_once 'includes/functions.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = '';
    $success = '';
    $submissionSummary = null;
    
    try {
        // Validate required fields
        $requiredFields = [
            'parentFirstName', 'parentSurname', 'contactNumber', 'emailAddress', 
            'parentIdNumber', 'residentialAddress', 'childFirstName', 'childSurname', 
            'dateOfBirth', 'childGender', 'gradeApplyingFor'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all required fields.");
            }
        }
        
        // Validate email
        if (!filter_var($_POST['emailAddress'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please enter a valid email address.");
        }
        
        // Validate phone number
        if (!preg_match('/^[0-9]{10}$/', $_POST['contactNumber'])) {
            throw new Exception("Please enter a valid 10-digit phone number.");
        }
        
        // Validate ID number
        if (!preg_match('/^[0-9]{13}$/', $_POST['parentIdNumber'])) {
            throw new Exception("Please enter a valid 13-digit ID number.");
        }
        
        // Validate age for grade
        if (!validateAgeForGrade($_POST['dateOfBirth'], $_POST['gradeApplyingFor'])) {
            throw new Exception("Child's age does not match the selected grade category.");
        }
        
        // Handle file uploads
        $uploadDir = __DIR__ . '/uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $parentIdDocument = '';
        if (isset($_FILES['parentIdDocument']) && $_FILES['parentIdDocument']['error'] === UPLOAD_ERR_OK) {
            $parentIdDocument = handleFileUpload($_FILES['parentIdDocument'], $uploadDir, 'parent_id_' . time());
        }
        
        $childIdDocument = '';
        if (isset($_FILES['childIdDocument']) && $_FILES['childIdDocument']['error'] === UPLOAD_ERR_OK) {
            $childIdDocument = handleFileUpload($_FILES['childIdDocument'], $uploadDir, 'child_id_' . time());
        }
        
        // Prepare admission data
        $newAdmission = [
            'parentFirstName' => sanitizeInput($_POST['parentFirstName']),
            'parentSurname' => sanitizeInput($_POST['parentSurname']),
            'contactNumber' => sanitizeInput($_POST['contactNumber']),
            'emailAddress' => sanitizeInput($_POST['emailAddress']),
            'parentIdNumber' => sanitizeInput($_POST['parentIdNumber']),
            'residentialAddress' => sanitizeInput($_POST['residentialAddress']),
            'childFirstName' => sanitizeInput($_POST['childFirstName']),
            'childSurname' => sanitizeInput($_POST['childSurname']),
            'dateOfBirth' => sanitizeInput($_POST['dateOfBirth']),
            'childGender' => sanitizeInput($_POST['childGender']),
            'gradeApplyingFor' => sanitizeInput($_POST['gradeApplyingFor']),
            'medicalInfo' => sanitizeInput($_POST['medicalInfo'] ?? ''),
            'parentIdDocument' => $parentIdDocument,
            'childIdDocument' => $childIdDocument
        ];
        
        // Save admission
        if (saveAdmission($newAdmission)) {
            $success = "✅ Application submitted successfully! We will contact you within 2-3 business days.";
            $submissionSummary = [
                'applicationID' => $newAdmission['applicationID'],
                'parentName' => $newAdmission['parentFirstName'] . ' ' . $newAdmission['parentSurname'],
                'childName' => $newAdmission['childFirstName'] . ' ' . $newAdmission['childSurname'],
                'grade' => $gradeCategories[$newAdmission['gradeApplyingFor']]['label']
            ];
        } else {
            throw new Exception("Failed to save application. Please try again.");
        }
        
    } catch (Exception $e) {
        $error = "❌ " . $e->getMessage();
    }
}

// User data pre-fill from session
$sessionUser = $_SESSION['user'] ?? [];
$defaultParent = [
    'parentFirstName' => $sessionUser['parentName'] ?? '',
    'parentSurname' => $sessionUser['parentSurname'] ?? '',
    'contactNumber' => $sessionUser['phone'] ?? '',
    'emailAddress' => $sessionUser['email'] ?? '',
    'residentialAddress' => '',
    'parentIdNumber' => '',
];

require_once 'includes/header.php';
?>

<main class="home-container">
    <div class="admission-container">
        <h1>📝 Admission Application</h1>
        <p class="admission-intro">Apply to Tiny Tots Creche and give your child the best start in life!</p>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success && $submissionSummary): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <div class="summary-card">
                <h3>Application Summary</h3>
                <div class="summary-details">
                    <p><strong>Application ID:</strong> <?= htmlspecialchars($submissionSummary['applicationID']) ?></p>
                    <p><strong>Parent/Guardian:</strong> <?= htmlspecialchars($submissionSummary['parentName']) ?></p>
                    <p><strong>Child:</strong> <?= htmlspecialchars($submissionSummary['childName']) ?></p>
                    <p><strong>Grade:</strong> <?= htmlspecialchars($submissionSummary['grade']) ?></p>
                </div>
                <p class="next-steps">We will review your application and contact you within 2-3 business days.</p>
            </div>
        <?php else: ?>
        
            <form id="admissionForm" method="POST" enctype="multipart/form-data" class="admission-form">
                <div class="form-section">
                    <h2>👤 Parent/Guardian Information</h2>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="parentFirstName">First Name *</label>
                            <input type="text" id="parentFirstName" name="parentFirstName" 
                                   value="<?= htmlspecialchars($defaultParent['parentFirstName']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="parentSurname">Surname *</label>
                            <input type="text" id="parentSurname" name="parentSurname" 
                                   value="<?= htmlspecialchars($defaultParent['parentSurname']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="contactNumber">Contact Number *</label>
                            <input type="tel" id="contactNumber" name="contactNumber" 
                                   pattern="[0-9]{10}" placeholder="e.g., 0812345678"
                                   value="<?= htmlspecialchars($defaultParent['contactNumber']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="emailAddress">Email Address *</label>
                            <input type="email" id="emailAddress" name="emailAddress" 
                                   value="<?= htmlspecialchars($defaultParent['emailAddress']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="parentIdNumber">ID Number *</label>
                        <input type="text" id="parentIdNumber" name="parentIdNumber" 
                               pattern="[0-9]{13}" placeholder="13-digit South African ID" 
                               value="<?= htmlspecialchars($defaultParent['parentIdNumber']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="residentialAddress">Residential Address *</label>
                        <textarea id="residentialAddress" name="residentialAddress" rows="3" required></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>👶 Child Information</h2>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="childFirstName">Child's First Name *</label>
                            <input type="text" id="childFirstName" name="childFirstName" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="childSurname">Child's Surname *</label>
                            <input type="text" id="childSurname" name="childSurname" required>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="dateOfBirth">Date of Birth *</label>
                            <input type="date" id="dateOfBirth" name="dateOfBirth" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="childGender">Gender *</label>
                            <select id="childGender" name="childGender" required>
                                <option value="">-- Select Gender --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="gradeApplyingFor">Grade Applying For *</label>
                        <select id="gradeApplyingFor" name="gradeApplyingFor" required>
                            <option value="">-- Select Grade --</option>
                            <?php foreach ($gradeCategories as $key => $details): ?>
                                <option value="<?= htmlspecialchars($key) ?>">
                                    <?= htmlspecialchars($details['label']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-help">Select the appropriate grade based on your child's age</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="medicalInfo">Medical Information / Allergies</label>
                        <textarea id="medicalInfo" name="medicalInfo" rows="3" 
                                  placeholder="Please describe any medical conditions, allergies, or special needs..."></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>📎 Required Documents</h2>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="parentIdDocument">Parent ID Document *</label>
                            <input type="file" id="parentIdDocument" name="parentIdDocument" 
                                   accept=".pdf, .jpg, .jpeg, .png" required>
                            <small class="form-help">PDF, JPG, or PNG (Max 5MB)</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="childIdDocument">Child's Birth Certificate/ID *</label>
                            <input type="file" id="childIdDocument" name="childIdDocument" 
                                   accept=".pdf, .jpg, .jpeg, .png" required>
                            <small class="form-help">PDF, JPG, or PNG (Max 5MB)</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                    <button type="reset" class="btn btn-secondary">Clear Form</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<style>
/* Admission Form Styles */
.admission-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.admission-container h1 {
    text-align: center;
    color: var(--secondary-color);
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.admission-intro {
    text-align: center;
    color: var(--text-light);
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.admission-form {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
    overflow: hidden;
}

.form-section {
    padding: 2.5rem;
    border-bottom: 1px solid var(--light-blue);
}

.form-section:last-child {
    border-bottom: none;
}

.form-section h2 {
    color: var(--primary-color);
    margin: 0 0 2rem 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 1rem;
    border: 2px solid var(--light-blue);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--warm-white);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 8px rgba(135, 206, 235, 0.3);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-help {
    color: var(--text-light);
    font-size: 0.85rem;
    margin-top: 0.5rem;
    font-style: italic;
}

.form-actions {
    padding: 2rem 2.5rem;
    background: linear-gradient(135deg, var(--warm-white), var(--light-blue));
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn {
    padding: 1rem 2rem;
    border: none;
    border-radius: 25px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
    color: var(--text-dark);
    box-shadow: 0 4px 15px var(--shadow-light);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--shadow-medium);
}

.btn-secondary {
    background: white;
    color: var(--text-light);
    border: 2px solid var(--light-blue);
}

.btn-secondary:hover {
    background: var(--light-blue);
    color: var(--text-dark);
}

.alert {
    padding: 1.5rem;
    margin-bottom: 2rem;
    border-radius: 10px;
    font-weight: 500;
    text-align: center;
}

.alert-error {
    background: #ff6b6b;
    color: white;
    border-left: 5px solid #d63031;
}

.alert-success {
    background: #51cf66;
    color: white;
    border-left: 5px solid #2b8c3a;
}

.summary-card {
    background: linear-gradient(135deg, #e6ffe6, #d4fdd4);
    border: 2px solid #51cf66;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.summary-card h3 {
    color: var(--primary-color);
    margin: 0 0 1.5rem 0;
    font-size: 1.3rem;
}

.summary-details p {
    margin-bottom: 0.8rem;
    color: var(--text-dark);
    font-size: 1rem;
}

.next-steps {
    text-align: center;
    color: var(--text-light);
    font-style: italic;
    margin-top: 1.5rem;
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-section {
        padding: 2rem 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 250px;
        text-align: center;
    }
}
</style>

<?php
// File upload helper function
function handleFileUpload($file, $uploadDir, $prefix) {
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File upload failed. Please try again.");
    }
    
    if ($file['size'] > $maxSize) {
        throw new Exception("File size must be less than 5MB.");
    }
    
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception("Only JPG, PNG, and PDF files are allowed.");
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . '_' . uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception("Failed to save uploaded file.");
    }
    
    return $filename;
}
?>

<?php require_once 'includes/footer.php'; ?>
