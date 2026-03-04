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
