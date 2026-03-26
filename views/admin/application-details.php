<div class="content-wrapper">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-content">
            <h1>Application Review</h1>
            <p>Review and take action on admission application</p>
        </div>
    </section>

    <div class="admin-container">
        <?php if ($admission): ?>
            <!-- Quick Action Section -->
            <div class="action-section">
                <div class="action-card">
                    <h3><i class="fas fa-exclamation-triangle"></i> Quick Action Required</h3>
                    <p>Application #<?= htmlspecialchars($admission['applicationID']) ?> for <?= htmlspecialchars($admission['child_name'] ?? $admission['childFirstName'] . ' ' . $admission['childSurname']) ?></p>
                    
                    <div class="action-buttons">
                        <button onclick="showAdmitForm()" class="btn btn-success btn-large">
                            <i class="fas fa-check-circle"></i> Admit Student
                        </button>
                        <button onclick="showRejectForm()" class="btn btn-danger btn-large">
                            <i class="fas fa-times-circle"></i> Reject Application
                        </button>
                    </div>
                </div>
            </div>

            <!-- Admit Form (Hidden by default) -->
            <div id="admitForm" class="action-form" style="display: none;">
                <div class="form-card">
                    <h3><i class="fas fa-check-circle"></i> Admit Student</h3>
                    <form id="admitFormElement">
                        <div class="form-group">
                            <label>Admission Reason (Optional)</label>
                            <textarea name="reason" placeholder="Enter reason for admission (e.g., 'Meets all requirements', 'Space available')"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Admission Date</label>
                            <input type="date" name="admission_date" required>
                        </div>
                        <div class="form-actions">
                            <button type="button" onclick="confirmAdmit()" class="btn btn-success">
                                <i class="fas fa-check"></i> Confirm Admission
                            </button>
                            <button type="button" onclick="hideAllForms()" class="btn btn-outline">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reject Form (Hidden by default) -->
            <div id="rejectForm" class="action-form" style="display: none;">
                <div class="form-card">
                    <h3><i class="fas fa-times-circle"></i> Reject Application</h3>
                    <form id="rejectFormElement">
                        <div class="form-group">
                            <label>Rejection Reason *</label>
                            <textarea name="reason" placeholder="Enter reason for rejection (e.g., 'Age not appropriate', 'No space available', 'Incomplete documentation')" required></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="button" onclick="confirmReject()" class="btn btn-danger">
                                <i class="fas fa-times"></i> Confirm Rejection
                            </button>
                            <button type="button" onclick="hideAllForms()" class="btn btn-outline">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Basic Application Info -->
            <div class="info-section">
                <h3><i class="fas fa-info-circle"></i> Application Summary</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <label>Child Name</label>
                        <span><?= htmlspecialchars($admission['child_name'] ?? $admission['childFirstName'] . ' ' . $admission['childSurname']) ?></span>
                    </div>
                    <div class="summary-item">
                        <label>Age</label>
                        <span><?= htmlspecialchars($admission['child_age'] ?? $admission['age']) ?> years</span>
                    </div>
                    <div class="summary-item">
                        <label>Grade</label>
                        <span><?= htmlspecialchars($admission['grade'] ?? $admission['gradeApplyingFor']) ?></span>
                    </div>
                    <div class="summary-item">
                        <label>Parent</label>
                        <span><?= htmlspecialchars($admission['parentFirstName'] . ' ' . $admission['parentSurname']) ?></span>
                    </div>
                    <div class="summary-item">
                        <label>Contact</label>
                        <span><?= htmlspecialchars($admission['contactNumber']) ?></span>
                    </div>
                    <div class="summary-item">
                        <label>Submitted</label>
                        <span><?= date('M j, Y', strtotime($admission['submitted_at'] ?? $admission['submittedAt'])) ?></span>
                    </div>
                    <div class="summary-item">
                        <label>Current Status</label>
                        <span class="status-badge status-<?= strtolower($admission['status']) ?>">
                            <?= htmlspecialchars($admission['status']) ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function showAdmitForm() {
    document.getElementById('admitForm').style.display = 'block';
    document.getElementById('rejectForm').style.display = 'none';
    document.getElementById('admitFormElement').querySelector('input[name="admission_date"]').valueAsDate = new Date();
}

function showRejectForm() {
    document.getElementById('rejectForm').style.display = 'block';
    document.getElementById('admitForm').style.display = 'none';
}

function hideAllForms() {
    document.getElementById('admitForm').style.display = 'none';
    document.getElementById('rejectForm').style.display = 'none';
}

function confirmAdmit() {
    const reason = document.querySelector('#admitForm textarea[name="reason"]').value;
    const admissionDate = document.querySelector('#admitForm input[name="admission_date"]').value;
    
    if (!admissionDate) {
        alert('Please select admission date');
        return;
    }
    
    if (confirm('Are you sure you want to admit this student?')) {
        updateStatus('<?= htmlspecialchars($admission['id']) ?>', 'approved', reason, admissionDate);
    }
}

function confirmReject() {
    const reason = document.querySelector('#rejectForm textarea[name="reason"]').value;
    
    if (!reason.trim()) {
        alert('Please provide a reason for rejection');
        return;
    }
    
    if (confirm('Are you sure you want to reject this application?')) {
        updateStatus('<?= htmlspecialchars($admission['id']) ?>', 'rejected', reason);
    }
}

function updateStatus(id, status, reason, admissionDate = null) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('status', status);
    formData.append('reason', reason || '');
    if (admissionDate) {
        formData.append('admission_date', admissionDate);
    }
    
    fetch('/admin/update-application-status', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Application status updated successfully!');
            window.location.reload();
        } else {
            alert('Error updating status: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}
</script>

<style>
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 3rem 2rem;
}

.action-section {
    margin-bottom: 2rem;
}

.action-card {
    background: white;
    border-radius: 15px;
    padding: 3rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    text-align: center;
    border: 2px solid #f0f0f0;
}

.action-card h3 {
    color: #e74c3c;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
}

.action-card p {
    color: #666;
    margin-bottom: 2.5rem;
    font-size: 1.2rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn-large {
    padding: 1rem 2rem;
    font-size: 1.1rem;
    min-width: 180px;
}

.action-form {
    margin-bottom: 2rem;
}

.form-card {
    background: white;
    border-radius: 15px;
    padding: 3rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border-left: 4px solid #28a745;
}

#rejectForm .form-card {
    border-left-color: #dc3545;
}

.form-card h3 {
    margin-bottom: 2rem;
    color: #333;
    font-size: 1.4rem;
}

.form-group {
    margin-bottom: 2rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.8rem;
    font-weight: 600;
    color: #333;
    font-size: 1.1rem;
}

.form-group textarea,
.form-group input {
    width: 100%;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1.1rem;
}

.form-group textarea {
    min-height: 120px;
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: 1.5rem;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.summary-item label {
    font-weight: 600;
    color: #666;
    font-size: 1rem;
}

.summary-item span {
    font-weight: 500;
    color: #333;
    font-size: 1.1rem;
}

.status-badge {
    padding: 0.3rem 0.8rem;
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

@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
}
</style>
