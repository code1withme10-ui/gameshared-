<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>

<div class="content-wrapper">
    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="container">
            <div class="welcome-content">
                <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</h1>
                <p>Your Parent Portal Dashboard</p>
                
                <?php if (isset($_SESSION['generated_username'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Registration Successful!</strong><br>
                        Your username is: <strong><?= htmlspecialchars($_SESSION['generated_username']) ?></strong><br>
                        You have been automatically logged in. Please save your username for future reference.
                    </div>
                    <?php unset($_SESSION['generated_username']); ?>
                <?php endif; ?>
                
                <div class="user-info-card">
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="fas fa-user"></i>
                            <div>
                                <strong>Username:</strong><br>
                                <?= htmlspecialchars($_SESSION['user']['username']) ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email:</strong><br>
                                <?= htmlspecialchars($_SESSION['user']['email']) ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>Phone:</strong><br>
                                <?= htmlspecialchars($_SESSION['user']['phone']) ?>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-user-tag"></i>
                            <div>
                                <strong>Account Type:</strong><br>
                                Parent
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="actions-section">
        <div class="container">
            <h2>Quick Actions</h2>
            <div class="actions-grid">
                <a href="/admission" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Apply for Admission</h3>
                    <p>Start your child's admission application</p>
                </a>
                
                <a href="/profile" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <h3>Update Profile</h3>
                    <p>Edit your personal information</p>
                </a>
                
                <a href="/logout" class="action-card logout-card">
                    <div class="action-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <h3>Logout</h3>
                    <p>Sign out of your account</p>
                </a>
                
                <a href="/login" class="action-card login-card">
                    <div class="action-icon">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <h3>Login Again</h3>
                    <p>Test login with your credentials</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Account Information -->
    <section class="account-section">
        <div class="container">
            <h2>Your Account Information</h2>
            <div class="account-details">
                <div class="detail-card">
                    <h3><i class="fas fa-id-card"></i> Personal Details</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <strong>Full Name:</strong> <?= htmlspecialchars($_SESSION['user']['name'] . ' ' . $_SESSION['user']['surname']) ?>
                        </div>
                        <div class="detail-item">
                            <strong>Email:</strong> <?= htmlspecialchars($_SESSION['user']['email']) ?>
                        </div>
                        <div class="detail-item">
                            <strong>Phone:</strong> <?= htmlspecialchars($_SESSION['user']['phone']) ?>
                        </div>
                        <div class="detail-item">
                            <strong>Address:</strong> <?= htmlspecialchars($_SESSION['user']['address']) ?>
                        </div>
                        <div class="detail-item">
                            <strong>City:</strong> <?= htmlspecialchars($_SESSION['user']['city']) ?>
                        </div>
                        <div class="detail-item">
                            <strong>Province:</strong> <?= htmlspecialchars(ucfirst(str_replace('-', ' ', $_SESSION['user']['province']))) ?>
                        </div>
                        <div class="detail-item">
                            <strong>Postal Code:</strong> <?= htmlspecialchars($_SESSION['user']['postal_code']) ?>
                        </div>
                        <div class="detail-item">
                            <strong>ID Number:</strong> <?= htmlspecialchars($_SESSION['user']['id_number']) ?>
                        </div>
                        <div class="detail-item">
                            <strong>Relationship:</strong> <?= htmlspecialchars(ucfirst($_SESSION['user']['relationship'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Parent Portal Styles */
.welcome-section {
    padding: 3rem 0;
    background: linear-gradient(135deg, var(--warm-white), var(--light-blue));
    border-radius: 20px;
    margin-bottom: 2rem;
}

.welcome-content {
    text-align: center;
}

.welcome-content h1 {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.welcome-content p {
    font-size: 1.2rem;
    color: var(--text-light);
    margin-bottom: 2rem;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    border: 1px solid #c3e6cb;
    border-radius: 10px;
    padding: 1.5rem;
    margin: 2rem 0;
    color: #155724;
    text-align: left;
}

.alert-success i {
    color: #28a745;
    margin-right: 0.5rem;
}

.user-info-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 25px var(--shadow-light);
    margin-top: 2rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--warm-white);
    border-radius: 10px;
    border: 1px solid var(--light-blue);
}

.info-item i {
    font-size: 1.5rem;
    color: var(--primary-color);
}

.actions-section {
    padding: 3rem 0;
}

.actions-section h2 {
    text-align: center;
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 3rem;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.action-card {
    display: block;
    background: white;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    text-decoration: none;
    color: var(--text-dark);
    box-shadow: 0 8px 25px var(--shadow-light);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px var(--shadow-dark);
    border-color: var(--primary-color);
}

.action-icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.action-card h3 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
    color: var(--primary-color);
}

.action-card p {
    color: var(--text-light);
    font-size: 0.9rem;
}

.logout-card .action-icon {
    color: #dc3545;
}

.logout-card:hover {
    border-color: #dc3545;
}

.login-card .action-icon {
    color: var(--secondary-color);
}

.login-card:hover {
    border-color: var(--secondary-color);
}

.account-section {
    padding: 3rem 0;
    background: var(--warm-white);
    border-radius: 20px;
    margin-bottom: 2rem;
}

.account-section h2 {
    text-align: center;
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 3rem;
}

.detail-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 25px var(--shadow-light);
}

.detail-card h3 {
    font-size: 1.5rem;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.detail-item {
    padding: 0.8rem;
    background: var(--warm-white);
    border-radius: 8px;
    border: 1px solid var(--light-blue);
}

@media (max-width: 768px) {
    .welcome-content h1 {
        font-size: 2rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
