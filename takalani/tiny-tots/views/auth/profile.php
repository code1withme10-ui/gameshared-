<div class="content-wrapper">
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <h1><i class="fas fa-user-edit"></i> My Profile</h1>
                <p>Manage your account information</p>
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
            
            <form id="profileForm" method="POST" action="/profile" class="profile-form">
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-id-card"></i> Full Name *
                    </label>
                    <input type="text" id="name" name="name" required 
                           value="<?= htmlspecialchars($old['name'] ?? $user['name']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address *
                    </label>
                    <input type="email" id="email" name="email" required 
                           value="<?= htmlspecialchars($old['email'] ?? $user['email']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input type="text" id="username" name="username" readonly
                           value="<?= htmlspecialchars($user['username']) ?>">
                    <small class="form-help">Username cannot be changed</small>
                </div>
                
                <div class="form-group">
                    <label for="role">
                        <i class="fas fa-user-tag"></i> Account Type
                    </label>
                    <input type="text" id="role" name="role" readonly
                           value="<?= htmlspecialchars(ucfirst($user['role'])) ?>">
                    <small class="form-help">Contact administrator to change account type</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                    <a href="/logout" class="btn btn-outline">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </form>
        </div>
        
        <div class="account-info">
            <div class="info-card">
                <h3><i class="fas fa-info-circle"></i> Account Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Member Since:</label>
                        <span><?= date('F j, Y', strtotime($user['created_at'])) ?></span>
                    </div>
                    <div class="info-item">
                        <label>Last Login:</label>
                        <span><?= date('F j, Y g:i a') ?></span>
                    </div>
                    <div class="info-item">
                        <label>Account Status:</label>
                        <span class="status-active">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile Page Styles */
.profile-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1rem;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.profile-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
    overflow: hidden;
}

.profile-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 2.5rem;
    text-align: center;
}

.profile-header h1 {
    margin: 0 0 0.5rem 0;
    font-size: 2rem;
    font-weight: 600;
}

.profile-header p {
    margin: 0;
    font-size: 1rem;
    opacity: 0.9;
}

.profile-form {
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

.form-group input[readonly] {
    background: var(--light-blue);
    cursor: not-allowed;
}

.form-help {
    display: block;
    color: var(--text-light);
    font-size: 0.85rem;
    margin-top: 0.5rem;
    font-style: italic;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--light-blue);
}

.account-info {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.info-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 8px 25px var(--shadow-light);
}

.info-card h3 {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
    font-weight: 600;
}

.info-card h3 i {
    margin-right: 0.8rem;
}

.info-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 0;
    border-bottom: 1px solid var(--light-blue);
}

.info-item:last-child {
    border-bottom: none;
}

.info-item label {
    color: var(--text-light);
    font-weight: 500;
}

.info-item span {
    color: var(--text-dark);
    font-weight: 600;
}

.status-active {
    color: var(--success-green);
    background: rgba(81, 207, 102, 0.1);
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.85rem;
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

@media (max-width: 768px) {
    .profile-container {
        grid-template-columns: 1fr;
        gap: 2rem;
        margin: 1rem auto;
    }
    
    .profile-header {
        padding: 2rem 1.5rem;
    }
    
    .profile-form {
        padding: 2rem 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>
