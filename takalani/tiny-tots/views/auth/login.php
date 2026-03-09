<div class="content-wrapper">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-sign-in-alt"></i> Login Required</h1>
                <p>Access the Tiny Tots Creche admission system</p>
                <?php if (isset($_SESSION['redirect_after_login']) && $_SESSION['redirect_after_login'] === '/admission'): ?>
                    <div class="login-purpose">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Login to complete your child's admission application</span>
                    </div>
                <?php endif; ?>
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
            
            <form id="loginForm" method="POST" action="/login" class="login-form">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Username or Email
                    </label>
                    <input type="text" id="username" name="username" required 
                           autocomplete="username" placeholder="Enter your username or email"
                           value="<?= htmlspecialchars($old['username'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="password" name="password" required 
                           autocomplete="current-password" placeholder="Enter your password">
                    <div class="password-toggle">
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="remember-forgot">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" id="remember">
                            <span class="checkmark"></span>
                            Remember me
                        </label>
                        <a href="/forgot-password" class="forgot-link">Forgot Password?</a>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-full" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i> 
                        <span id="loginBtnText">Login</span>
                    </button>
                </div>
            </form>
            
            <div class="login-help">
                <div class="help-section">
                    <h4><i class="fas fa-info-circle"></i> User Roles</h4>
                    <div class="role-info">
                        <div class="role-item">
                            <div class="role-icon headmaster">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="role-details">
                                <h5>Headmaster</h5>
                                <p>Full access to admissions and system management</p>
                            </div>
                        </div>
                        <div class="role-item">
                            <div class="role-icon parent">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="role-details">
                                <h5>Parent</h5>
                                <p>Access to child information and communication</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="help-section">
                    <h4><i class="fas fa-shield-alt"></i> Security Information</h4>
                    <div class="security-info">
                        <ul>
                            <li><i class="fas fa-check"></i> All login attempts are monitored and logged</li>
                            <li><i class="fas fa-check"></i> Secure password storage with encryption</li>
                            <li><i class="fas fa-check"></i> Automatic logout after inactivity</li>
                            <li><i class="fas fa-check"></i> POPIA compliant data protection</li>
                        </ul>
                    </div>
                </div>
                
                <div class="help-section">
                    <h4><i class="fas fa-question-circle"></i> Need Help?</h4>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>081 421 0084</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>mollerv40@gmail.com</span>
                        </div>
                    </div>
                </div>
                
                <div class="login-footer">
                    <p>Don't have an account? <a href="/register">Register here</a></p>
                    <p class="security-notice">
                        <i class="fas fa-lock"></i>
                        This is a secure login. Your connection is protected.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Login Page Styles */
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

.login-purpose {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    padding: 0.8rem 1rem;
    background: rgba(255, 215, 0, 0.2);
    border-radius: 10px;
    border-left: 4px solid var(--secondary-color);
    font-size: 0.9rem;
    color: var(--text-dark);
    font-weight: 500;
}

.login-purpose i {
    color: var(--secondary-color);
    font-size: 1rem;
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

.remember-forgot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    color: var(--text-dark);
    font-weight: 500;
}

.checkbox-label input {
    display: none;
}

.checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid var(--primary-color);
    border-radius: 4px;
    margin-right: 0.5rem;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-label input:checked + .checkmark {
    background: var(--primary-color);
}

.checkbox-label input:checked + .checkmark::after {
    content: '✓';
    color: white;
    font-size: 12px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.forgot-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.forgot-link:hover {
    text-decoration: underline;
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

/* Role Information */
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

.role-info {
    display: grid;
    gap: 1rem;
}

.role-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px var(--shadow-light);
    transition: transform 0.3s ease;
}

.role-item:hover {
    transform: translateY(-2px);
}

.role-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.role-icon.headmaster {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.role-icon.parent {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
}

.role-details h5 {
    color: var(--text-dark);
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.role-details p {
    color: var(--text-light);
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Security Information */
.security-info {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #27ae60;
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
    color: var(--text-dark);
    font-size: 0.9rem;
}

.security-info li i {
    color: #27ae60;
    font-size: 0.8rem;
}

/* Contact Information */
.contact-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: white;
    border-radius: 10px;
    border-left: 3px solid var(--primary-color);
}

.contact-item i {
    color: var(--primary-color);
    font-size: 1.1rem;
}

.contact-item span {
    color: var(--text-dark);
    font-weight: 500;
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

.login-footer a:hover {
    text-decoration: underline;
}

.security-notice {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: var(--text-light);
    font-size: 0.85rem;
    font-style: italic;
}

.security-notice i {
    color: #27ae60;
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
    
    .role-item {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .contact-info {
        grid-template-columns: 1fr;
    }
    
    .remember-forgot {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
}
</style>

<script>
// Password visibility toggle
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePassword');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
});

// Form submission with loading state
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const loginBtn = document.getElementById('loginBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    const passwordInput = document.getElementById('password');
    const usernameInput = document.getElementById('username');
    
    // Basic client-side validation
    if (usernameInput.value.trim() === '') {
        e.preventDefault();
        showError('Please enter your username or email');
        return;
    }
    
    if (passwordInput.value.length < 6) {
        e.preventDefault();
        showError('Password must be at least 6 characters long');
        return;
    }
    
    // Show loading state
    loginBtn.disabled = true;
    loginBtnText.textContent = 'Logging in...';
    loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
});

// Show error function
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
    
    const form = document.getElementById('loginForm');
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

// Rate limiting simulation (in production, this would be server-side)
let loginAttempts = 0;
const maxAttempts = 5;
const lockoutDuration = 15 * 60 * 1000; // 15 minutes

document.getElementById('loginForm').addEventListener('submit', function() {
    loginAttempts++;
    
    if (loginAttempts >= maxAttempts) {
        const loginBtn = document.getElementById('loginBtn');
        loginBtn.disabled = true;
        loginBtn.innerHTML = '<i class="fas fa-lock"></i> Account Locked';
        
        showError(`Too many failed attempts. Account locked for ${lockoutDuration/60000} minutes. Please try again later.`);
        
        // In production, this would be handled server-side
        setTimeout(() => {
            loginAttempts = 0;
            loginBtn.disabled = false;
            loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
        }, lockoutDuration);
    }
});

// Auto-focus on username field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('username').focus();
});

// Remember me functionality
document.getElementById('remember').addEventListener('change', function() {
    if (this.checked) {
        // In production, this would set a secure cookie
        console.log('Remember me checked - would set secure cookie');
    }
});
</script>
