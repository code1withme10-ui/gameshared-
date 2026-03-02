<div class="content-wrapper">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-key"></i> Reset Password</h1>
                <p>Set your new Tiny Tots Creche password</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <form id="resetPasswordForm" method="POST" action="/reset-password?token=<?= htmlspecialchars($token) ?>&email=<?= htmlspecialchars($email) ?>" class="login-form">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> New Password
                    </label>
                    <input type="password" id="password" name="password" required 
                           autocomplete="new-password" placeholder="Enter your new password">
                    <div class="password-toggle">
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i> Confirm New Password
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           autocomplete="new-password" placeholder="Confirm your new password">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-full" id="submitBtn">
                        <i class="fas fa-check"></i> 
                        <span id="submitBtnText">Reset Password</span>
                    </button>
                </div>
            </form>
            
            <div class="login-help">
                <div class="help-section">
                    <h4><i class="fas fa-shield-alt"></i> Password Requirements</h4>
                    <div class="password-requirements">
                        <ul>
                            <li><i class="fas fa-check"></i> At least 6 characters long</li>
                            <li><i class="fas fa-check"></i> Contains at least one uppercase letter</li>
                            <li><i class="fas fa-check"></i> Contains at least one lowercase letter</li>
                            <li><i class="fas fa-check"></i> Contains at least one number</li>
                            <li><i class="fas fa-check"></i> Should not be a common password</li>
                        </ul>
                    </div>
                </div>
                
                <div class="help-section">
                    <h4><i class="fas fa-info-circle"></i> Security Notice</h4>
                    <div class="security-info">
                        <ul>
                            <li><i class="fas fa-exclamation-triangle"></i> This reset link will expire in 2 hours</li>
                            <li><i class="fas fa-exclamation-triangle"></i> For your security, make sure no one is watching your screen</li>
                            <li><i class="fas fa-exclamation-triangle"></i> Create a strong, unique password</li>
                        </ul>
                    </div>
                </div>
                
                <div class="login-footer">
                    <p><a href="/login" class="back-link">
                        <i class="fas fa-arrow-left"></i> Back to Login
                    </a></p>
                    <p>Remember your password? <a href="/login">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Reset Password Styles */
.login-container {
    max-width: 500px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.login-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
    overflow: hidden;
    animation: slideUp 0.5s ease-out;
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
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-group label i {
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.form-group input {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--light-blue);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--warm-white);
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 8px rgba(135, 206, 235, 0.3);
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 2.8rem;
    cursor: pointer;
    color: var(--text-light);
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: var(--primary-color);
}

.form-actions {
    margin-top: 2rem;
}

.btn-full {
    width: 100%;
    padding: 1rem 2rem;
    border: none;
    border-radius: 25px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
    color: var(--text-dark);
    box-shadow: 0 4px 15px var(--shadow-light);
}

.btn-full:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--shadow-medium);
}

.btn-full:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Alert Styles */
.alert {
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border-left: 5px solid #dc3545;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 5px solid #28a745;
}

/* Password Requirements */
.password-requirements {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid var(--primary-color);
}

.password-requirements ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.password-requirements li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0;
    color: var(--text-dark);
    font-size: 0.9rem;
}

.password-requirements li i {
    color: var(--primary-color);
    font-size: 0.8rem;
}

/* Security Notice */
.security-info {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 10px;
    padding: 1.5rem;
}

.security-info ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.security-info li {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0;
    color: #856404;
    font-size: 0.9rem;
}

.security-info li i {
    color: #ffc107;
    font-size: 0.8rem;
    margin-right: 0.3rem;
}

/* Help Section Styles */
.login-help {
    background: linear-gradient(135deg, var(--warm-white), var(--light-blue));
    padding: 2rem;
}

.help-section {
    margin-bottom: 1.5rem;
}

.help-section:last-child {
    margin-bottom: 0;
}

.help-section h4 {
    color: var(--secondary-color);
    margin: 0 0 1rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.login-footer {
    text-align: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(0,0,0,0.1);
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

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: 1px solid var(--primary-color);
    border-radius: 20px;
    background: var(--warm-white);
    transition: all 0.3s ease;
}

.back-link:hover {
    background: var(--primary-color);
    color: white;
}

.login-footer a:hover {
    text-decoration: underline;
}

/* Responsive Design */
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
    
    .login-help {
        padding: 1.5rem;
    }
}
</style>

<script>
// Password visibility toggle
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    const toggleIcon = document.getElementById('togglePassword');
    
    const isPasswordVisible = passwordInput.type === 'text';
    
    passwordInput.type = isPasswordVisible ? 'password' : 'text';
    confirmInput.type = isPasswordVisible ? 'password' : 'text';
    
    toggleIcon.classList.remove('fa-eye', 'fa-eye-slash');
    toggleIcon.classList.add(isPasswordVisible ? 'fa-eye' : 'fa-eye-slash');
});

// Form submission with validation
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    
    // Password validation
    if (passwordInput.value.length < 6) {
        e.preventDefault();
        showError('Password must be at least 6 characters long');
        return;
    }
    
    if (!passwordInput.value.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/)) {
        e.preventDefault();
        showError('Password must contain at least one uppercase letter, one lowercase letter, and one number');
        return;
    }
    
    if (passwordInput.value !== confirmInput.value) {
        e.preventDefault();
        showError('Passwords do not match');
        return;
    }
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtnText.textContent = 'Resetting...';
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Resetting...';
});

function showError(message) {
    const existingError = document.querySelector('.alert-error');
    if (existingError) {
        existingError.remove();
    }
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-error';
    errorDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle"></i>
        ${message}
    `;
    
    const form = document.getElementById('resetPasswordForm');
    form.parentNode.insertBefore(errorDiv, form);
    
    // Scroll to error
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    // Auto-remove error after 5 seconds
    setTimeout(() => {
        if (errorDiv.parentNode) {
            errorDiv.remove();
        }
    }, 5000);
}

// Auto-focus on password field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('password').focus();
});
</script>
