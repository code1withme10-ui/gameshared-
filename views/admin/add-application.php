<div class="content-wrapper">
    <div class="form-container">
        <div class="form-header">
            <h1><i class="fas fa-plus-circle"></i> Add Child Application</h1>
            <p>Register a new application for a child</p>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <form id="admissionForm" method="POST" action="/admin/add-application" class="application-form" enctype="multipart/form-data">
            <!-- Parent Selection Section -->
            <div class="form-section">
                <h2><i class="fas fa-user-shield"></i> Parent Information</h2>
                
                <?php if ($fromRegistration && $parent): ?>
                    <!-- Auto-filled parent info from registration -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Parent Information Auto-Filled</strong><br>
                        Parent details have been automatically filled from registration. You can review but cannot edit them here.
                    </div>
                <?php endif; ?>
                
                <?php if (!$fromRegistration): ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="parent_search">Search Existing Parent</label>
                            <input type="text" id="parent_search" placeholder="Search by name, email, or ID..."
                                   autocomplete="off">
                            <div id="parent_search_results" class="search-results"></div>
                        </div>
                        
                        <div class="form-group">
                            <label>OR <a href="/admin/register-parent" class="btn btn-sm btn-outline">Register New Parent</a></label>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="parent_id">Parent ID</label>
                        <input type="text" id="parent_id" name="parent_id" readonly required
                               value="<?= htmlspecialchars($parent['id'] ?? $_POST['parent_id'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="parentFirstName">Parent First Name</label>
                        <input type="text" id="parentFirstName" name="parentFirstName" required
                               value="<?= htmlspecialchars($parent['name'] ?? $_POST['parentFirstName'] ?? '') ?>"
                               <?= $fromRegistration ? 'readonly' : '' ?>>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="parentSurname">Parent Surname</label>
                        <input type="text" id="parentSurname" name="parentSurname" required
                               value="<?= htmlspecialchars($parent['surname'] ?? $_POST['parentSurname'] ?? '') ?>"
                               <?= $fromRegistration ? 'readonly' : '' ?>>
                    </div>
                    
                    <div class="form-group">
                        <label for="relationshipToChild">Relationship to Child</label>
                        <select id="relationshipToChild" name="relationshipToChild" required
                                <?= $fromRegistration ? 'disabled' : '' ?>>
                            <option value="">Select Relationship</option>
                            <option value="mother" <?= (($_POST['relationshipToChild'] ?? ($parent['relationship'] ?? '')) === 'mother' ? 'selected' : '') ?>>Mother</option>
                            <option value="father" <?= (($_POST['relationshipToChild'] ?? ($parent['relationship'] ?? '')) === 'father' ? 'selected' : '') ?>>Father</option>
                            <option value="guardian" <?= (($_POST['relationshipToChild'] ?? ($parent['relationship'] ?? '')) === 'guardian' ? 'selected' : '') ?>>Guardian</option>
                            <option value="grandparent" <?= (($_POST['relationshipToChild'] ?? ($parent['relationship'] ?? '')) === 'grandparent' ? 'selected' : '') ?>>Grandparent</option>
                            <option value="other" <?= (($_POST['relationshipToChild'] ?? ($parent['relationship'] ?? '')) === 'other' ? 'selected' : '') ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="contactNumber">Contact Number</label>
                        <input type="tel" id="contactNumber" name="contactNumber" required
                               value="<?= htmlspecialchars($parent['phone'] ?? $_POST['contactNumber'] ?? '') ?>"
                               pattern="[0-9+\s()-]+"
                               <?= $fromRegistration ? 'readonly' : '' ?>>
                    </div>
                    
                    <div class="form-group">
                        <label for="emailAddress">Email Address</label>
                        <input type="email" id="emailAddress" name="emailAddress" required
                               value="<?= htmlspecialchars($parent['email'] ?? $_POST['emailAddress'] ?? '') ?>"
                               <?= $fromRegistration ? 'readonly' : '' ?>>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="parentIdNumber">Parent ID Number</label>
                        <input type="text" id="parentIdNumber" name="parentIdNumber" required
                               value="<?= htmlspecialchars($parent['id_number'] ?? $_POST['parentIdNumber'] ?? '') ?>"
                               pattern="[0-9]{8,13}"
                               <?= $fromRegistration ? 'readonly' : '' ?>>
                    </div>
                    
                    <div class="form-group">
                        <label for="residentialAddress">Residential Address</label>
                        <input type="text" id="residentialAddress" name="residentialAddress" required
                               value="<?= htmlspecialchars($parent['address'] ?? $_POST['residentialAddress'] ?? '') ?>"
                               <?= $fromRegistration ? 'readonly' : '' ?>>
                    </div>
                </div>
            </div>
            
            <!-- Child Information Section -->
            <div class="form-section">
                <h2><i class="fas fa-child"></i> Child Information</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="childFirstName">Child First Name *</label>
                        <input type="text" id="childFirstName" name="childFirstName" required
                               value="<?= htmlspecialchars($_POST['childFirstName'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="childSurname">Child Surname *</label>
                        <input type="text" id="childSurname" name="childSurname" required
                               value="<?= htmlspecialchars($_POST['childSurname'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="childIdNumber">Child ID Number *</label>
                        <input type="text" id="childIdNumber" name="childIdNumber" required
                               value="<?= htmlspecialchars($_POST['childIdNumber'] ?? '') ?>"
                               pattern="[0-9]{8,13}"
                               title="ID number must be 8-13 digits">
                    </div>
                    
                    <div class="form-group">
                        <label for="dateOfBirth">Date of Birth *</label>
                        <input type="date" id="dateOfBirth" name="dateOfBirth" required
                               value="<?= htmlspecialchars($_POST['dateOfBirth'] ?? '') ?>"
                               onchange="calculateAge()">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="childGender">Gender *</label>
                        <select id="childGender" name="childGender" required>
                            <option value="">Select Gender</option>
                            <option value="male" <?= (($_POST['childGender'] ?? '') === 'male' ? 'selected' : '') ?>>Male</option>
                            <option value="female" <?= (($_POST['childGender'] ?? '') === 'female' ? 'selected' : '') ?>>Female</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="gradeApplyingFor">Grade Applying For *</label>
                        <select id="gradeApplyingFor" name="gradeApplyingFor" required>
                            <option value="">Select Grade</option>
                            <?php foreach ($gradeCategories as $value => $label): ?>
                                <option value="<?= $value ?>" <?= (($_POST['gradeApplyingFor'] ?? '') === $value ? 'selected' : '') ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['gradeApplyingFor'])): ?>
                            <div class="error-message" style="color: #dc3545; font-size: 0.875rem; margin-top: 5px; padding: 8px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px;">
                                <i class="fas fa-exclamation-triangle"></i>
                                <?= htmlspecialchars($errors['gradeApplyingFor']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Medical Information Section -->
            <div class="form-section">
                <h2><i class="fas fa-heartbeat"></i> Medical Information</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="medicalConditions">Medical Conditions</label>
                        <textarea id="medicalConditions" name="medicalConditions" rows="3"
                                  placeholder="List any known medical conditions..."><?= htmlspecialchars($_POST['medicalConditions'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="allergies">Allergies</label>
                        <textarea id="allergies" name="allergies" rows="3"
                                  placeholder="List any known allergies..."><?= htmlspecialchars($_POST['allergies'] ?? '') ?></textarea>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="medications">Current Medications</label>
                        <textarea id="medications" name="medications" rows="3"
                                  placeholder="List any current medications..."><?= htmlspecialchars($_POST['medications'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="doctorName">Doctor's Name</label>
                        <input type="text" id="doctorName" name="doctorName"
                               value="<?= htmlspecialchars($_POST['doctorName'] ?? '') ?>"
                               placeholder="Primary care physician">
                    </div>
                </div>
            </div>
            
            <!-- Emergency Contact Section -->
            <div class="form-section">
                <h2><i class="fas fa-phone-alt"></i> Emergency Contact</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="emergencyContactName">Emergency Contact Name *</label>
                        <input type="text" id="emergencyContactName" name="emergencyContactName" required
                               value="<?= htmlspecialchars($_POST['emergencyContactName'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="emergencyContactRelationship">Relationship to Child *</label>
                        <input type="text" id="emergencyContactRelationship" name="emergencyContactRelationship" required
                               value="<?= htmlspecialchars($_POST['emergencyContactRelationship'] ?? '') ?>"
                               placeholder="e.g., Aunt, Uncle, Grandparent">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="emergencyContactPhone">Emergency Contact Phone *</label>
                        <input type="tel" id="emergencyContactPhone" name="emergencyContactPhone" required
                               value="<?= htmlspecialchars($_POST['emergencyContactPhone'] ?? '') ?>"
                               pattern="[0-9+\s()-]+">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="emergencyContactAddress">Emergency Contact Address</label>
                        <input type="text" id="emergencyContactAddress" name="emergencyContactAddress"
                               value="<?= htmlspecialchars($_POST['emergencyContactAddress'] ?? '') ?>">
                    </div>
                </div>
            </div>
            
            <!-- Additional Information Section -->
            <div class="form-section">
                <h2><i class="fas fa-info-circle"></i> Additional Information</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="transportation">Transportation Required</label>
                        <select id="transportation" name="transportation" required>
                            <option value="none" <?= (($_POST['transportation'] ?? 'none') === 'none' ? 'selected' : '') ?>>None</option>
                            <option value="morning" <?= (($_POST['transportation'] ?? '') === 'morning' ? 'selected' : '') ?>>Morning Only</option>
                            <option value="afternoon" <?= (($_POST['transportation'] ?? '') === 'afternoon' ? 'selected' : '') ?>>Afternoon Only</option>
                            <option value="both" <?= (($_POST['transportation'] ?? '') === 'both' ? 'selected' : '') ?>>Both Ways</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="specialNeeds">Special Needs</label>
                        <textarea id="specialNeeds" name="specialNeeds" rows="3"
                                  placeholder="Describe any special needs or medical conditions..."><?= htmlspecialchars($_POST['specialNeeds'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Supporting Documents Section -->
            <div class="form-section">
                <h2><i class="fas fa-file-upload"></i> Upload Supporting Documents</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="childBirthCertificate">Child ID / Birth Certificate *</label>
                        <input type="file" id="childBirthCertificate" name="childBirthCertificate" 
                               accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="form-help">PDF, JPG, or PNG (Max 2MB)</small>
                        <div id="birthCertPreview" class="file-preview"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="parentIdDocument">Parent ID *</label>
                        <input type="file" id="parentIdDocument" name="parentIdDocument" 
                               accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="form-help">PDF, JPG, or PNG (Max 2MB)</small>
                        <div id="parentIdPreview" class="file-preview"></div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="clinicalReport">Clinical Report *</label>
                        <input type="file" id="clinicalReport" name="clinicalReport" 
                               accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="form-help">PDF, JPG, or PNG (Max 2MB)</small>
                        <div id="clinicalPreview" class="file-preview"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="previousSchoolReport">Previous School Report</label>
                        <input type="file" id="previousSchoolReport" name="previousSchoolReport" 
                               accept=".pdf,.jpg,.jpeg,.png">
                        <small class="form-help">PDF, JPG, or PNG (Max 2MB) - Optional</small>
                        <div id="schoolReportPreview" class="file-preview"></div>
                    </div>
                </div>
                
                <div class="upload-notes">
                    <h4><i class="fas fa-info-circle"></i> Upload Notes:</h4>
                    <ul>
                        <li>Allowed file types: PDF, JPG, PNG</li>
                        <li>Maximum file size: 2MB per file</li>
                        <li>Image files will show preview</li>
                        <li>Invalid file types will be rejected</li>
                        <li>Multiple upload attempts allowed</li>
                    </ul>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Add Application
                </button>
                <a href="/admin/dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="/admin/register-parent" class="btn btn-outline">
                    <i class="fas fa-user-plus"></i> Register Parent
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Form validation and file upload handling for admin application form
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admissionForm') || document.querySelector('.application-form');
    const submitBtn = document.querySelector('button[type="submit"]');
    const dateOfBirthInput = document.getElementById('dateOfBirth');
    const gradeSelect = document.getElementById('gradeApplyingFor');
    
    // Grade age mapping
    const gradeAgeMap = {
        'toddlers': [0, 2],
        'playgroup': [2, 3],
        'preschool': [3, 4],
        'grade_r': [4, 5],
        'grade_1': [5, 6],
        'foundation': [6, 7]
    };
    
    // Function to calculate age from date of birth
    function calculateAge(birthDate) {
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        
        return age;
    }
    
    // Function to find appropriate grade based on age
    function getGradeForAge(age) {
        for (const [grade, ageRange] of Object.entries(gradeAgeMap)) {
            if (age >= ageRange[0] && age <= ageRange[1]) {
                return grade;
            }
        }
        return null; // No suitable grade found
    }
    
    // Function to get grade display text
    function getGradeDisplayText(gradeValue) {
        const gradeOptions = Array.from(gradeSelect.options);
        const option = gradeOptions.find(opt => opt.value === gradeValue);
        return option ? option.text : gradeValue;
    }
    
    // Auto-select grade when date of birth changes
    if (dateOfBirthInput && gradeSelect) {
        dateOfBirthInput.addEventListener('change', function() {
            const birthDate = this.value;
            console.log('Birth date changed:', birthDate);
            
            if (birthDate) {
                const age = calculateAge(birthDate);
                console.log('Calculated age:', age);
                
                const appropriateGrade = getGradeForAge(age);
                console.log('Appropriate grade:', appropriateGrade);
                
                if (appropriateGrade) {
                    // Check if the grade option exists in the select
                    const gradeOption = gradeSelect.querySelector(`option[value="${appropriateGrade}"]`);
                    console.log('Grade option found:', gradeOption);
                    
                    if (gradeOption) {
                        gradeSelect.value = appropriateGrade;
                        console.log('Grade set to:', appropriateGrade);
                        
                        // Show success message
                        const existingMessage = gradeSelect.parentNode.querySelector('.auto-grade-message');
                        if (existingMessage) {
                            existingMessage.remove();
                        }
                        
                        const message = document.createElement('div');
                        message.className = 'auto-grade-message';
                        message.style.cssText = 'color: #28a745; font-size: 0.875rem; margin-top: 5px; padding: 5px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px;';
                        message.innerHTML = `<i class="fas fa-check-circle"></i> Grade automatically set to "${getGradeDisplayText(appropriateGrade)}" for child aged ${age} years`;
                        gradeSelect.parentNode.appendChild(message);
                        
                        // Remove message after 3 seconds
                        setTimeout(() => {
                            message.remove();
                        }, 3000);
                    } else {
                        console.error('Grade option not found in select:', appropriateGrade);
                    }
                } else {
                    // Show warning if no suitable grade found
                    const existingMessage = gradeSelect.parentNode.querySelector('.auto-grade-message');
                    if (existingMessage) {
                        existingMessage.remove();
                    }
                    
                    const message = document.createElement('div');
                    message.className = 'auto-grade-message';
                    message.style.cssText = 'color: #856404; font-size: 0.875rem; margin-top: 5px; padding: 5px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px;';
                    message.innerHTML = `<i class="fas fa-exclamation-triangle"></i> No suitable grade found for child aged ${age} years. Please select manually.`;
                    gradeSelect.parentNode.appendChild(message);
                    
                    // Remove message after 5 seconds
                    setTimeout(() => {
                        message.remove();
                    }, 5000);
                }
            }
        });
        
        // Also trigger on page load if date is pre-filled
        if (dateOfBirthInput.value) {
            console.log('Triggering grade selection on page load');
            dateOfBirthInput.dispatchEvent(new Event('change'));
        }
    }
    
    // Prevent form submission if validation errors exist
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Check for any error messages on the page
            const errorMessages = document.querySelectorAll('.error-message');
            const sessionErrors = document.querySelectorAll('.alert-danger');
            
            if (errorMessages.length > 0 || sessionErrors.length > 0) {
                e.preventDefault();
                
                // Scroll to first error
                const firstError = errorMessages[0] || sessionErrors[0];
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                // Show alert
                alert('Please fix all validation errors before submitting the application.');
                return false;
            }
            
            // Validate required fields
            const requiredFields = form.querySelectorAll('[required]');
            let hasEmptyRequired = false;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    hasEmptyRequired = true;
                    field.style.borderColor = '#dc3545';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (hasEmptyRequired) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
                return false;
            }
        });
    }
    
    // File upload handling
    const fileInputs = [
        { input: 'childBirthCertificate', preview: 'birthCertPreview' },
        { input: 'parentIdDocument', preview: 'parentIdPreview' },
        { input: 'clinicalReport', preview: 'clinicalPreview' },
        { input: 'previousSchoolReport', preview: 'schoolReportPreview' }
    ];
    
    fileInputs.forEach(({ input, preview }) => {
        const fileInput = document.getElementById(input);
        const previewDiv = document.getElementById(preview);
        
        if (fileInput && previewDiv) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                previewDiv.innerHTML = '';
                
                if (file) {
                    // Validate file type
                    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                    if (!allowedTypes.includes(file.type)) {
                        alert(`${input}: Invalid file type. Only PDF, JPG, PNG allowed.`);
                        fileInput.value = '';
                        return;
                    }
                    
                    // Validate file size (2MB = 2 * 1024 * 1024 bytes)
                    const maxSize = 2 * 1024 * 1024;
                    if (file.size > maxSize) {
                        alert(`${input}: File size exceeds 2MB limit.`);
                        fileInput.value = '';
                        return;
                    }
                    
                    // Show preview for images
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewDiv.innerHTML = `
                                <div class="preview-image">
                                    <img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 5px;">
                                    <p><small>${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</small></p>
                                </div>
                            `;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewDiv.innerHTML = `
                            <div class="preview-file">
                                <i class="fas fa-file-pdf" style="font-size: 2rem; color: #dc3545;"></i>
                                <p><small>${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</small></p>
                            </div>
                        `;
                    }
                }
            });
        }
    });
});
</script>

<style>
/* CSS Variables - Match Main System Exactly */
:root {
    --primary-color: #87CEEB;      /* Baby Blue */
    --secondary-color: #FFD700;     /* Sunny Yellow */
    --accent-color: #FFA500;        /* Golden Yellow */
    --light-blue: #B0E0E6;        /* Soft Blue */
    --warm-white: #FFFEF7;         /* Warm White */
    --text-dark: #333333;
    --text-light: #666666;
    --text-muted: #999999;
    --error-red: #ff6b6b;
    --success-green: #51cf66;
    --shadow-light: rgba(0, 0, 0, 0.1);
    --shadow-medium: rgba(0, 0, 0, 0.15);
    --border-radius: 8px;
}

/* Base Styles */
* {
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: var(--text-dark);
    background-color: var(--warm-white);
}

/* Alert Styles */
.alert {
    padding: 12px 16px;
    border-radius: var(--border-radius);
    margin-bottom: 20px;
    border: 1px solid transparent;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}

.alert i {
    font-size: 1.1em;
    flex-shrink: 0;
}

/* Form Container */
.form-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.form-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 30px 0;
}

.form-header h1 {
    color: var(--primary-color);
    margin-bottom: 10px;
    font-size: 2.5rem;
    font-weight: 600;
}

.form-header p {
    color: var(--text-light);
    font-size: 1.1rem;
    margin: 0;
}

/* Application Form */
.application-form {
    background: white;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 8px 25px var(--shadow-light);
    border: 1px solid #e0e0e0;
    margin-bottom: 40px;
}

/* Form Sections */
.form-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e0e0e0;
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.form-section h2 {
    color: var(--primary-color);
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--primary-color);
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-section h2 i {
    font-size: 1.2em;
}

/* Form Layout */
.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 25px;
}

.form-group {
    display: flex;
    flex-direction: column;
    position: relative;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group.half-width {
    grid-column: span 1;
}

/* Form Labels */
.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-dark);
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.required {
    color: var(--error-red);
}

/* Form Inputs */
.form-group input,
.form-group textarea,
.form-group select {
    padding: 14px 16px;
    border: 2px solid var(--light-blue);
    border-radius: var(--border-radius);
    font-size: 1rem;
    font-family: inherit;
    transition: all 0.3s ease;
    background-color: white;
    width: 100%;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(135, 206, 235, 0.2);
    transform: translateY(-1px);
}

.form-group input[readonly],
.form-group textarea[readonly],
.form-group select[disabled] {
    background-color: var(--light-color);
    cursor: not-allowed;
    opacity: 0.7;
    border-color: #ccc;
}

.form-group input[readonly]:focus,
.form-group textarea[readonly]:focus,
.form-group select[disabled]:focus {
    box-shadow: none;
    transform: none;
}

/* Form Help Text */
.form-help {
    color: var(--text-muted);
    font-size: 0.875rem;
    margin-top: 5px;
    font-style: italic;
}

/* Error Messages */
.error-message {
    color: var(--error-red);
    font-size: 0.875rem;
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 500;
}

.error-message i {
    font-size: 0.9em;
}

/* Auto Grade Message */
.auto-grade-message {
    margin-top: 8px;
    padding: 8px 12px;
    border-radius: var(--border-radius);
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #e0e0e0;
}

/* Buttons */
.btn {
    padding: 14px 28px;
    border: none;
    border-radius: var(--border-radius);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: inherit;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(135, 206, 235, 0.3);
}

.btn-secondary {
    background: var(--secondary-color);
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
    transform: translateY(-2px);
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

.btn-sm {
    padding: 8px 16px;
    font-size: 0.875rem;
}

/* Document Upload Section */
.upload-section {
    background: var(--light-color);
    padding: 25px;
    border-radius: var(--border-radius);
    margin-top: 20px;
    border: 1px solid #e0e0e0;
}

.upload-section h3 {
    color: var(--text-dark);
    margin-bottom: 20px;
    font-size: 1.2rem;
}

.upload-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.upload-item {
    background: white;
    padding: 20px;
    border-radius: var(--border-radius);
    border: 1px solid #e0e0e0;
}

.upload-item label {
    display: block;
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--text-dark);
}

.upload-item input[type="file"] {
    width: 100%;
    padding: 10px;
    border: 2px dashed #ccc;
    border-radius: var(--border-radius);
    background: white;
    cursor: pointer;
}

.upload-item input[type="file"]:hover {
    border-color: var(--primary-color);
}

/* File Preview */
.file-preview {
    margin-top: 15px;
    padding: 15px;
    border: 1px solid #e0e0e0;
    border-radius: var(--border-radius);
    background: var(--light-color);
    text-align: center;
}

.preview-image img {
    max-width: 200px;
    max-height: 150px;
    border-radius: var(--border-radius);
    border: 1px solid #ddd;
    box-shadow: 0 2px 8px var(--shadow-light);
}

.preview-file {
    text-align: center;
    padding: 20px;
    color: var(--text-muted);
}

/* Upload Notes */
.upload-notes {
    background: #fff9e6;
    padding: 20px;
    border-radius: var(--border-radius);
    margin-top: 25px;
    border: 1px solid #ffeaa7;
}

.upload-notes h4 {
    color: var(--text-dark);
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.upload-notes ul {
    margin: 0;
    padding-left: 20px;
}

.upload-notes li {
    margin-bottom: 8px;
    color: var(--text-muted);
    line-height: 1.5;
}

/* Search Results */
.search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e0e0e0;
    border-top: none;
    border-radius: 0 0 var(--border-radius) var(--border-radius);
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 4px 12px var(--shadow-light);
}

.search-result-item {
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s ease;
}

.search-result-item:hover {
    background-color: var(--light-color);
}

.search-result-item:last-child {
    border-bottom: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-container {
        padding: 10px;
    }
    
    .application-form {
        padding: 20px;
    }
    
    .form-header h1 {
        font-size: 2rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
    
    .upload-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .form-header h1 {
        font-size: 1.5rem;
    }
    
    .form-section h2 {
        font-size: 1.2rem;
    }
    
    .btn {
        padding: 12px 20px;
        font-size: 0.9rem;
    }
}
</style>
        max-width: 300px;
    }
}
</style>
