<div class="content-wrapper">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-user-plus"></i> Register New Parent</h1>
                <p>Add a new parent account to the system</p>
                <div class="login-purpose">
                    <i class="fas fa-info-circle"></i>
                    <span>Username will be automatically generated from email</span>
                </div>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errors)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    Please fix the errors below:
                    <ul>
                        <?php foreach ($errors as $field => $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form id="registerForm" method="POST" action="/admin/register-parent" class="login-form">
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-id-card"></i> Full Name *
                    </label>
                    <input type="text" id="name" name="name" required 
                           autocomplete="name" placeholder="Enter your full name"
                           value="<?= htmlspecialchars($old['name'] ?? $_POST['name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="surname">
                        <i class="fas fa-user"></i> Surname *
                    </label>
                    <input type="text" id="surname" name="surname" required 
                           autocomplete="family-name" placeholder="Enter your surname"
                           value="<?= htmlspecialchars($old['surname'] ?? $_POST['surname'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address *
                    </label>
                    <input type="email" id="email" name="email" required 
                           autocomplete="email" placeholder="your.email@example.com"
                           value="<?= htmlspecialchars($old['email'] ?? $_POST['email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone"></i> Phone Number *
                    </label>
                    <input type="tel" id="phone" name="phone" required 
                           autocomplete="tel" placeholder="Enter your phone number"
                           value="<?= htmlspecialchars($old['phone'] ?? $_POST['phone'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">
                        <i class="fas fa-home"></i> Physical Address *
                    </label>
                    <input type="text" id="address" name="address" required 
                           autocomplete="street-address" placeholder="Enter your physical address"
                           value="<?= htmlspecialchars($old['address'] ?? $_POST['address'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="city">
                        <i class="fas fa-map-marker-alt"></i> City/Town *
                    </label>
                    <input type="text" id="city" name="city" required 
                           autocomplete="address-level2" placeholder="Enter your city or town"
                           value="<?= htmlspecialchars($old['city'] ?? $_POST['city'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="province">
                        <i class="fas fa-map"></i> Province *
                    </label>
                    <select id="province" name="province" required>
                        <option value="">Select province</option>
                        <option value="gauteng" <?= (($old['province'] ?? $_POST['province'] ?? '') === 'gauteng' ? 'selected' : '') ?>>Gauteng</option>
                        <option value="western-cape" <?= (($old['province'] ?? $_POST['province'] ?? '') === 'western-cape' ? 'selected' : '') ?>>Western Cape</option>
                        <option value="eastern-cape" <?= (($old['province'] ?? $_POST['province'] ?? '') === 'eastern-cape' ? 'selected' : '') ?>>Eastern Cape</option>
                        <option value="northern-cape" <?= (($old['province'] ?? $_POST['province'] ?? '') === 'northern-cape' ? 'selected' : '') ?>>Northern Cape</option>
                        <option value="free-state" <?= (($old['province'] ?? $_POST['province'] ?? '') === 'free-state' ? 'selected' : '') ?>>Free State</option>
                        <option value="kwazulu-natal" <?= (($old['province'] ?? $_POST['province'] ?? '') === 'kwazulu-natal' ? 'selected' : '') ?>>KwaZulu-Natal</option>
                        <option value="mpumalanga" <?= (($old['province'] ?? $_POST['province'] ?? '') === 'mpumalanga' ? 'selected' : '') ?>>Mpumalanga</option>
                        <option value="limpopo" <?= (($old['province'] ?? $_POST['province'] ?? '') === 'limpopo' ? 'selected' : '') ?>>Limpopo</option>
                        <option value="north-west" <?= (($old['province'] ?? $_POST['province'] ?? '') === 'north-west' ? 'selected' : '') ?>>North West</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="postal-code">
                        <i class="fas fa-mail-bulk"></i> Postal Code *
                    </label>
                    <input type="text" id="postal-code" name="postal_code" required 
                           autocomplete="postal-code" placeholder="Enter postal code"
                           value="<?= htmlspecialchars($old['postal_code'] ?? $_POST['postal_code'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="id-number">
                        <i class="fas fa-id-card"></i> ID/Passport Number *
                    </label>
                    <input type="text" id="id-number" name="id_number" required 
                           autocomplete="off" placeholder="Enter South African ID or Passport number"
                           value="<?= htmlspecialchars($old['id_number'] ?? $_POST['id_number'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="relationship">
                        <i class="fas fa-heart"></i> Relationship to Child *
                    </label>
                    <select id="relationship" name="relationship" required>
                        <option value="">Select relationship</option>
                        <option value="mother" <?= (($old['relationship'] ?? $_POST['relationship'] ?? '') === 'mother' ? 'selected' : '') ?>>Mother</option>
                        <option value="father" <?= (($old['relationship'] ?? $_POST['relationship'] ?? '') === 'father' ? 'selected' : '') ?>>Father</option>
                        <option value="guardian" <?= (($old['relationship'] ?? $_POST['relationship'] ?? '') === 'guardian' ? 'selected' : '') ?>>Legal Guardian</option>
                        <option value="grandparent" <?= (($old['relationship'] ?? $_POST['relationship'] ?? '') === 'grandparent' ? 'selected' : '') ?>>Grandparent</option>
                        <option value="aunt" <?= (($old['relationship'] ?? $_POST['relationship'] ?? '') === 'aunt' ? 'selected' : '') ?>>Aunt</option>
                        <option value="uncle" <?= (($old['relationship'] ?? $_POST['relationship'] ?? '') === 'uncle' ? 'selected' : '') ?>>Uncle</option>
                        <option value="sibling" <?= (($old['relationship'] ?? $_POST['relationship'] ?? '') === 'sibling' ? 'selected' : '') ?>>Sibling</option>
                        <option value="other" <?= (($old['relationship'] ?? $_POST['relationship'] ?? '') === 'other' ? 'selected' : '') ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password *
                    </label>
                    <input type="password" id="password" name="password" required 
                           autocomplete="new-password" placeholder="Create a password">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i> Confirm Password *
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           autocomplete="new-password" placeholder="Confirm your password">
                </div>
                
                <!-- Terms and Conditions - Very Visible -->
                <div class="terms-section">
                    <div class="terms-header">
                        <i class="fas fa-file-contract"></i>
                        <h3>Terms and Conditions</h3>
                    </div>
                    
                    <div class="terms-content">
                        <div class="terms-box">
                            <h4>Important - Please Read Carefully</h4>
                            <p>By registering with Tiny Tots Creche, you agree to:</p>
                            <ul>
                                <li><i class="fas fa-check-circle"></i> Provide accurate and truthful information</li>
                                <li><i class="fas fa-check-circle"></i> Keep your login credentials secure</li>
                                <li><i class="fas fa-check-circle"></i> Follow all creche policies and procedures</li>
                                <li><i class="fas fa-check-circle"></i> Update us on any changes to your contact information</li>
                                <li><i class="fas fa-check-circle"></i> Allow us to contact you regarding your child's education</li>
                            </ul>
                        </div>
                        
                        <div class="terms-agreement">
                            <label class="checkbox-label">
                                <input type="checkbox" id="terms" name="terms" required>
                                <span class="checkmark"></span>
                                <span class="checkbox-text">I have read and agree to the Terms and Conditions</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-user-plus"></i> Register Parent Account
                    </button>
                </div>
            </form>
            
            <div class="form-footer">
                <p><i class="fas fa-arrow-left"></i> <a href="/admin/dashboard">Back to Admin Dashboard</a></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        const existingErrors = form.querySelectorAll('.error-message');
        existingErrors.forEach(error => error.remove());
        
        let hasErrors = false;
        
        // Validate required fields
        const requiredFields = [
            'name', 'surname', 'email', 'phone', 'address', 'city', 
            'province', 'postal_code', 'id_number', 'relationship', 
            'password', 'confirm_password', 'terms'
        ];
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                if (fieldName === 'terms') {
                    if (!field.checked) {
                        showError(field, 'You must agree to the Terms and Conditions');
                        hasErrors = true;
                    }
                } else {
                    if (!field.value.trim()) {
                        showError(field, 'This field is required');
                        hasErrors = true;
                    }
                }
            }
        });
        
        // Validate email format
        const emailField = document.getElementById('email');
        if (emailField && emailField.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value)) {
                showError(emailField, 'Please enter a valid email address');
                hasErrors = true;
            }
        }
        
        // Validate ID number (8-13 digits)
        const idField = document.getElementById('id_number');
        if (idField && idField.value) {
            const idRegex = /^\d{8,13}$/;
            if (!idRegex.test(idField.value)) {
                showError(idField, 'ID number must be 8-13 digits');
                hasErrors = true;
            }
        }
        
        // Validate phone number
        const phoneField = document.getElementById('phone');
        if (phoneField && phoneField.value) {
            const phoneRegex = /^[\d\s\-\+\(\)]+$/;
            if (!phoneRegex.test(phoneField.value)) {
                showError(phoneField, 'Please enter a valid phone number');
                hasErrors = true;
            }
        }
        
        // Validate password match
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('confirm_password');
        if (passwordField && confirmPasswordField) {
            if (passwordField.value !== confirmPasswordField.value) {
                showError(confirmPasswordField, 'Passwords do not match');
                hasErrors = true;
            }
            
            if (passwordField.value.length < 6) {
                showError(passwordField, 'Password must be at least 6 characters');
                hasErrors = true;
            }
        }
        
        if (!hasErrors) {
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registering...';
            
            // Submit form
            form.submit();
        }
    });
    
    function showError(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        errorDiv.style.cssText = 'color: #dc3545; font-size: 0.875rem; margin-top: 5px; display: flex; align-items: center; gap: 5px;';
        
        field.parentNode.appendChild(errorDiv);
        field.style.borderColor = '#dc3545';
        
        // Remove error when user starts typing
        field.addEventListener('input', function() {
            errorDiv.remove();
            field.style.borderColor = '';
        });
    }
});
</script>
