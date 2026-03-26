<div class="content-wrapper">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-key"></i> Forgot Password</h1>
                <p>Reset your Tiny Tots Creche password</p>
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
            
            <form id="forgotPasswordForm" method="POST" action="/forgot-password" class="login-form">
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" id="email" name="email" required 
                           autocomplete="email" placeholder="Enter your registered email address"
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-full" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> 
                        <span id="submitBtnText">Send Reset Link</span>
                    </button>
                </div>
            </form>
            
            <div class="login-help">
                <div class="help-section">
                    <h4><i class="fas fa-info-circle"></i> Password Reset Process</h4>
                    <div class="process-steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h5>Enter Email</h5>
                                <p>Enter the email address associated with your account</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h5>Check Email</h5>
                                <p>You'll receive a password reset link in your email</p>
                            </div>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h5>Reset Password</h5>
                                <p>Click the link in the email to set a new password</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="help-section">
                    <h4><i class="fas fa-shield-alt"></i> Security Information</h4>
                    <div class="security-info">
                        <ul>
                            <li><i class="fas fa-check"></i> Reset links expire after 2 hours</li>
                            <li><i class="fas fa-check"></i> Links can only be used once</li>
                            <li><i class="fas fa-check"></i> Your account remains secure during reset</li>
                            <li><i class="fas fa-check"></i> We'll never ask for your password</li>
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
/* Forgot Password Styles */
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

/* Process Steps */
.process-steps {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.step {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: var(--warm-white);
    border-radius: 10px;
    border-left: 3px solid var(--primary-color);
}

.step-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    flex-shrink: 0;
}

.step-content h5 {
    color: var(--text-dark);
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
    font-weight: 600;
}

.step-content p {
    color: var(--text-light);
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.4;
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
    
    .step {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .contact-info {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const emailInput = document.getElementById('email');
    
    // Basic client-side validation
    if (emailInput.value.trim() === '') {
        e.preventDefault();
        showError('Please enter your email address');
        return;
    }
    
    if (!emailInput.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        e.preventDefault();
        showError('Please enter a valid email address');
        return;
    }
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtnText.textContent = 'Sending...';
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
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
    
    const form = document.getElementById('forgotPasswordForm');
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

// Auto-focus on email field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('email').focus();
});
</script>
