<?php
// Get parent's admission applications using the proper MVC structure
require_once MODELS_PATH . '/AdmissionModel.php';
$admissionModel = new AdmissionModel();
$parentApplications = $admissionModel->getApplicationsByParent($_SESSION['user']['id']);

// Debug: Log the parent ID and applications found
error_log("Parent ID: " . $_SESSION['user']['id']);
error_log("Applications found: " . count($parentApplications));
foreach ($parentApplications as $app) {
    error_log("Application: " . $app['id'] . " - " . ($app['child_name'] ?? $app['childFirstName'] . ' ' . $app['childSurname']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Portal - Tiny Tots Creche</title>
    <link rel="stylesheet" href="/public/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php require_once VIEWS_PATH . '/layouts/header.php'; ?>

    <div class="content-wrapper">
        <!-- Welcome Section -->
        <section class="welcome-section">
            <div class="container">
                <div class="welcome-content">
                    <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</h1>
                    <p>Your Parent Portal Dashboard</p>
                    
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
                
                <?php if (empty($parentApplications)): ?>
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
                        <?php foreach ($parentApplications as $application): ?>
                            <div class="application-card">
                                <div class="application-header">
                                    <div class="application-info">
                                        <h3><?= htmlspecialchars($application['child_name'] ?? $application['childFirstName'] . ' ' . $application['childSurname']) ?></h3>
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
                                        <span class="label">Child's Full Name:</span>
                                        <span class="value"><?= htmlspecialchars($application['child_name'] ?? $application['childFirstName'] . ' ' . $application['childSurname']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Age:</span>
                                        <span class="value"><?= htmlspecialchars($application['child_age'] ?? $application['age']) ?> years</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Grade:</span>
                                        <span class="value"><?= htmlspecialchars($application['grade'] ?? $application['gradeApplyingFor']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Gender:</span>
                                        <span class="value"><?= htmlspecialchars($application['childGender']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Date of Birth:</span>
                                        <span class="value"><?= date('M j, Y', strtotime($application['dateOfBirth'])) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Parent/Guardian:</span>
                                        <span class="value"><?= htmlspecialchars($application['parentFirstName'] . ' ' . $application['parentSurname']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Contact Number:</span>
                                        <span class="value"><?= htmlspecialchars($application['contactNumber']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Email Address:</span>
                                        <span class="value"><?= htmlspecialchars($application['emailAddress']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Residential Address:</span>
                                        <span class="value"><?= htmlspecialchars($application['residentialAddress']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Relationship to Child:</span>
                                        <span class="value"><?= htmlspecialchars($application['relationshipToChild']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Emergency Contact:</span>
                                        <span class="value"><?= htmlspecialchars($application['emergencyContactName']) ?> (<?= htmlspecialchars($application['emergencyContactPhone']) ?>)</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Transportation:</span>
                                        <span class="value"><?= htmlspecialchars($application['transportation']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Special Needs:</span>
                                        <span class="value"><?= htmlspecialchars($application['specialNeeds']) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Application Status:</span>
                                        <span class="value">
                                            <span class="status-badge <?= $statusClass ?>">
                                                <i class="<?= $statusIcon ?>"></i>
                                                <?= ucfirst(str_replace('-', ' ', $application['status'])) ?>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Submitted Date:</span>
                                        <span class="value"><?= date('M j, Y H:i', strtotime($application['submitted_at'] ?? $application['submittedAt'])) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label">Last Updated:</span>
                                        <span class="value"><?= date('M j, Y H:i', strtotime($application['updated_at'] ?? $application['submitted_at'] ?? $application['submittedAt'])) ?></span>
                                    </div>
                                </div>
                                
                                <div class="application-actions">
                                    <a href="#" class="btn btn-secondary" onclick="alert('Full application details would be shown here')">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <a href="admission.php" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> New Application
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
</body>
</html>
