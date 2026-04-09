<div class="content-wrapper">
    <div class="view-container">
        <div class="view-header">
            <h1><i class="fas fa-child"></i> Child Application Details</h1>
            <div class="header-actions">
                <a href="/admin/admissions" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Applications
                </a>
                <button onclick="printApplication()" class="btn btn-outline">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
        
        <!-- Application Status Card -->
        <div class="status-card">
            <div class="status-header">
                <div class="child-info">
                    <h2><?= htmlspecialchars($admission['child_name'] ?? $admission['childFirstName'] . ' ' . $admission['childSurname']) ?></h2>
                    <p class="application-id">Application ID: <?= htmlspecialchars($admission['applicationID']) ?></p>
                </div>
                <div class="status-badge-large status-<?= strtolower($admission['status']) ?>">
                    <i class="fas fa-<?= $admission['status'] === 'approved' ? 'check-circle' : ($admission['status'] === 'rejected' ? 'times-circle' : 'clock') ?>"></i>
                    <span><?= htmlspecialchars(ucfirst($admission['status'])) ?></span>
                </div>
            </div>
            
            <div class="status-details">
                <div class="status-item">
                    <label>Submitted:</label>
                    <span><?= date('F j, Y, g:i a', strtotime($admission['submitted_at'])) ?></span>
                </div>
                <div class="status-item">
                    <label>Last Updated:</label>
                    <span><?= isset($admission['updated_at']) ? date('F j, Y, g:i a', strtotime($admission['updated_at'])) : 'Never' ?></span>
                </div>
                <div class="status-item">
                    <label>Age:</label>
                    <span><?= $admission['age'] ?? 'N/A' ?> years old</span>
                </div>
                <div class="status-item">
                    <label>Grade:</label>
                    <span><?= htmlspecialchars($admission['grade'] ?? $admission['gradeApplyingFor']) ?></span>
                </div>
            </div>
        </div>

        <!-- Child Details Section -->
        <div class="detail-sections-grid">
            <div class="detail-section">
                <h3><i class="fas fa-user-child"></i> Child Information</h3>
                <div class="detail-list">
                    <div class="detail-row">
                        <span class="label">Full Name:</span>
                        <span class="value"><?= htmlspecialchars($admission['child_name'] ?? $admission['childFirstName'] . ' ' . $admission['childSurname']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Date of Birth:</span>
                        <span class="value"><?= htmlspecialchars($admission['dateOfBirth']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Child ID Number:</span>
                        <span class="value"><?= htmlspecialchars($admission['childIdNumber'] ?? 'N/A') ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Gender:</span>
                        <span class="value"><?= htmlspecialchars(ucfirst($admission['childGender'])) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Address:</span>
                        <span class="value"><?= htmlspecialchars($admission['childAddress']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Parent/Guardian Section -->
            <div class="detail-section">
                <h3><i class="fas fa-user-shield"></i> Parent/Guardian Information</h3>
                <div class="detail-list">
                    <div class="detail-row">
                        <span class="label">Parent Name:</span>
                        <span class="value"><?= htmlspecialchars($admission['parentFirstName'] . ' ' . $admission['parentSurname']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Relationship:</span>
                        <span class="value"><?= htmlspecialchars($admission['relationshipToChild']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Contact Number:</span>
                        <span class="value"><?= htmlspecialchars($admission['contactNumber']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Email Address:</span>
                        <span class="value"><?= htmlspecialchars($admission['emailAddress']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">ID Number:</span>
                        <span class="value"><?= htmlspecialchars($admission['parentIdNumber']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Address:</span>
                        <span class="value"><?= htmlspecialchars($admission['residentialAddress']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact Section -->
            <div class="detail-section">
                <h3><i class="fas fa-phone-alt"></i> Emergency Contact</h3>
                <div class="detail-list">
                    <div class="detail-row">
                        <span class="label">Contact Name:</span>
                        <span class="value"><?= htmlspecialchars($admission['emergencyContactName'] ?? 'Not provided') ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Contact Phone:</span>
                        <span class="value"><?= htmlspecialchars($admission['emergencyContactPhone'] ?? 'Not provided') ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Contact Address:</span>
                        <span class="value"><?= htmlspecialchars($admission['emergencyContactAddress'] ?? 'Not provided') ?></span>
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="detail-section">
                <h3><i class="fas fa-info-circle"></i> Additional Information</h3>
                <div class="detail-list">
                    <div class="detail-row">
                        <span class="label">Transportation:</span>
                        <span class="value"><?= htmlspecialchars(ucfirst($admission['transportation'])) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Special Needs:</span>
                        <span class="value"><?= htmlspecialchars($admission['specialNeeds']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-section">
            <div class="action-buttons">
                <button onclick="updateStatus('<?= htmlspecialchars($admission['id']) ?>')" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Update Status
                </button>
                <button onclick="downloadDocuments('<?= htmlspecialchars($admission['id']) ?>')" class="btn btn-outline">
                    <i class="fas fa-download"></i> Download Documents
                </button>
                <button onclick="printApplication()" class="btn btn-outline">
                    <i class="fas fa-print"></i> Print Application
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.detail-sections-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.detail-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: 1px solid #e0e0e0;
}

.status-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin: 20px 0;
    border: 1px solid #e0e0e0;
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.child-info h2 {
    color: var(--primary-color);
    margin: 0 0 5px 0;
    font-size: 1.5rem;
}

.application-id {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin: 0;
}

.status-badge-large {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
}

.status-badge-large.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-badge-large.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-badge-large.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.status-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.status-item {
    display: flex;
    flex-direction: column;
}

.status-item label {
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 5px;
    font-size: 0.85rem;
}

.status-item span {
    font-size: 1rem;
    color: var(--text-dark);
}

.detail-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row .label {
    font-weight: 600;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.detail-row .value {
    color: var(--text-dark);
    font-weight: 500;
    text-align: right;
}

.action-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin: 20px 0;
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .detail-sections-grid {
        grid-template-columns: 1fr;
    }
    
    .status-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .status-details {
        grid-template-columns: 1fr;
    }
    
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .detail-row .value {
        text-align: left;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<script>
function updateStatus(applicationId) {
    // Implementation for status update
    console.log('Update status for:', applicationId);
}

function downloadDocuments(applicationId) {
    // Implementation for document download
    console.log('Download documents for:', applicationId);
}

function printApplication() {
    window.print();
}
</script>
