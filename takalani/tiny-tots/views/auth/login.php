<div class="content-wrapper">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-sign-in-alt"></i> Login</h1>
                <p>Access your Tiny Tots Creche portal</p>
            </div>
            
            <form id="loginForm" method="POST" action="/login" class="login-form">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input type="text" id="username" name="username" required 
                           autocomplete="username" placeholder="Enter your username"
                           value="<?= htmlspecialchars($old['username'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="password" name="password" required 
                           autocomplete="current-password" placeholder="Enter your password">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </div>
            </form>
            
            <div class="login-help">
                <div class="help-section">
                    <h4><i class="fas fa-info-circle"></i> Login Information</h4>
                    <div class="login-info">
                        <div class="info-item">
                            <strong>👨‍💼 Headmaster:</strong>
                            <p>Username: <code>admin</code></p>
                            <p>Password: <code>admin123</code></p>
                        </div>
                        <div class="info-item">
                            <strong>👨‍👩‍👧‍👦 Parents:</strong>
                            <p>Use the credentials provided during registration</p>
                        </div>
                    </div>
                </div>
                
                <div class="help-section">
                    <h4><i class="fas fa-question-circle"></i> Need Help?</h4>
                    <p>Contact us at:</p>
                    <p><strong>📞 081 421 0084</strong></p>
                    <p><strong>📧 mollerv40@gmail.com</strong></p>
                </div>
                
                <div class="login-footer">
                    <p>Don't have an account? <a href="/register">Register here</a></p>
                    <p><a href="/forgot-password">Forgot your password?</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Login Page Styles */
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
}

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
}

.login-info {
    display: grid;
    gap: 1rem;
}

.info-item {
    background: white;
    padding: 1rem;
    border-radius: 10px;
    border-left: 4px solid var(--primary-color);
}

.info-item strong {
    color: var(--primary-color);
    display: block;
    margin-bottom: 0.5rem;
}

.info-item p {
    margin: 0.2rem 0;
    font-size: 0.9rem;
}

.info-item code {
    background: var(--light-blue);
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
}

.help-section p {
    margin: 0.3rem 0;
    color: var(--text-dark);
    font-size: 0.9rem;
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
