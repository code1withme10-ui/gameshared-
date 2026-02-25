<div class="content-wrapper">
    <div class="create-user-container">
        <div class="form-header">
            <h1><i class="fas fa-user-plus"></i> Create User</h1>
            <p>Add a new user account to the system</p>
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
        
        <form id="createUserForm" method="POST" action="/admin/create-user" class="user-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Username *
                    </label>
                    <input type="text" id="username" name="username" required 
                           autocomplete="username" placeholder="Choose a username"
                           value="<?= htmlspecialchars($old['username'] ?? '') ?>">
                    <small class="form-help">Username must be unique</small>
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password *
                    </label>
                    <input type="password" id="password" name="password" required 
                           autocomplete="new-password" placeholder="Create a password">
                    <small class="form-help">Minimum 6 characters</small>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i> Confirm Password *
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           autocomplete="new-password" placeholder="Confirm password">
                </div>
                
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-id-card"></i> Full Name *
                    </label>
                    <input type="text" id="name" name="name" required 
                           placeholder="Enter full name"
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address *
                    </label>
                    <input type="email" id="email" name="email" required 
                           autocomplete="email" placeholder="email@example.com"
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
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Create User
                </button>
                <a href="/admin/users" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
        </form>
    </div>
</div>

<style>
/* Create User Form Styles */
.create-user-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.form-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 2.5rem;
    border-radius: 20px 20px 0 0;
    text-align: center;
}

.form-header h1 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.form-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

.user-form {
    background: white;
    padding: 3rem;
    border-radius: 0 0 20px 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
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
    display: flex;
    align-items: center;
}

.form-group label i {
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.form-group input,
.form-group select {
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

.form-help {
    font-size: 0.85rem;
    color: var(--text-light);
    margin-top: 0.5rem;
    font-style: italic;
}

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

.btn-primary {
    background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
    color: var(--text-dark);
    box-shadow: 0 4px 15px var(--shadow-light);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--shadow-medium);
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

.alert {
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
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

@media (max-width: 768px) {
    .create-user-container {
        margin: 1rem auto;
        padding: 0 0.5rem;
    }
    
    .form-header {
        padding: 2rem 1.5rem;
    }
    
    .user-form {
        padding: 2rem 1.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
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
}
</style>
