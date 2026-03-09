<div class="content-wrapper">
    <div class="view-container">
        <div class="view-header">
            <h1><i class="fas fa-file-alt"></i> Application Details</h1>
            <div class="header-actions">
                <a href="/admin/admissions" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <button onclick="printApplication()" class="btn btn-outline">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
        
        <div class="application-details">
            <div class="detail-section">
                <h2><i class="fas fa-info-circle"></i> Application Information</h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Application ID:</label>
                        <span><?= htmlspecialchars($admission['applicationID']) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Status:</label>
                        <span class="status-badge status-<?= strtolower($admission['status']) ?>">
                            <?= htmlspecialchars($admission['status']) ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <label>Submitted:</label>
                        <span><?= date('F j, Y, g:i a', strtotime($admission['submittedAt'])) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Last Updated:</label>
                        <span><?= isset($admission['updatedAt']) ? date('F j, Y, g:i a', strtotime($admission['updatedAt'])) : 'Never' ?></span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h2><i class="fas fa-user"></i> Parent/Guardian Information</h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Full Name:</label>
                        <span><?= htmlspecialchars($admission['parentFirstName'] . ' ' . $admission['parentSurname']) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Contact Number:</label>
                        <span><?= htmlspecialchars($admission['contactNumber']) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Email Address:</label>
                        <span><?= htmlspecialchars($admission['emailAddress']) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>ID Number:</label>
                        <span><?= htmlspecialchars($admission['parentIdNumber']) ?></span>
                    </div>
                    <div class="detail-item full-width">
                        <label>Residential Address:</label>
                        <span><?= htmlspecialchars($admission['residentialAddress']) ?></span>
                    </div>
                </div>
                
                <?php if (!empty($admission['motherFirstName'])): ?>
                <h3><i class="fas fa-female"></i> Mother Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Name:</label>
                        <span><?= htmlspecialchars($admission['motherFirstName'] . ' ' . ($admission['motherSurname'] ?? '')) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>ID Number:</label>
                        <span><?= htmlspecialchars($admission['motherIdNumber'] ?? 'N/A') ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Occupation:</label>
                        <span><?= htmlspecialchars($admission['motherOccupation'] ?? 'N/A') ?></span>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($admission['fatherFirstName'])): ?>
                <h3><i class="fas fa-male"></i> Father Information</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Name:</label>
                        <span><?= htmlspecialchars($admission['fatherFirstName'] . ' ' . ($admission['fatherSurname'] ?? '')) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>ID Number:</label>
                        <span><?= htmlspecialchars($admission['fatherIdNumber'] ?? 'N/A') ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Occupation:</label>
                        <span><?= htmlspecialchars($admission['fatherOccupation'] ?? 'N/A') ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="detail-section">
                <h2><i class="fas fa-child"></i> Child Information</h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Full Name:</label>
                        <span><?= htmlspecialchars($admission['childFirstName'] . ' ' . $admission['childSurname']) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Date of Birth:</label>
                        <span><?= htmlspecialchars($admission['dateOfBirth']) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Age:</label>
                        <span><?= $admission['age'] ?? 'N/A' ?> years</span>
                    </div>
                    <div class="detail-item">
                        <label>Gender:</label>
                        <span><?= htmlspecialchars(ucfirst($admission['childGender'])) ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Grade Applying For:</label>
                        <span><?= htmlspecialchars($gradeCategories[$admission['gradeApplyingFor']] ?? 'N/A') ?></span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h2><i class="fas fa-phone-alt"></i> Emergency Contact</h2>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Contact Name:</label>
                        <span><?= htmlspecialchars($admission['emergencyContactName'] ?? 'Not provided') ?></span>
                    </div>
                    <div class="detail-item">
                        <label>Contact Phone:</label>
                        <span><?= htmlspecialchars($admission['emergencyContactPhone'] ?? 'Not provided') ?></span>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h2><i class="fas fa-bus"></i> Transportation</h2>
                <div class="detail-item">
                    <label>Transportation Required:</label>
                    <span><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $admission['transportation'] ?? 'none'))) ?></span>
                </div>
            </div>
            
            <?php if (!empty($admission['specialNeeds'])): ?>
            <div class="detail-section">
                <h2><i class="fas fa-heart"></i> Special Needs</h2>
                <div class="detail-item full-width">
                    <label>Special Needs/Medical Conditions:</label>
                    <span><?= htmlspecialchars($admission['specialNeeds']) ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="action-section">
            <h2><i class="fas fa-cog"></i> Actions</h2>
            <div class="action-buttons">
                <?php if ($admission['status'] === 'Pending'): ?>
                    <button onclick="updateStatus('<?= htmlspecialchars($admission['id']) ?>', 'Approved')" class="btn btn-success">
                        <i class="fas fa-check"></i> Approve Application
                    </button>
                    <button onclick="updateStatus('<?= htmlspecialchars($admission['id']) ?>', 'Rejected')" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject Application
                    </button>
                <?php elseif ($admission['status'] === 'Approved'): ?>
                    <button onclick="updateStatus('<?= htmlspecialchars($admission['id']) ?>', 'Rejected')" class="btn btn-warning">
                        <i class="fas fa-exchange-alt"></i> Change to Rejected
                    </button>
                <?php elseif ($admission['status'] === 'Rejected'): ?>
                    <button onclick="updateStatus('<?= htmlspecialchars($admission['id']) ?>', 'Approved')" class="btn btn-success">
                        <i class="fas fa-exchange-alt"></i> Change to Approved
                    </button>
                <?php endif; ?>
                
                <button onclick="deleteApplication('<?= htmlspecialchars($admission['id']) ?>')" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Application
                </button>
                
                <a href="mailto:<?= htmlspecialchars($admission['emailAddress']) ?>" class="btn btn-secondary">
                    <i class="fas fa-envelope"></i> Contact Parent
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Application View Styles */
.view-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.view-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.view-header h1 {
    color: var(--secondary-color);
    font-size: 2rem;
    margin: 0;
    font-weight: 600;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.application-details {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
    overflow: hidden;
}

.detail-section {
    padding: 2rem;
    border-bottom: 1px solid var(--light-blue);
}

.detail-section:last-child {
    border-bottom: none;
}

.detail-section h2 {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.detail-section h2 i {
    margin-right: 0.8rem;
}

.detail-section h3 {
    color: var(--secondary-color);
    margin: 2rem 0 1rem 0;
    font-size: 1.2rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.detail-section h3 i {
    margin-right: 0.8rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-item label {
    color: var(--text-light);
    font-weight: 500;
    font-size: 0.9rem;
}

.detail-item span {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 1rem;
}

.status-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #ffa500;
    color: white;
}

.status-approved {
    background: #51cf66;
    color: white;
}

.status-rejected {
    background: #ff6b6b;
    color: white;
}

.action-section {
    padding: 2rem;
    background: var(--warm-white);
}

.action-section h2 {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.action-section h2 i {
    margin-right: 0.8rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn {
    padding: 0.8rem 1.5rem;
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

.btn-success {
    background: #51cf66;
    color: white;
}

.btn-danger {
    background: #ff6b6b;
    color: white;
}

.btn-warning {
    background: #ffa500;
    color: white;
}

.btn-secondary {
    background: var(--primary-color);
    color: var(--text-dark);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px var(--shadow-medium);
}

@media (max-width: 768px) {
    .view-container {
        padding: 1rem 0.5rem;
    }
    
    .view-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .header-actions {
        justify-content: center;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .action-buttons .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function updateStatus(id, status) {
    if (confirm(`Are you sure you want to ${status.toLowerCase()} this application?`)) {
        // In a real application, this would make an AJAX call
        fetch('/admission/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.csrfToken
            },
            body: JSON.stringify({
                id: id,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Application status updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status.');
        });
    }
}

function deleteApplication(id) {
    if (confirm('Are you sure you want to delete this application? This action cannot be undone.')) {
        // In a real application, this would make an AJAX call
        fetch('/admission/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': window.csrfToken
            },
            body: JSON.stringify({
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Application deleted successfully!');
                window.location.href = '/admin/admissions';
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the application.');
        });
    }
}

function printApplication() {
    window.print();
}
</script>
