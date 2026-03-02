<div class="content-wrapper">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-content">
            <h1>Admission Application</h1>
            <p>Begin your child's educational journey with Tiny Tots Creche</p>
        </div>
    </section>

    <div class="admission-container">
        <div class="admission-form-container">
            <div class="form-header">
                <h2>📝 Tiny Tots Creche Admission Application</h2>
                <p>Please complete all required fields. Applications are reviewed on a first-come, first-served basis.</p>
            </div>

            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($errors['general']) ?>
                </div>
            <?php endif; ?>

            <form id="admissionForm" method="POST" action="/admission/submit" class="admission-form" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                
                <!-- Parent/Guardian Information -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-user"></i> SECTION 2: PARENT / GUARDIAN DETAILS</legend>
                    
                    <div class="form-group">
                        <label for="parentFullName">Parent Full Name *</label>
                        <input type="text" id="parentFullName" name="parentFullName" required
                               value="<?= htmlspecialchars($old['parentFullName'] ?? '') ?>">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="relationshipToChild">Relationship to Child *</label>
                            <input type="text" id="relationshipToChild" name="relationshipToChild" required
                                   value="<?= htmlspecialchars($old['relationshipToChild'] ?? '') ?>"
                                   placeholder="e.g., Mother, Father, Guardian">
                        </div>
                        
                        <div class="form-group">
                            <label for="parentIdNumber">ID No/Passport No *</label>
                            <input type="text" id="parentIdNumber" name="parentIdNumber" required
                                   value="<?= htmlspecialchars($old['parentIdNumber'] ?? '') ?>"
                                   placeholder="Enter ID or Passport number">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="emailAddress">Email Address *</label>
                            <input type="email" id="emailAddress" name="emailAddress" required
                                   value="<?= htmlspecialchars($old['emailAddress'] ?? '') ?>"
                                   placeholder="your.email@example.com">
                            <small class="form-help">Must be a valid email format</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required
                                   value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                                   placeholder="Enter phone number">
                            <small class="form-help">Must be a valid phone number</small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="parentAddress">Residential Address *</label>
                        <input type="text" id="parentAddress" name="parentAddress" required
                               value="<?= htmlspecialchars($old['parentAddress'] ?? '') ?>"
                               placeholder="Enter parent's residential address">
                    </div>
                </fieldset>

                <!-- Mother Information -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-female"></i> Mother Information (Optional)</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="motherFirstName">First Name</label>
                            <input type="text" id="motherFirstName" name="motherFirstName"
                                   value="<?= htmlspecialchars($old['motherFirstName'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="motherSurname">Surname</label>
                            <input type="text" id="motherSurname" name="motherSurname"
                                   value="<?= htmlspecialchars($old['motherSurname'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="motherIdNumber">ID Number</label>
                            <input type="text" id="motherIdNumber" name="motherIdNumber"
                                   value="<?= htmlspecialchars($old['motherIdNumber'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="motherOccupation">Occupation</label>
                            <input type="text" id="motherOccupation" name="motherOccupation"
                                   value="<?= htmlspecialchars($old['motherOccupation'] ?? '') ?>">
                        </div>
                    </div>
                </fieldset>

                <!-- Father Information -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-male"></i> Father Information (Optional)</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fatherFirstName">First Name</label>
                            <input type="text" id="fatherFirstName" name="fatherFirstName"
                                   value="<?= htmlspecialchars($old['fatherFirstName'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="fatherSurname">Surname</label>
                            <input type="text" id="fatherSurname" name="fatherSurname"
                                   value="<?= htmlspecialchars($old['fatherSurname'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fatherIdNumber">ID Number</label>
                            <input type="text" id="fatherIdNumber" name="fatherIdNumber"
                                   value="<?= htmlspecialchars($old['fatherIdNumber'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="fatherOccupation">Occupation</label>
                            <input type="text" id="fatherOccupation" name="fatherOccupation"
                                   value="<?= htmlspecialchars($old['fatherOccupation'] ?? '') ?>">
                        </div>
                    </div>
                </fieldset>

                <!-- Child Information -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-child"></i> SECTION 1: CHILD DETAILS</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="childFullName">Child Full Name *</label>
                            <input type="text" id="childFullName" name="childFullName" required
                                   value="<?= htmlspecialchars($old['childFullName'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="dateOfBirth">Date of Birth *</label>
                            <input type="date" id="dateOfBirth" name="dateOfBirth" required
                                   value="<?= htmlspecialchars($old['dateOfBirth'] ?? '') ?>">
                            <small class="form-help">Age will be calculated automatically</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="childGender">Gender *</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="childGender" value="male" required
                                           <?= ($old['childGender'] ?? '') === 'male' ? 'checked' : '' ?>>
                                    <span class="radio-checkmark"></span>
                                    Male
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="childGender" value="female"
                                           <?= ($old['childGender'] ?? '') === 'female' ? 'checked' : '' ?>>
                                    <span class="radio-checkmark"></span>
                                    Female
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="childGender" value="other"
                                           <?= ($old['childGender'] ?? '') === 'other' ? 'checked' : '' ?>>
                                    <span class="radio-checkmark"></span>
                                    Other
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="gradeApplyingFor">Grade Applying For *</label>
                        <select id="gradeApplyingFor" name="gradeApplyingFor" required>
                            <option value="">Select Category *</option>
                            <?php foreach ($gradeCategories as $key => $grade): ?>
                                <option value="<?= $key ?>" data-min-age="<?= $grade['min_age'] ?>" data-max-age="<?= $grade['max_age'] ?>"
                                        <?= ($old['gradeApplyingFor'] ?? '') === $key ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($grade['label']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-help">Grade category must match child's age</small>
                        <div id="gradeError" class="error-message" style="display: none;">
                            Selected category does not match child's age.
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="childAddress">Home Address *</label>
                        <input type="text" id="childAddress" name="childAddress" required
                               value="<?= htmlspecialchars($old['childAddress'] ?? '') ?>"
                               placeholder="Enter child's residential address">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="disability">Disability</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="disability" value="no" required
                                           <?= ($old['disability'] ?? 'no') === 'no' ? 'checked' : '' ?>>
                                    <span class="radio-checkmark"></span>
                                    No
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="disability" value="yes"
                                           <?= ($old['disability'] ?? '') === 'yes' ? 'checked' : '' ?>>
                                    <span class="radio-checkmark"></span>
                                    Yes
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="medicalAllergies">Medical Allergies</label>
                            <input type="text" id="medicalAllergies" name="medicalAllergies"
                                   value="<?= htmlspecialchars($old['medicalAllergies'] ?? '') ?>"
                                   placeholder="List any known medical allergies">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="previousSchool">Previous School</label>
                        <input type="text" id="previousSchool" name="previousSchool"
                               value="<?= htmlspecialchars($old['previousSchool'] ?? '') ?>"
                               placeholder="Name of previous school (if any)">
                    </div>
                </fieldset>

                <!-- Emergency Contact -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-phone-alt"></i> SECTION 3: EMERGENCY CONTACT (Other than parent/guardian)</legend>
                    
                    <div class="form-group">
                        <label for="emergencyContactName">Full Name *</label>
                        <input type="text" id="emergencyContactName" name="emergencyContactName" required
                               value="<?= htmlspecialchars($old['emergencyContactName'] ?? '') ?>"
                               placeholder="Enter emergency contact full name">
                    </div>
                    
                    <div class="form-group">
                        <label for="emergencyContactAddress">Residential Address *</label>
                        <input type="text" id="emergencyContactAddress" name="emergencyContactAddress" required
                               value="<?= htmlspecialchars($old['emergencyContactAddress'] ?? '') ?>"
                               placeholder="Enter emergency contact address">
                    </div>
                    
                    <div class="form-group">
                        <label for="emergencyContactPhone">Phone Number *</label>
                        <input type="tel" id="emergencyContactPhone" name="emergencyContactPhone" required
                               value="<?= htmlspecialchars($old['emergencyContactPhone'] ?? '') ?>"
                               placeholder="Enter emergency contact phone number">
                    </div>
                </fieldset>

                <!-- Transportation -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-bus"></i> Transportation</legend>
                    
                    <div class="form-group">
                        <label for="transportation">Transportation Required</label>
                        <select id="transportation" name="transportation">
                            <option value="none" <?= ($old['transportation'] ?? 'none') === 'none' ? 'selected' : '' ?>>No transportation needed</option>
                            <option value="morning" <?= ($old['transportation'] ?? '') === 'morning' ? 'selected' : '' ?>>Morning only</option>
                            <option value="afternoon" <?= ($old['transportation'] ?? '') === 'afternoon' ? 'selected' : '' ?>>Afternoon only</option>
                            <option value="both" <?= ($old['transportation'] ?? '') === 'both' ? 'selected' : '' ?>>Both morning and afternoon</option>
                        </select>
                    </div>
                </fieldset>

                <!-- Special Needs -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-heart"></i> Additional Information</legend>
                    
                    <div class="form-group">
                        <label for="specialNeeds">Special Needs or Medical Conditions</label>
                        <textarea id="specialNeeds" name="specialNeeds" rows="4"
                                  placeholder="Please describe any special needs, allergies, or medical conditions we should be aware of..."><?= htmlspecialchars($old['specialNeeds'] ?? '') ?></textarea>
                        <small class="form-help">This information helps us provide the best care for your child</small>
                    </div>
                </fieldset>

                <!-- Supporting Documents -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-file-upload"></i> SECTION 4: UPLOAD SUPPORTING DOCUMENTS</legend>
                    
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
                </fieldset>

                <!-- Form Validation and Submission -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-check-circle"></i> SECTION 5: FORM VALIDATION AND SUBMISSION</legend>
                    
                    <!-- Validation Errors Display Area -->
                    <div id="validationErrors" class="validation-errors" style="display: none;">
                        <h4><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h4>
                        <ul id="errorList"></ul>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="terms" name="terms" required>
                            <span class="checkmark"></span>
                            I certify that all information provided is accurate and complete. I understand that false information may result in the rejection of this application.
                        </label>
                    </div>
                </fieldset>

                <div class="form-actions">
                    <button type="submit" id="submitBtn" class="btn btn-primary btn-large" disabled>
                        <i class="fas fa-paper-plane"></i> SUBMIT APPLICATION
                    </button>
                    <button type="reset" class="btn btn-outline">
                        <i class="fas fa-redo"></i> Reset Form
                    </button>
                </div>
                
                <div class="submission-notes">
                    <h4><i class="fas fa-info-circle"></i> Submission Notes:</h4>
                    <ul>
                        <li>All required fields marked with "*" must be completed</li>
                        <li>All required documents must be uploaded</li>
                        <li>Submit button will be disabled until all validations pass</li>
                        <li>After submission, you will receive a confirmation with Application ID</li>
                        <li>Application status will default to "Pending"</li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Form validation and file handling
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admissionForm');
    const submitBtn = document.getElementById('submitBtn');
    const validationErrors = document.getElementById('validationErrors');
    const errorList = document.getElementById('errorList');
    const gradeError = document.getElementById('gradeError');
    
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
        
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            previewDiv.innerHTML = '';
            
            if (file) {
                // Validate file type
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    showError(`${input}: Invalid file type. Only PDF, JPG, PNG allowed.`);
                    fileInput.value = '';
                    return;
                }
                
                // Validate file size (2MB = 2 * 1024 * 1024 bytes)
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    showError(`${input}: File size exceeds 2MB limit.`);
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
            
            validateForm();
        });
    });
    
    // Grade validation based on date of birth
    const dateOfBirthInput = document.getElementById('dateOfBirth');
    const gradeSelect = document.getElementById('gradeApplyingFor');
    
    dateOfBirthInput.addEventListener('change', function() {
        validateGradeAge();
        validateForm();
    });
    
    gradeSelect.addEventListener('change', function() {
        validateGradeAge();
        validateForm();
    });
    
    function validateGradeAge() {
        const dob = dateOfBirthInput.value;
        const selectedGrade = gradeSelect.value;
        
        if (!dob || !selectedGrade) {
            gradeError.style.display = 'none';
            return;
        }
        
        const age = calculateAge(dob);
        const selectedOption = gradeSelect.options[gradeSelect.selectedIndex];
        const minAge = parseInt(selectedOption.dataset.minAge);
        const maxAge = parseInt(selectedOption.dataset.maxAge);
        
        if (age < minAge || age > maxAge) {
            gradeError.style.display = 'block';
            gradeError.textContent = `Selected category does not match child's age. Child is ${age} years old, but this category requires ${minAge}-${maxAge} years.`;
            return false;
        } else {
            gradeError.style.display = 'none';
            return true;
        }
    }
    
    function calculateAge(dob) {
        const birthDate = new Date(dob);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        return age;
    }
    
    // Email validation
    const emailInput = document.getElementById('emailAddress');
    emailInput.addEventListener('input', validateForm);
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // Phone validation
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', validateForm);
    
    function validatePhone(phone) {
        const re = /^[\d\s\-\+\(\)]+$/;
        return re.test(phone) && phone.replace(/\D/g, '').length >= 10;
    }
    
    // Form validation
    function validateForm() {
        const errors = [];
        
        // Check required fields
        const requiredFields = [
            'parentFullName', 'relationshipToChild', 'parentIdNumber', 
            'emailAddress', 'phone', 'parentAddress',
            'childFullName', 'dateOfBirth', 'childGender', 'gradeApplyingFor', 'childAddress',
            'emergencyContactName', 'emergencyContactAddress', 'emergencyContactPhone'
        ];
        
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !field.value.trim()) {
                errors.push(`${getFieldLabel(fieldId)} is required`);
            }
        });
        
        // Validate email
        if (emailInput.value && !validateEmail(emailInput.value)) {
            errors.push('Please enter a valid email address');
        }
        
        // Validate phone
        if (phoneInput.value && !validatePhone(phoneInput.value)) {
            errors.push('Please enter a valid phone number');
        }
        
        // Validate grade age
        if (!validateGradeAge()) {
            errors.push('Grade category must match child\'s age');
        }
        
        // Check required documents
        const requiredDocs = ['childBirthCertificate', 'parentIdDocument', 'clinicalReport'];
        requiredDocs.forEach(docId => {
            const docInput = document.getElementById(docId);
            if (docInput && !docInput.files.length) {
                errors.push(`${getDocLabel(docId)} is required`);
            }
        });
        
        // Check terms
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox.checked) {
            errors.push('You must accept the terms and conditions');
        }
        
        // Display errors
        if (errors.length > 0) {
            errorList.innerHTML = errors.map(error => `<li>${error}</li>`).join('');
            validationErrors.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            validationErrors.style.display = 'none';
            submitBtn.disabled = false;
        }
    }
    
    function getFieldLabel(fieldId) {
        const labels = {
            'parentFullName': 'Parent Full Name',
            'relationshipToChild': 'Relationship to Child',
            'parentIdNumber': 'ID No/Passport No',
            'emailAddress': 'Email Address',
            'phone': 'Phone Number',
            'parentAddress': 'Parent Residential Address',
            'childFullName': 'Child Full Name',
            'dateOfBirth': 'Date of Birth',
            'childGender': 'Gender',
            'gradeApplyingFor': 'Grade Applying For',
            'childAddress': 'Child Home Address',
            'emergencyContactName': 'Emergency Contact Name',
            'emergencyContactAddress': 'Emergency Contact Address',
            'emergencyContactPhone': 'Emergency Contact Phone'
        };
        return labels[fieldId] || fieldId;
    }
    
    function getDocLabel(docId) {
        const labels = {
            'childBirthCertificate': 'Child ID / Birth Certificate',
            'parentIdDocument': 'Parent ID',
            'clinicalReport': 'Clinical Report',
            'previousSchoolReport': 'Previous School Report'
        };
        return labels[docId] || docId;
    }
    
    function showError(message) {
        errorList.innerHTML = `<li>${message}</li>`;
        validationErrors.style.display = 'block';
        setTimeout(() => {
            validationErrors.style.display = 'none';
        }, 5000);
    }
    
    // Initial validation
    validateForm();
    
    // Add event listeners for all inputs
    form.addEventListener('input', validateForm);
    form.addEventListener('change', validateForm);
});
</script>

<style>
/* Admission Form Styles */
.page-hero {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 4rem 0;
    text-align: center;
    margin-bottom: 3rem;
}

.page-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.page-hero p {
    font-size: 1.3rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.admission-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.admission-form-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 2.5rem;
    text-align: center;
}

.form-header h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.form-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

.admission-form {
    padding: 3rem;
}

.form-section {
    border: 2px solid var(--light-blue);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    background: var(--warm-white);
}

.form-section legend {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 1rem 2rem;
    border-radius: 10px;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
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
    background: white;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 8px rgba(135, 206, 235, 0.3);
}

.form-help {
    font-size: 0.85rem;
    color: var(--text-light);
    margin-top: 0.5rem;
    font-style: italic;
}

/* Radio Button Styles */
.radio-group {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.radio-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-weight: 500;
    color: var(--text-dark);
}

.radio-label input[type="radio"] {
    display: none;
}

.radio-checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--primary-color);
    border-radius: 50%;
    margin-right: 0.5rem;
    position: relative;
    transition: all 0.3s ease;
}

.radio-label input[type="radio"]:checked + .radio-checkmark {
    background: var(--primary-color);
}

.radio-label input[type="radio"]:checked + .radio-checkmark::after {
    content: '';
    width: 8px;
    height: 8px;
    background: white;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Checkbox Styles */
.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-weight: 500;
    color: var(--text-dark);
    line-height: 1.5;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--primary-color);
    border-radius: 5px;
    margin-right: 0.5rem;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: var(--primary-color);
}

.checkbox-label input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    color: white;
    font-size: 14px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* File Upload Styles */
input[type="file"] {
    padding: 0.8rem;
    border: 2px dashed var(--primary-color);
    background: var(--warm-white);
    cursor: pointer;
}

input[type="file"]:hover {
    border-color: var(--secondary-color);
    background: white;
}

.file-preview {
    margin-top: 1rem;
    padding: 1rem;
    border: 1px solid var(--light-blue);
    border-radius: 10px;
    background: white;
}

.preview-image,
.preview-file {
    text-align: center;
}

.preview-image img {
    border-radius: 5px;
    box-shadow: 0 2px 8px var(--shadow-light);
}

/* Validation Styles */
.validation-errors {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.validation-errors h4 {
    color: #856404;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.validation-errors ul {
    margin: 0;
    padding-left: 1.5rem;
    color: #856404;
}

.validation-errors li {
    margin-bottom: 0.5rem;
}

.error-message {
    color: #dc3545;
    font-size: 0.85rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

/* Alert Styles */
.alert {
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    border-radius: 10px;
    font-weight: 500;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border-left: 5px solid #dc3545;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Upload Notes */
.upload-notes {
    background: var(--light-blue);
    padding: 1.5rem;
    border-radius: 10px;
    margin-top: 1.5rem;
}

.upload-notes h4 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.upload-notes ul {
    margin: 0;
    padding-left: 1.5rem;
    color: var(--text-dark);
}

.upload-notes li {
    margin-bottom: 0.5rem;
}

/* Submission Notes */
.submission-notes {
    background: var(--warm-white);
    padding: 1.5rem;
    border-radius: 10px;
    margin-top: 2rem;
    border: 1px solid var(--light-blue);
}

.submission-notes h4 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.submission-notes ul {
    margin: 0;
    padding-left: 1.5rem;
    color: var(--text-dark);
}

.submission-notes li {
    margin-bottom: 0.5rem;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--light-blue);
}

.btn {
    padding: 1rem 2rem;
    border: none;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-large {
    padding: 1.2rem 2.5rem;
    font-size: 1.1rem;
}

.btn-primary {
    background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
    color: var(--text-dark);
    box-shadow: 0 4px 15px var(--shadow-light);
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--shadow-medium);
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.btn-outline {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .admission-container {
        padding: 0 1rem;
    }
    
    .form-header {
        padding: 2rem 1.5rem;
    }
    
    .admission-form {
        padding: 2rem 1.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .radio-group {
        flex-direction: column;
        gap: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .form-actions .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}

.page-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.page-hero p {
    font-size: 1.3rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.admission-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 1rem;
}

.admission-form-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 2.5rem;
    text-align: center;
}

.form-header h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.form-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

.admission-form {
    padding: 3rem;
}

.form-section {
    border: 2px solid var(--light-blue);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    background: var(--warm-white);
}

.form-section legend {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 1rem 2rem;
    border-radius: 25px;
    font-size: 1.2rem;
    font-weight: 600;
    border: none;
    width: auto;
    margin-bottom: 2rem;
}

.form-section legend i {
    margin-right: 0.8rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--light-blue);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    font-family: 'Poppins', sans-serif;
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
    display: block;
    color: var(--text-light);
    font-size: 0.85rem;
    margin-top: 0.5rem;
    font-style: italic;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    font-size: 0.95rem;
    line-height: 1.5;
}

.checkbox-label input[type="checkbox"] {
    width: auto;
    margin-right: 0.8rem;
    margin-top: 0.2rem;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    font-weight: 500;
}

.alert-error {
    background: #ffe5e5;
    color: var(--error-red);
    border-left: 5px solid var(--error-red);
}

.alert i {
    margin-right: 0.8rem;
    font-size: 1.2rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 2px solid var(--light-blue);
}

@media (max-width: 768px) {
    .page-hero h1 {
        font-size: 2rem;
    }
    
    .page-hero p {
        font-size: 1.1rem;
    }
    
    .admission-form {
        padding: 2rem 1.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-section {
        padding: 1.5rem;
    }
    
    .form-section legend {
        font-size: 1.1rem;
        padding: 0.8rem 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .form-actions .btn {
        width: 100%;
        max-width: 300px;
    }
}
</style>
