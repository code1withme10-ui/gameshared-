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
                    
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Parent Information Auto-Filled</strong><br>
                            Your details have been automatically filled from your profile. You can edit them if needed.
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="parentFullName">Parent Full Name *</label>
                            <input type="text" id="parentFullName" name="parentFullName" required
                                   value="<?= htmlspecialchars(isset($_SESSION['user']) ? ($_SESSION['user']['name'] . ' ' . $_SESSION['user']['surname']) : ($old['parentFullName'] ?? '')) ?>"
                                   <?= isset($_SESSION['user']) ? 'readonly' : '' ?>>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="relationshipToChild">Relationship to Child *</label>
                            <input type="text" id="relationshipToChild" name="relationshipToChild" required
                                   value="<?= htmlspecialchars(isset($_SESSION['user']) ? ($_SESSION['user']['relationship'] ?? '') : ($old['relationshipToChild'] ?? '')) ?>"
                                   placeholder="e.g., Mother, Father, Guardian"
                                   <?= isset($_SESSION['user']) ? 'readonly' : '' ?>>
                        </div>
                        
                        <div class="form-group">
                            <label for="parentIdNumber">ID No/Passport No *</label>
                            <input type="text" id="parentIdNumber" name="parentIdNumber" required
                                   value="<?= htmlspecialchars(isset($_SESSION['user']) ? ($_SESSION['user']['id_number'] ?? '') : ($old['parentIdNumber'] ?? '')) ?>"
                                   placeholder="Enter ID or Passport number"
                                   <?= isset($_SESSION['user']) ? 'readonly' : '' ?>>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="emailAddress">Email Address *</label>
                            <input type="email" id="emailAddress" name="emailAddress" required
                                   value="<?= htmlspecialchars(isset($_SESSION['user']) ? ($_SESSION['user']['email'] ?? '') : ($old['emailAddress'] ?? '')) ?>"
                                   placeholder="your.email@example.com"
                                   <?= isset($_SESSION['user']) ? 'readonly' : '' ?>>
                            <small class="form-help">Must be a valid email format</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required
                                   value="<?= htmlspecialchars(isset($_SESSION['user']) ? ($_SESSION['user']['phone'] ?? '') : ($old['phone'] ?? '')) ?>"
                                   placeholder="Enter phone number"
                                   <?= isset($_SESSION['user']) ? 'readonly' : '' ?>>
                            <small class="form-help">Must be a valid phone number</small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="parentAddress">Residential Address *</label>
                        <input type="text" id="parentAddress" name="parentAddress" required
                               value="<?= htmlspecialchars(isset($_SESSION['user']) ? ($_SESSION['user']['address'] ?? '') : ($old['parentAddress'] ?? '')) ?>"
                               placeholder="Enter parent's residential address"
                               <?= isset($_SESSION['user']) ? 'readonly' : '' ?>>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="maritalStatus">Marital Status *</label>
                            <select id="maritalStatus" name="maritalStatus" required
                                    <?= isset($_SESSION['user']) ? 'disabled' : '' ?>>
                                <option value="">Select Status</option>
                                <option value="married" <?= (isset($_SESSION['user']) ? ($_SESSION['user']['marital_status'] ?? '') : ($old['maritalStatus'] ?? '')) === 'married' ? 'selected' : '' ?>>Married</option>
                                <option value="single" <?= (isset($_SESSION['user']) ? ($_SESSION['user']['marital_status'] ?? '') : ($old['maritalStatus'] ?? '')) === 'single' ? 'selected' : '' ?>>Single</option>
                                <option value="divorced" <?= (isset($_SESSION['user']) ? ($_SESSION['user']['marital_status'] ?? '') : ($old['maritalStatus'] ?? '')) === 'divorced' ? 'selected' : '' ?>>Divorced</option>
                                <option value="widowed" <?= (isset($_SESSION['user']) ? ($_SESSION['user']['marital_status'] ?? '') : ($old['maritalStatus'] ?? '')) === 'widowed' ? 'selected' : '' ?>>Widowed</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="workContactNumber">Work Contact Number</label>
                            <input type="tel" id="workContactNumber" name="workContactNumber"
                                   value="<?= htmlspecialchars(isset($_SESSION['user']) ? ($_SESSION['user']['work_phone'] ?? '') : ($old['workContactNumber'] ?? '')) ?>"
                                   placeholder="Work phone number"
                                   <?= isset($_SESSION['user']) ? 'readonly' : '' ?>>
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
                    <legend><i class="fas fa-child"></i> SECTION 1: CHILD DETAILS</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="childFullName">Child Full Name *</label>
                            <input type="text" id="childFullName" name="childFullName" required
                                   value="<?= htmlspecialchars($old['childFullName'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="childNickname">Nickname</label>
                            <input type="text" id="childNickname" name="childNickname"
                                   value="<?= htmlspecialchars($old['childNickname'] ?? '') ?>"
                                   placeholder="Child's preferred name (optional)">
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
                            <label for="childIdNumber">Child ID Number</label>
                            <input type="text" id="childIdNumber" name="childIdNumber"
                                   value="<?= htmlspecialchars($old['childIdNumber'] ?? '') ?>"
                                   placeholder="Child's ID number (if available)">
                        </div>
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
                    <legend><i class="fas fa-phone-alt"></i> SECTION 3: EMERGENCY CONTACTS (Other than parent/guardian)</legend>
                    
                    <div class="emergency-contacts">
                        <div class="contact-entry">
                            <h4>Emergency Contact #1 *</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="emergencyContact1Name">Full Name *</label>
                                    <input type="text" id="emergencyContact1Name" name="emergencyContact1Name" required
                                           value="<?= htmlspecialchars($old['emergencyContact1Name'] ?? '') ?>"
                                           placeholder="Enter emergency contact full name">
                                </div>
                                
                                <div class="form-group">
                                    <label for="emergencyContact1Relationship">Relationship to Child *</label>
                                    <input type="text" id="emergencyContact1Relationship" name="emergencyContact1Relationship" required
                                           value="<?= htmlspecialchars($old['emergencyContact1Relationship'] ?? '') ?>"
                                           placeholder="e.g., Grandmother, Uncle, Family Friend">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="emergencyContact1Phone">Phone Number *</label>
                                    <input type="tel" id="emergencyContact1Phone" name="emergencyContact1Phone" required
                                           value="<?= htmlspecialchars($old['emergencyContact1Phone'] ?? '') ?>"
                                           placeholder="Enter emergency contact phone number">
                                </div>
                                
                                <div class="form-group">
                                    <label for="emergencyContact1Address">Residential Address *</label>
                                    <input type="text" id="emergencyContact1Address" name="emergencyContact1Address" required
                                           value="<?= htmlspecialchars($old['emergencyContact1Address'] ?? '') ?>"
                                           placeholder="Enter emergency contact address">
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-entry">
                            <h4>Emergency Contact #2</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="emergencyContact2Name">Full Name</label>
                                    <input type="text" id="emergencyContact2Name" name="emergencyContact2Name"
                                           value="<?= htmlspecialchars($old['emergencyContact2Name'] ?? '') ?>"
                                           placeholder="Enter emergency contact full name">
                                </div>
                                
                                <div class="form-group">
                                    <label for="emergencyContact2Relationship">Relationship to Child</label>
                                    <input type="text" id="emergencyContact2Relationship" name="emergencyContact2Relationship"
                                           value="<?= htmlspecialchars($old['emergencyContact2Relationship'] ?? '') ?>"
                                           placeholder="e.g., Grandfather, Aunt, Family Friend">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="emergencyContact2Phone">Phone Number</label>
                                    <input type="tel" id="emergencyContact2Phone" name="emergencyContact2Phone"
                                           value="<?= htmlspecialchars($old['emergencyContact2Phone'] ?? '') ?>"
                                           placeholder="Enter emergency contact phone number">
                                </div>
                                
                                <div class="form-group">
                                    <label for="emergencyContact2Address">Residential Address</label>
                                    <input type="text" id="emergencyContact2Address" name="emergencyContact2Address"
                                           value="<?= htmlspecialchars($old['emergencyContact2Address'] ?? '') ?>"
                                           placeholder="Enter emergency contact address">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <!-- Authorized Collectors -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-user-check"></i> SECTION 3B: PEOPLE AUTHORIZED TO COLLECT CHILD</legend>
                    <p><strong>Important:</strong> People not on this list will only be allowed to take your child with special arrangement made by you</p>
                    
                    <div class="collectors-section">
                        <div class="collector-entry">
                            <h4>Authorized Person #1 *</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="collector1Name">Name & Surname *</label>
                                    <input type="text" id="collector1Name" name="collector1Name" required
                                           value="<?= htmlspecialchars($old['collector1Name'] ?? '') ?>"
                                           placeholder="Full name of authorized person">
                                </div>
                                
                                <div class="form-group">
                                    <label for="collector1Relationship">Relationship to Parents *</label>
                                    <input type="text" id="collector1Relationship" name="collector1Relationship" required
                                           value="<?= htmlspecialchars($old['collector1Relationship'] ?? '') ?>"
                                           placeholder="e.g., Mother, Father, Grandmother">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="collector1Phone">Contact Number *</label>
                                <input type="tel" id="collector1Phone" name="collector1Phone" required
                                       value="<?= htmlspecialchars($old['collector1Phone'] ?? '') ?>"
                                       placeholder="Contact number for authorized person">
                            </div>
                            
                            <div class="form-group">
                                <label for="collector1Id">ID Number *</label>
                                <input type="text" id="collector1Id" name="collector1Id" required
                                       value="<?= htmlspecialchars($old['collector1Id'] ?? '') ?>"
                                       placeholder="ID number for verification">
                            </div>
                        </div>
                        
                        <div class="collector-entry">
                            <h4>Authorized Person #2</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="collector2Name">Name & Surname</label>
                                    <input type="text" id="collector2Name" name="collector2Name"
                                           value="<?= htmlspecialchars($old['collector2Name'] ?? '') ?>"
                                           placeholder="Full name of authorized person">
                                </div>
                                
                                <div class="form-group">
                                    <label for="collector2Relationship">Relationship to Parents</label>
                                    <input type="text" id="collector2Relationship" name="collector2Relationship"
                                           value="<?= htmlspecialchars($old['collector2Relationship'] ?? '') ?>"
                                           placeholder="e.g., Mother, Father, Grandmother">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="collector2Phone">Contact Number</label>
                                    <input type="tel" id="collector2Phone" name="collector2Phone"
                                           value="<?= htmlspecialchars($old['collector2Phone'] ?? '') ?>"
                                           placeholder="Contact number for authorized person">
                                </div>
                                
                                <div class="form-group">
                                    <label for="collector2Id">ID Number</label>
                                    <input type="text" id="collector2Id" name="collector2Id"
                                           value="<?= htmlspecialchars($old['collector2Id'] ?? '') ?>"
                                           placeholder="ID number for verification">
                                </div>
                            </div>
                        </div>
                        
                        <div class="collector-entry">
                            <h4>Authorized Person #3</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="collector3Name">Name & Surname</label>
                                    <input type="text" id="collector3Name" name="collector3Name"
                                           value="<?= htmlspecialchars($old['collector3Name'] ?? '') ?>"
                                           placeholder="Full name of authorized person">
                                </div>
                                
                                <div class="form-group">
                                    <label for="collector3Relationship">Relationship to Parents</label>
                                    <input type="text" id="collector3Relationship" name="collector3Relationship"
                                           value="<?= htmlspecialchars($old['collector3Relationship'] ?? '') ?>"
                                           placeholder="e.g., Mother, Father, Grandmother">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="collector3Phone">Contact Number</label>
                                    <input type="tel" id="collector3Phone" name="collector3Phone"
                                           value="<?= htmlspecialchars($old['collector3Phone'] ?? '') ?>"
                                           placeholder="Contact number for authorized person">
                                </div>
                                
                                <div class="form-group">
                                    <label for="collector3Id">ID Number</label>
                                    <input type="text" id="collector3Id" name="collector3Id"
                                           value="<?= htmlspecialchars($old['collector3Id'] ?? '') ?>"
                                           placeholder="ID number for verification">
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <!-- Medical Aid Information -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-hospital"></i> SECTION 4: MEDICAL AID INFORMATION</legend>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="medicalAidName">Medical Aid Name</label>
                            <input type="text" id="medicalAidName" name="medicalAidName"
                                   value="<?= htmlspecialchars($old['medicalAidName'] ?? '') ?>"
                                   placeholder="e.g., Discovery, Bonitas, Momentum">
                        </div>
                        
                        <div class="form-group">
                            <label for="medicalAidNumber">Medical Aid Number</label>
                            <input type="text" id="medicalAidNumber" name="medicalAidNumber"
                                   value="<?= htmlspecialchars($old['medicalAidNumber'] ?? '') ?>"
                                   placeholder="Medical aid scheme number">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="childDependentNumber">Child Dependent Number</label>
                            <input type="text" id="childDependentNumber" name="childDependentNumber"
                                   value="<?= htmlspecialchars($old['childDependentNumber'] ?? '') ?>"
                                   placeholder="Child's dependent number on medical aid">
                        </div>
                        
                        <div class="form-group">
                            <label for="mainMemberName">Main Member Name & Surname</label>
                            <input type="text" id="mainMemberName" name="mainMemberName"
                                   value="<?= htmlspecialchars($old['mainMemberName'] ?? '') ?>"
                                   placeholder="Main member's full name">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="mainMemberIdNumber">Main Member ID Number</label>
                        <input type="text" id="mainMemberIdNumber" name="mainMemberIdNumber"
                               value="<?= htmlspecialchars($old['mainMemberIdNumber'] ?? '') ?>"
                               placeholder="Main member's ID number">
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
                    <legend><i class="fas fa-file-upload"></i> SECTION 5: UPLOAD SUPPORTING DOCUMENTS</legend>
                    
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

                <!-- Fee Acknowledgment and Policies -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-file-contract"></i> SECTION 6: FEE ACKNOWLEDGMENT AND POLICIES</legend>
                    
                    <div class="fee-acknowledgment">
                        <div class="fee-header">
                            <i class="fas fa-info-circle"></i>
                            <h3>Important Fee and Policy Information</h3>
                        </div>
                        
                        <div class="fee-content">
                            <div class="fee-box">
                                <h4>📅 Fee Structure and Payment Terms</h4>
                                <ul>
                                    <li><strong>Registration Fee:</strong> R500.00 payable annually</li>
                                    <li><strong>Monthly Fees:</strong> R1,850.00 (Full day 6:30-17:00) or R1,650.00 (Half day 6:30-13:00)</li>
                                    <li><strong>Payment Due:</strong> Strictly payable in advance on or before the last day of each month</li>
                                    <li><strong>Late Payment:</strong> +10% for each day of late payment</li>
                                    <li><strong>Late Collection:</strong> R30 for every 15 minutes after closing time</li>
                                </ul>
                            </div>
                            
                            <div class="fee-box">
                                <h4>🏦 Banking Details</h4>
                                <ul>
                                    <li><strong>Bank:</strong> FNB Bank</li>
                                    <li><strong>Account Name:</strong> Tiny Tots Creche NPO</li>
                                    <li><strong>Account Number:</strong> 63073329089</li>
                                    <li><strong>Reference:</strong> Child's name and surname</li>
                                </ul>
                            </div>
                            
                            <div class="fee-box">
                                <h4>📋 Important Policies</h4>
                                <ul>
                                    <li><strong>Notice Period:</strong> One (1) month's paid and written notice required if child is leaving</li>
                                    <li><strong>No Refunds:</strong> Full payment due even if child is absent for any reason</li>
                                    <li><strong>Space Forfeiture:</strong> No payment for 3 months = automatic space forfeiture</li>
                                    <li><strong>Required Documents:</strong> Clinic card, birth certificate, parent IDs, collector IDs</li>
                                </ul>
                            </div>
                            
                            <div class="fee-box">
                                <h4>🚗 Transportation and Collection</h4>
                                <ul>
                                    <li><strong>Authorized Collectors Only:</strong> Only people on the authorized list may collect child</li>
                                    <li><strong>ID Verification:</strong> Collectors must present ID for verification</li>
                                    <li><strong>Special Arrangements:</strong> Required for anyone not on authorized list</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="fee-agreement">
                            <label class="checkbox-label">
                                <input type="checkbox" id="feeAcknowledgment" name="feeAcknowledgment" required>
                                <span class="checkmark"></span>
                                <strong>I have read, understood, and agree to all fee terms and payment conditions</strong>
                            </label>
                        </div>
                    </div>
                </fieldset>

                <!-- Medical and Emergency Policies -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-heartbeat"></i> SECTION 7: MEDICAL AND EMERGENCY POLICIES</legend>
                    
                    <div class="medical-policies">
                        <div class="policy-box">
                            <h4>🤒 Fever and Illness Policy</h4>
                            <ul>
                                <li>Children with temperature 37°C and higher must be kept home</li>
                                <li>Parents will be contacted immediately to collect sick children</li>
                                <li>No medication kept on premises - children must be kept home when ill</li>
                                <li>Contagious illnesses (stomach viruses, measles, head lice, etc.) require clearance before return</li>
                            </ul>
                        </div>
                        
                        <div class="policy-box">
                            <h4>💊 Medication Policy</h4>
                            <ul>
                                <li>Medication must be clearly marked with child's name and administration times</li>
                                <li>Message MUST be sent to 081 421 0084 with medication instructions</li>
                                <li>No medication in bags - must be handed directly to teachers</li>
                                <li>All allergies must be disclosed and taken seriously</li>
                            </ul>
                        </div>
                        
                        <div class="policy-box">
                            <h4>🏥 Emergency Medical Treatment</h4>
                            <ul>
                                <li>Staff authorized to provide emergency medical care when parents unavailable</li>
                                <li>Parents take full responsibility for all medical costs</li>
                                <li>Emergency transportation authorized when necessary</li>
                                <li>No life-sustaining procedures will be withheld</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="policy-agreement">
                        <label class="checkbox-label">
                            <input type="checkbox" id="medicalPolicyAgreement" name="medicalPolicyAgreement" required>
                            <span class="checkmark"></span>
                            <strong>I understand and agree to all medical and emergency policies</strong>
                        </label>
                    </div>
                </fieldset>

                <!-- Indemnity and Permission -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-shield-alt"></i> SECTION 8: INDEMNITY AND PERMISSION</legend>
                    
                    <div class="indemnity-section">
                        <div class="indemnity-content">
                            <div class="indemnity-box">
                                <h4>📋 Indemnity Agreement</h4>
                                <p>The undersigned parent or legal guardian hereby expressly grants to Tiny Tots Crèche and its authorized staff consent to emergency medical care for the child when immediate contact with parents is not possible and waiting would jeopardize the child's health and welfare.</p>
                                <p>The undersigned assumes all risk of injury or harm to the child associated with participation in the crèche and agrees to release, indemnify, defend and forever discharge Tiny Tots Crèche and its staff from all liability.</p>
                            </div>
                            
                            <div class="indemnity-box">
                                <h4>🚌 Permission for Outings and Transportation</h4>
                                <p>I give permission for my child to participate in all activities while in the care of Tiny Tots and to be transported by designated drivers of Tiny Tots.</p>
                                <p>I understand that Tiny Tots shall not be held responsible for any costs incurred due to accident or injury either on the premises or during transit.</p>
                            </div>
                            
                            <div class="indemnity-box">
                                <h4>📱 Communication and Data Processing</h4>
                                <p>Upon registration, I consent to the processing of personal information for academic and related purposes, including communication with governmental departments and school WhatsApp/Telegram groups.</p>
                                <p>I agree that relevant staff of Tiny Tots act in loco parentis should I or my child's emergency contact be unavailable should medical treatment and/or surgery be deemed necessary.</p>
                            </div>
                        </div>
                        
                        <div class="indemnity-agreement">
                            <label class="checkbox-label">
                                <input type="checkbox" id="indemnityAgreement" name="indemnityAgreement" required>
                                <span class="checkmark"></span>
                                <strong>I have read, understood, and agree to the indemnity and permission terms</strong>
                            </label>
                        </div>
                    </div>
                </fieldset>

                <!-- Form Validation and Submission -->
                <fieldset class="form-section">
                    <legend><i class="fas fa-check-circle"></i> SECTION 9: FORM VALIDATION AND SUBMISSION</legend>
                    
                    <!-- Validation Errors Display Area -->
                    <div id="validationErrors" class="validation-errors" style="display: none;">
                        <h4><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h4>
                        <ul id="errorList"></ul>
                    </div>
                    
                    <!-- Terms and Conditions - Highly Visible -->
                    <div class="terms-section">
                        <div class="terms-header">
                            <i class="fas fa-file-contract"></i>
                            <h3>Admission Terms and Conditions</h3>
                        </div>
                        
                        <div class="terms-content">
                            <div class="terms-box">
                                <h4>Important - Please Read Carefully</h4>
                                <p>By submitting this admission application, you agree to the following terms:</p>
                                <ul>
                                    <li><i class="fas fa-check-circle"></i> All information provided is accurate and complete</li>
                                    <li><i class="fas fa-check-circle"></i> False information may result in application rejection</li>
                                    <li><i class="fas fa-check-circle"></i> You consent to background checks if required</li>
                                    <li><i class="fas fa-check-circle"></i> You agree to follow all creche policies and procedures</li>
                                    <li><i class="fas fa-check-circle"></i> You authorize emergency medical treatment if needed</li>
                                    <li><i class="fas fa-check-circle"></i> You understand fees and payment terms</li>
                                    <li><i class="fas fa-check-circle"></i> You agree to provide required documentation</li>
                                </ul>
                            </div>
                            
                            <div class="terms-agreement">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="terms" name="terms" required>
                                    <span class="checkmark"></span>
                                    <strong>I have read, understood, and agree to all the terms and conditions above</strong>
                                </label>
                                <p class="terms-note">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    You must accept the terms and conditions to submit your application
                                </p>
                            </div>
                        </div>
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
    
    /* Auto-filled fields styling */
input[readonly] {
    background-color: #f8f9fa;
    border-color: #e9ecef;
    color: #6c757d;
    cursor: not-allowed;
}

input[readonly]:focus {
    outline: none;
    box-shadow: none;
}

/* Parent info alert styling */
.alert-info {
    background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    border: 1px solid #b8daff;
    color: #0c5460;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.alert-info strong {
    color: #0c5460;
    font-weight: 700;
}

/* Terms and Conditions Responsive */
    .terms-section {
        padding: 1rem;
        margin: 1rem 0;
    }
    
    .terms-header {
        flex-direction: column;
        text-align: center;
        gap: 0.3rem;
    }
    
    .terms-box {
        padding: 1rem;
    }
    
    .terms-box li {
        font-size: 0.9rem;
    }
    
    .terms-agreement .checkbox-label {
        font-size: 0.9rem;
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

/* Admission Terms and Conditions Styling */
.terms-section {
    background: linear-gradient(135deg, #fff3cd, #fff8e1);
    border: 2px solid #ffc107;
    border-radius: 15px;
    padding: 1.5rem;
    margin: 2rem 0;
    box-shadow: 0 8px 25px rgba(255, 193, 7, 0.2);
}

.terms-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    color: #856404;
}

.terms-header i {
    font-size: 1.5rem;
    color: #ffc107;
}

.terms-header h3 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 700;
}

.terms-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.terms-box {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    border: 1px solid #ffc107;
}

.terms-box h4 {
    color: #856404;
    margin: 0 0 1rem 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.terms-box p {
    color: #856404;
    margin: 0 0 1rem 0;
    font-weight: 500;
}

.terms-box ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.terms-box li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0;
    color: #856404;
    font-size: 0.95rem;
}

.terms-box li i {
    color: #28a745;
    font-size: 1rem;
}

.terms-agreement {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    border: 1px solid #dee2e6;
}

.terms-agreement .checkbox-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.terms-agreement .checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    accent-color: var(--primary-color);
}

.terms-note {
    margin: 0;
    padding: 0.5rem;
    background: #d1ecf1;
    border-radius: 5px;
    color: #0c5460;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.terms-note i {
    color: #17a2b8;
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
