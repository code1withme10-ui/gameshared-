<div class="content-wrapper">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-user-plus"></i> Create Account</h1>
                <p>Join Tiny Tots Creche to access admission forms</p>
                <?php if (isset($_SESSION['redirect_after_login']) && $_SESSION['redirect_after_login'] === '/admission'): ?>
                    <div class="login-purpose">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Register to start your child's admission application</span>
                    </div>
                <?php else: ?>
                    <div class="login-purpose">
                        <i class="fas fa-info-circle"></i>
                        <span>Username will be automatically generated from your name</span>
                    </div>
                <?php endif; ?>
            </div>
            
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
            
            <form id="registerForm" method="POST" action="/register" class="login-form">
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-id-card"></i> Full Name *
                    </label>
                    <input type="text" id="name" name="name" required 
                           autocomplete="name" placeholder="Enter your full name"
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="surname">
                        <i class="fas fa-user"></i> Surname *
                    </label>
                    <input type="text" id="surname" name="surname" required 
                           autocomplete="family-name" placeholder="Enter your surname"
                           value="<?= htmlspecialchars($old['surname'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address *
                    </label>
                    <input type="email" id="email" name="email" required 
                           autocomplete="email" placeholder="your.email@example.com"
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone"></i> Phone Number *
                    </label>
                    <input type="tel" id="phone" name="phone" required 
                           autocomplete="tel" placeholder="Enter your phone number"
                           value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">
                        <i class="fas fa-home"></i> Physical Address *
                    </label>
                    <input type="text" id="address" name="address" required 
                           autocomplete="street-address" placeholder="Enter your physical address"
                           value="<?= htmlspecialchars($old['address'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="city">
                        <i class="fas fa-map-marker-alt"></i> City/Town *
                    </label>
                    <input type="text" id="city" name="city" required 
                           autocomplete="address-level2" placeholder="Enter your city or town"
                           value="<?= htmlspecialchars($old['city'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="province">
                        <i class="fas fa-map"></i> Province *
                    </label>
                    <select id="province" name="province" required>
                        <option value="">Select province</option>
                        <option value="gauteng" <?= ($old['province'] ?? '') === 'gauteng' ? 'selected' : '' ?>>Gauteng</option>
                        <option value="western-cape" <?= ($old['province'] ?? '') === 'western-cape' ? 'selected' : '' ?>>Western Cape</option>
                        <option value="eastern-cape" <?= ($old['province'] ?? '') === 'eastern-cape' ? 'selected' : '' ?>>Eastern Cape</option>
                        <option value="northern-cape" <?= ($old['province'] ?? '') === 'northern-cape' ? 'selected' : '' ?>>Northern Cape</option>
                        <option value="free-state" <?= ($old['province'] ?? '') === 'free-state' ? 'selected' : '' ?>>Free State</option>
                        <option value="kwazulu-natal" <?= ($old['province'] ?? '') === 'kwazulu-natal' ? 'selected' : '' ?>>KwaZulu-Natal</option>
                        <option value="mpumalanga" <?= ($old['province'] ?? '') === 'mpumalanga' ? 'selected' : '' ?>>Mpumalanga</option>
                        <option value="limpopo" <?= ($old['province'] ?? '') === 'limpopo' ? 'selected' : '' ?>>Limpopo</option>
                        <option value="north-west" <?= ($old['province'] ?? '') === 'north-west' ? 'selected' : '' ?>>North West</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="postal-code">
                        <i class="fas fa-mail-bulk"></i> Postal Code *
                    </label>
                    <input type="text" id="postal-code" name="postal_code" required 
                           autocomplete="postal-code" placeholder="Enter postal code"
                           value="<?= htmlspecialchars($old['postal_code'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="id-number">
                        <i class="fas fa-id-card"></i> ID/Passport Number *
                    </label>
                    <input type="text" id="id-number" name="id_number" required 
                           autocomplete="off" placeholder="Enter South African ID or Passport number"
                           value="<?= htmlspecialchars($old['id_number'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="relationship">
                        <i class="fas fa-heart"></i> Relationship to Child *
                    </label>
                    <select id="relationship" name="relationship" required>
                        <option value="">Select relationship</option>
                        <option value="mother" <?= ($old['relationship'] ?? '') === 'mother' ? 'selected' : '' ?>>Mother</option>
                        <option value="father" <?= ($old['relationship'] ?? '') === 'father' ? 'selected' : '' ?>>Father</option>
                        <option value="guardian" <?= ($old['relationship'] ?? '') === 'guardian' ? 'selected' : '' ?>>Legal Guardian</option>
                        <option value="grandparent" <?= ($old['relationship'] ?? '') === 'grandparent' ? 'selected' : '' ?>>Grandparent</option>
                        <option value="aunt" <?= ($old['relationship'] ?? '') === 'aunt' ? 'selected' : '' ?>>Aunt</option>
                        <option value="uncle" <?= ($old['relationship'] ?? '') === 'uncle' ? 'selected' : '' ?>>Uncle</option>
                        <option value="sibling" <?= ($old['relationship'] ?? '') === 'sibling' ? 'selected' : '' ?>>Sibling</option>
                        <option value="other" <?= ($old['relationship'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
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
                                <strong>I have read and agree to the Terms and Conditions</strong>
                            </label>
                            <?php if (isset($errors['terms'])): ?>
                                <div class="alert alert-error">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <?= htmlspecialchars($errors['terms']) ?>
                                </div>
                            <?php endif; ?>
                            <p class="terms-note">
                                <i class="fas fa-exclamation-triangle"></i>
                                You must accept the terms and conditions to create an account
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-full" id="submitBtn" disabled>
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>
                </div>
            </form>
            
            <div class="login-footer">
                <p>Already have an account? <a href="/login">Login here</a></p>
                <p><a href="/">Back to Home</a></p>
            </div>
        </div>
    </div>
</div>

<style>
/* Register Page Styles */
.login-container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.login-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
    overflow: hidden;
}

.login-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 2.5rem;
    text-align: center;
}

.login-header h1 {
    margin: 0 0 0.5rem 0;
    font-size: 2rem;
    font-weight: 600;
}

.login-header p {
    margin: 0;
    font-size: 1rem;
    opacity: 0.9;
}

.login-form {
    padding: 2.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
    position: relative;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-dark);
}

.form-group label i {
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.8rem;
    border: 2px solid var(--light-blue);
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(135, 206, 235, 0.1);
}

.form-actions {
    margin-top: 2rem;
}

.btn-full {
    width: 100%;

.alert-error li {
    margin-bottom: 0.3rem;
}

.login-footer {
    background: linear-gradient(135deg, var(--warm-white), var(--light-blue));
    padding: 2rem;
    text-align: center;
}

.login-footer p {
    margin: 0.3rem 0;
    font-size: 0.9rem;
}

.login-footer a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.login-footer a:hover {
    text-decoration: underline;
}

/* Terms and Conditions Styling */
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

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    accent-color: var(--primary-color);
    cursor: pointer;
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

#submitBtn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    opacity: 0.6;
}

#submitBtn:disabled:hover {
    background: #6c757d;
    transform: none;
}

@media (max-width: 768px) {
    .login-container {
        margin: 1rem auto;
    }
    
    .login-header {
        padding: 2rem 1.5rem;
    }
    
    .login-form {
        padding: 2rem 1.5rem;
    }
    
    .login-footer {
        padding: 1.5rem;
    }
    
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
    
    .checkbox-label {
        font-size: 0.9rem;
    }
}
</style>

<!-- Terms and Conditions JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const termsCheckbox = document.getElementById('terms');
    const submitBtn = document.getElementById('submitBtn');
    
    termsCheckbox.addEventListener('change', function() {
        submitBtn.disabled = !this.checked;
    });
});
</script>
