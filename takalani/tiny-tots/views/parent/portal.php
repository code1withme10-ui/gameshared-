<?php
// Helper function for progress percentage
function getProgressPercentage($status) {
    switch ($status) {
        case 'pending':
            return 25;
        case 'under-review':
            return 50;
        case 'approved':
            return 100;
        case 'rejected':
            return 0;
        default:
            return 25;
    }
}
?>

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

    <!-- Application Status Section -->
    <section class="applications-section">
        <div class="container">
            <h2>📋 Your Applications</h2>
            
            <?php if (empty($applications)): ?>
                <div class="no-applications">
                    <div class="no-applications-card">
                        <i class="fas fa-file-alt"></i>
                        <h3>No Applications Yet</h3>
                        <p>You haven't submitted any admission applications yet.</p>
                        <a href="/admission" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Apply for Admission
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="applications-grid">
                    <?php foreach ($applications as $application): ?>
                        <div class="application-card">
                            <div class="application-header">
                                <div class="application-info">
                                    <h3><?= htmlspecialchars($application['child_name']) ?></h3>
                                    <p class="application-id">ID: <?= htmlspecialchars($application['id']) ?></p>
                                </div>
                                <div class="application-status">
                                    <?php
                                    $statusClass = '';
                                    $statusIcon = '';
                                    switch ($application['status']) {
                                        case 'pending':
                                            $statusClass = 'status-pending';
                                            $statusIcon = 'fas fa-clock';
                                            break;
                                        case 'under-review':
                                            $statusClass = 'status-review';
                                            $statusIcon = 'fas fa-eye';
                                            break;
                                        case 'approved':
                                            $statusClass = 'status-approved';
                                            $statusIcon = 'fas fa-check-circle';
                                            break;
                                        case 'rejected':
                                            $statusClass = 'status-rejected';
                                            $statusIcon = 'fas fa-times-circle';
                                            break;
                                        default:
                                            $statusClass = 'status-pending';
                                            $statusIcon = 'fas fa-clock';
                                    }
                                    ?>
                                    <span class="status-badge <?= $statusClass ?>">
                                        <i class="<?= $statusIcon ?>"></i>
                                        <?= ucfirst(str_replace('-', ' ', $application['status'])) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="application-details">
                                <div class="detail-row">
                                    <span class="label">Age:</span>
                                    <span class="value"><?= htmlspecialchars($application['child_age']) ?> years</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Grade:</span>
                                    <span class="value"><?= htmlspecialchars($application['grade']) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Submitted:</span>
                                    <span class="value"><?= date('M j, Y', strtotime($application['submitted_at'])) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Last Updated:</span>
                                    <span class="value"><?= date('M j, Y', strtotime($application['updated_at'])) ?></span>
                                </div>
                            </div>
                            
                            <div class="application-progress">
                                <div class="progress-header">
                                    <span>Application Progress</span>
                                    <span class="progress-percentage"><?= getProgressPercentage($application['status']) ?>%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?= getProgressPercentage($application['status']) ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="application-actions">
                                <a href="/parent/application-status?id=<?= htmlspecialchars($application['id']) ?>" class="btn btn-outline">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                <?php if ($application['status'] === 'pending'): ?>
                                    <a href="/admission" class="btn btn-secondary">
                                        <i class="fas fa-edit"></i> Edit Application
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
/* Application Status Section */
.applications-section {
    padding: 3rem 0;
    background: var(--warm-white);
    border-radius: 20px;
    margin-bottom: 2rem;
}

.applications-section h2 {
    text-align: center;
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 3rem;
}

.no-applications {
    text-align: center;
    padding: 3rem;
}

.no-applications-card {
    background: white;
    border-radius: 15px;
    padding: 3rem;
    box-shadow: 0 8px 25px var(--shadow-light);
    max-width: 500px;
    margin: 0 auto;
}

.no-applications-card i {
    font-size: 4rem;
    color: var(--text-light);
    margin-bottom: 1rem;
}

.no-applications-card h3 {
    font-size: 1.5rem;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.no-applications-card p {
    color: var(--text-light);
    margin-bottom: 2rem;
}

.applications-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.application-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 25px var(--shadow-light);
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.application-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px var(--shadow-dark);
    border-color: var(--primary-color);
}

.application-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.application-info h3 {
    font-size: 1.3rem;
    color: var(--primary-color);
    margin: 0 0 0.5rem 0;
}

.application-id {
    color: var(--text-light);
    font-size: 0.9rem;
    margin: 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffc107;
}

.status-review {
    background: #cce5ff;
    color: #004085;
    border: 1px solid #007bff;
}

.status-approved {
    background: #d4edda;
    color: #155724;
    border: 1px solid #28a745;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #dc3545;
}

.application-details {
    margin-bottom: 1.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--light-blue);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row .label {
    color: var(--text-light);
    font-weight: 500;
}

.detail-row .value {
    color: var(--text-dark);
    font-weight: 600;
}

.application-progress {
    margin-bottom: 1.5rem;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.progress-header span {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-dark);
}

.progress-percentage {
    color: var(--primary-color);
    font-weight: 600;
}

.progress-bar {
    height: 8px;
    background: var(--light-blue);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    border-radius: 4px;
    transition: width 0.3s ease;
}

.application-actions {
    display: flex;
    gap: 1rem;
}

.application-actions .btn {
    flex: 1;
    text-align: center;
    justify-content: center;
}

@media (max-width: 768px) {
    .applications-grid {
        grid-template-columns: 1fr;
    }
    
    .application-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .application-actions {
        flex-direction: column;
    }
    
    .no-applications-card {
        padding: 2rem;
    }
}
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
