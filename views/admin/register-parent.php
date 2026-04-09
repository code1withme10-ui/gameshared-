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
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--warm-white);
    color: var(--text-dark);
    line-height: 1.6;
}

/* Login Container */
.login-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
}

/* Login Card */
.login-card {
    background: white;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 8px 25px var(--shadow-light);
    border: 1px solid var(--light-blue);
}

/* Login Header */
.login-header {
    text-align: center;
    margin-bottom: 30px;
}

.login-header h1 {
    color: var(--primary-color);
    margin-bottom: 10px;
    font-size: 2.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.login-header h1 i {
    font-size: 0.9em;
}

.login-header p {
    color: var(--text-light);
    font-size: 1.1rem;
    margin: 0 0 20px 0;
}

/* Login Purpose */
.login-purpose {
    background: var(--light-blue);
    padding: 15px 20px;
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary-color);
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.login-purpose i {
    color: var(--primary-color);
    font-size: 1.2em;
}

.login-purpose span {
    color: var(--text-dark);
    font-weight: 500;
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

.alert-error {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.alert i {
    font-size: 1.1em;
    flex-shrink: 0;
}

/* Form Styles */
.login-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    position: relative;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
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
.form-group select,
.form-group textarea {
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
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(135, 206, 235, 0.2);
    transform: translateY(-1px);
}

/* Form Help */
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

/* Checkbox Styles */
.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    font-weight: 500;
    color: var(--text-dark);
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin: 0;
    cursor: pointer;
}

.checkbox-text {
    flex: 1;
    line-height: 1.4;
}

/* Terms Section */
.terms-section {
    background: var(--light-blue);
    padding: 20px;
    border-radius: var(--border-radius);
    margin: 20px 0;
    border: 1px solid var(--primary-color);
}

.terms-section h3 {
    color: var(--text-dark);
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.terms-list {
    list-style: none;
    padding: 0;
}

.terms-list li {
    padding: 8px 0;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 10px;
}

.terms-list li i {
    color: var(--success-green);
    font-size: 0.9em;
    width: 20px;
}

/* Terms Agreement */
.terms-agreement {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid var(--light-blue);
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-top: 30px;
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

.btn-primary:disabled {
    background: var(--text-light);
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-block {
    width: 100%;
    justify-content: center;
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

/* Form Footer */
.form-footer {
    text-align: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid var(--light-blue);
}

.form-footer p {
    color: var(--text-light);
    margin: 0;
}

.form-footer a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.form-footer a:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .login-container {
        padding: 10px;
        margin: 20px auto;
    }
    
    .login-card {
        padding: 25px;
    }
    
    .login-header h1 {
        font-size: 2rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .login-header h1 {
        font-size: 1.5rem;
    }
    
    .login-card {
        padding: 20px;
    }
    
    .btn {
        padding: 12px 20px;
        font-size: 0.9rem;
    }
}
</style>
