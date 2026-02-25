<div class="content-wrapper">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-user-plus"></i> Register</h1>
                <p>Create your Tiny Tots Creche account</p>
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
                    <label for="username">
                        <i class="fas fa-user"></i> Username *
                    </label>
                    <input type="text" id="username" name="username" required 
                           autocomplete="username" placeholder="Choose a username"
                           value="<?= htmlspecialchars($old['username'] ?? '') ?>">
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
                
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-id-card"></i> Full Name *
                    </label>
                    <input type="text" id="name" name="name" required 
                           placeholder="Enter your full name"
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>">
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
                    <label for="role">
                        <i class="fas fa-user-tag"></i> Account Type *
                    </label>
                    <select id="role" name="role" required>
                        <option value="">Select account type</option>
                        <option value="parent" <?= ($old['role'] ?? '') === 'parent' ? 'selected' : '' ?>>Parent</option>
                        <option value="headmaster" <?= ($old['role'] ?? '') === 'headmaster' ? 'selected' : '' ?>>Headmaster</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-full">
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
    max-width: 500px;
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

.form-group input,
.form-group select {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--light-blue);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--warm-white);
}

.form-group input:focus,
.form-group select:focus {
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

.alert {
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    border-radius: 10px;
    font-weight: 500;
}

.alert-error {
    background: #ff6b6b;
    color: white;
    border-left: 5px solid #d63031;
}

.alert-error ul {
    margin: 0.5rem 0 0 1.5rem;
    padding: 0;
}

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
}
</style>
