<div class="content-wrapper">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-content">
            <h1>Parent Documents</h1>
            <p>View all documents uploaded by <?= htmlspecialchars(($parent['firstName'] ?? $parent['name'] ?? 'Unknown') . ' ' . ($parent['surname'] ?? $parent['lastName'] ?? 'Unknown')) ?></p>
        </div>
    </section>

    <div class="admin-container">
        <!-- Parent Information -->
        <div class="parent-info-card">
            <div class="parent-header">
                <div class="parent-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="parent-details">
                    <h2><?= htmlspecialchars(($parent['firstName'] ?? $parent['name'] ?? 'Unknown') . ' ' . ($parent['surname'] ?? $parent['lastName'] ?? 'Unknown')) ?></h2>
                    <div class="parent-meta">
                        <span><i class="fas fa-envelope"></i> <?= htmlspecialchars($parent['email'] ?? 'N/A') ?></span>
                        <span><i class="fas fa-phone"></i> <?= htmlspecialchars($parent['phone'] ?? 'N/A') ?></span>
                        <span><i class="fas fa-id-card"></i> <?= htmlspecialchars($parent['idNumber'] ?? 'N/A') ?></span>
                    </div>
                </div>
                <div class="parent-actions">
                    <a href="/admin/parents-list" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Parents
                    </a>
                    <a href="mailto:<?= htmlspecialchars($parent['email'] ?? '') ?>" class="btn btn-primary">
                        <i class="fas fa-envelope"></i> Contact Parent
                    </a>
                </div>
            </div>
        </div>

        <!-- Applications Summary -->
        <div class="applications-summary">
            <h3><i class="fas fa-folder-open"></i> Applications Summary</h3>
            <div class="app-cards">
                <?php foreach ($applications as $app): ?>
                    <div class="app-card">
                        <div class="app-header">
                            <span class="app-id"><?= htmlspecialchars($app['applicationID']) ?></span>
                            <span class="app-status status-<?= strtolower($app['status']) ?>">
                                <?= htmlspecialchars($app['status']) ?>
                            </span>
                        </div>
                        <div class="app-details">
                            <h4><?= htmlspecialchars($app['child_name'] ?? $app['childFirstName'] . ' ' . $app['childSurname']) ?></h4>
                            <p>Grade: <?= htmlspecialchars($app['grade'] ?? $app['gradeApplyingFor'] ?? 'N/A') ?></p>
                            <p>Submitted: <?= date('M j, Y', strtotime($app['submitted_at'] ?? $app['submittedAt'] ?? 'now')) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="documents-section">
            <h3><i class="fas fa-file-alt"></i> All Documents</h3>
            
            <?php if (empty($documents)): ?>
                <div class="no-documents">
                    <i class="fas fa-inbox"></i>
                    <h4>No Documents Uploaded</h4>
                    <p>This parent hasn't uploaded any documents yet.</p>
                </div>
            <?php else: ?>
                <div class="documents-grid">
                    <?php foreach ($documents as $doc): ?>
                        <div class="document-card">
                            <div class="document-icon">
                                <?php
                                $iconClass = 'fa-file';
                                switch ($doc['type']) {
                                    case 'Birth Certificate':
                                        $iconClass = 'fa-file-pdf';
                                        break;
                                    case 'Parent ID Document':
                                        $iconClass = 'fa-id-card';
                                        break;
                                    case 'Clinical Report':
                                        $iconClass = 'fa-notes-medical';
                                        break;
                                    case 'School Report':
                                        $iconClass = 'fa-graduation-cap';
                                        break;
                                }
                                ?>
                                <i class="fas <?= $iconClass ?>"></i>
                            </div>
                            <div class="document-info">
                                <h4><?= htmlspecialchars($doc['type']) ?></h4>
                                <p class="document-filename"><?= htmlspecialchars($doc['filename']) ?></p>
                                <p class="document-child">Child: <?= htmlspecialchars($doc['child_name']) ?></p>
                                <p class="document-app">Application: <?= htmlspecialchars($doc['application_id']) ?></p>
                                <p class="document-date">Uploaded: <?= date('M j, Y', strtotime($doc['submitted_at'])) ?></p>
                            </div>
                            <div class="document-actions">
                                <a href="<?= htmlspecialchars($doc['path']) ?>" target="_blank" class="btn btn-sm btn-outline">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="<?= htmlspecialchars($doc['path']) ?>" download class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Document Statistics -->
        <div class="stats-section">
            <h3><i class="fas fa-chart-bar"></i> Document Statistics</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= count($documents) ?></h3>
                        <p>Total Documents</p>
                    </div>
                </div>
                
                <?php
                $docTypes = [];
                foreach ($documents as $doc) {
                    $docTypes[$doc['type']] = ($docTypes[$doc['type']] ?? 0) + 1;
                }
                ?>
                
                <?php foreach ($docTypes as $type => $count): ?>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <?php
                            $iconClass = 'fa-file';
                            switch ($type) {
                                case 'Birth Certificate':
                                    $iconClass = 'fa-file-pdf';
                                    break;
                                case 'Parent ID Document':
                                    $iconClass = 'fa-id-card';
                                    break;
                                case 'Clinical Report':
                                    $iconClass = 'fa-notes-medical';
                                    break;
                                case 'School Report':
                                    $iconClass = 'fa-graduation-cap';
                                    break;
                            }
                            ?>
                            <i class="fas <?= $iconClass ?>"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?= $count ?></h3>
                            <p><?= htmlspecialchars($type) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Parent Documents Styles */
.parent-info-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.parent-header {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

.parent-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.parent-avatar i {
    color: white;
    font-size: 2rem;
}

.parent-details {
    flex: 1;
}

.parent-details h2 {
    color: var(--secondary-color);
    margin-bottom: 10px;
}

.parent-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.parent-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.parent-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.applications-summary {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.applications-summary h3 {
    color: var(--secondary-color);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.app-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.app-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
}

.app-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.app-id {
    font-weight: 600;
    color: var(--primary-color);
}

.app-status {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.app-details h4 {
    color: var(--text-dark);
    margin-bottom: 5px;
}

.app-details p {
    color: var(--text-muted);
    font-size: 0.85rem;
    margin-bottom: 2px;
}

.documents-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.documents-section h3 {
    color: var(--secondary-color);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
}

.document-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    gap: 15px;
    align-items: flex-start;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.document-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.document-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.document-icon i {
    color: white;
    font-size: 1.2rem;
}

.document-info {
    flex: 1;
    min-width: 0;
}

.document-info h4 {
    color: var(--primary-color);
    margin-bottom: 8px;
    font-size: 1.1rem;
}

.document-filename {
    color: var(--text-muted);
    font-size: 0.85rem;
    margin-bottom: 5px;
    word-break: break-all;
}

.document-child,
.document-app,
.document-date {
    color: var(--text-dark);
    font-size: 0.85rem;
    margin-bottom: 2px;
}

.document-actions {
    display: flex;
    flex-direction: column;
    gap: 5px;
    align-items: stretch;
}

.no-documents {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-muted);
}

.no-documents i {
    font-size: 3rem;
    color: #ccc;
    margin-bottom: 15px;
}

.no-documents h4 {
    color: var(--text-muted);
    margin-bottom: 10px;
}

.stats-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.stats-section h3 {
    color: var(--secondary-color);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
}

.stat-icon {
    margin-bottom: 10px;
}

.stat-icon i {
    font-size: 2rem;
}

.stat-content h3 {
    font-size: 2rem;
    margin-bottom: 5px;
}

.stat-content p {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

@media (max-width: 768px) {
    .parent-header {
        flex-direction: column;
        text-align: center;
    }
    
    .parent-meta {
        justify-content: center;
    }
    
    .parent-actions {
        justify-content: center;
    }
    
    .app-cards {
        grid-template-columns: 1fr;
    }
    
    .documents-grid {
        grid-template-columns: 1fr;
    }
    
    .document-card {
        flex-direction: column;
        text-align: center;
    }
    
    .document-actions {
        flex-direction: row;
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
