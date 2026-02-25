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
                    <legend><i class="fas fa-user"></i> Parent/Guardian Information</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="parentFirstName">First Name *</label>
                            <input type="text" id="parentFirstName" name="parentFirstName" required
                                   value="<?= htmlspecialchars($old['parentFirstName'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="parentSurname">Surname *</label>
                            <input type="text" id="parentSurname" name="parentSurname" required
                                   value="<?= htmlspecialchars($old['parentSurname'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contactNumber">Contact Number *</label>
                            <input type="tel" id="contactNumber" name="contactNumber" required
                                   value="<?= htmlspecialchars($old['contactNumber'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="emailAddress">Email Address *</label>
                            <input type="email" id="emailAddress" name="emailAddress" required
                                   value="<?= htmlspecialchars($old['emailAddress'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="parentIdNumber">ID Number *</label>
                            <input type="text" id="parentIdNumber" name="parentIdNumber" required
                                   value="<?= htmlspecialchars($old['parentIdNumber'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="residentialAddress">Residential Address *</label>
                            <input type="text" id="residentialAddress" name="residentialAddress" required
                                   value="<?= htmlspecialchars($old['residentialAddress'] ?? '') ?>">
                        </div>
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
                    <legend><i class="fas fa-child"></i> Child Information</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="childFirstName">First Name *</label>
                            <input type="text" id="childFirstName" name="childFirstName" required
                                   value="<?= htmlspecialchars($old['childFirstName'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="childSurname">Surname *</label>
                            <input type="text" id="childSurname" name="childSurname" required
                                   value="<?= htmlspecialchars($old['childSurname'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="dateOfBirth">Date of Birth *</label>
                            <input type="date" id="dateOfBirth" name="dateOfBirth" required
                                   value="<?= htmlspecialchars($old['dateOfBirth'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="childGender">Gender *</label>
                            <select id="childGender" name="childGender" required>
                                <option value="">Select Gender</option>
                                <option value="male" <?= ($old['childGender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= ($old['childGender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="gradeApplyingFor">Grade Applying For *</label>
                        <select id="gradeApplyingFor" name="gradeApplyingFor" required>
                            <option value="">Select Grade</option>
                            <?php foreach ($gradeCategories as $key => $grade): ?>
                                <option value="<?= $key ?>" <?= ($old['gradeApplyingFor'] ?? '') === $key ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($grade['label']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-help">Age requirements will be validated automatically</small>
                    </div>
                </fieldset>

                <!-- Emergency Contact -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-phone-alt"></i> Emergency Contact</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="emergencyContactName">Emergency Contact Name</label>
                            <input type="text" id="emergencyContactName" name="emergencyContactName"
                                   value="<?= htmlspecialchars($old['emergencyContactName'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="emergencyContactPhone">Emergency Contact Phone</label>
                            <input type="tel" id="emergencyContactPhone" name="emergencyContactPhone"
                                   value="<?= htmlspecialchars($old['emergencyContactPhone'] ?? '') ?>">
                        </div>
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
                    <legend><i class="fas fa-file-upload"></i> Supporting Documents</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="childBirthCertificate">Child's Birth Certificate *</label>
                            <input type="file" id="childBirthCertificate" name="childBirthCertificate" 
                                   accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="form-help">PDF, JPG, or PNG (Max 5MB)</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="parentIdDocument">Parent ID Document *</label>
                            <input type="file" id="parentIdDocument" name="parentIdDocument" 
                                   accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="form-help">PDF, JPG, or PNG (Max 5MB)</small>
                        </div>
                    </div>
                </fieldset>

                <!-- Terms and Submit -->
                <fieldset class="form-section">
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="terms" name="terms" required>
                            <span class="checkmark"></span>
                            I certify that all information provided is accurate and complete. I understand that false information may result in the rejection of this application.
                        </label>
                    </div>
                </fieldset>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                    <button type="reset" class="btn btn-outline">
                        <i class="fas fa-redo"></i> Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
