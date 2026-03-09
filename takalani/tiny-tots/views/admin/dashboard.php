<div class="content-wrapper">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-content">
            <h1>Headmaster Dashboard</h1>
            <p>Manage admission applications and monitor enrollment statistics</p>
        </div>
    </section>

    <div class="admin-container">
        
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['total'] ?></h3>
                    <p>Total Applications</p>
                </div>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['pending'] ?></h3>
                    <p>Pending Review</p>
                </div>
            </div>
            
            <div class="stat-card approved">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['approved'] ?></h3>
                    <p>Accepted Applications</p>
                </div>
            </div>
            
            <div class="stat-card rejected">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['rejected'] ?></h3>
                    <p>Declined Applications</p>
                </div>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="recent-applications">
                <h2><i class="fas fa-clock"></i> Recent Applications</h2>
                <div class="applications-table">
                    <div class="table-header">
                        <div>Application ID</div>
                        <div>Parent Name</div>
                        <div>Child Name</div>
                        <div>Grade</div>
                        <div>Status</div>
                        <div>Submitted</div>
                        <div>Actions</div>
                    </div>
                    
                    <?php if (empty($recentAdmissions)): ?>
                        <div class="no-data">
                            <i class="fas fa-inbox"></i>
                            <h3>No applications yet</h3>
                            <p>When applications are submitted, they will appear here.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recentAdmissions as $admission): ?>
                            <div class="table-row">
                                <div>
                                    <span class="app-id"><?= htmlspecialchars($admission['applicationID']) ?></span>
                                </div>
                                <div>
                                    <div class="parent-info">
                                        <span class="parent-name"><?= htmlspecialchars($admission['parentFirstName'] . ' ' . $admission['parentSurname']) ?></span>
                                        <span class="parent-email"><?= htmlspecialchars($admission['emailAddress']) ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="child-info">
                                        <span class="child-name"><?= htmlspecialchars($admission['childFirstName'] . ' ' . $admission['childSurname']) ?></span>
                                        <span class="child-age">Age: <?= $admission['age'] ?? 'N/A' ?></span>
                                    </div>
                                </div>
                                <div>
                                    <span class="grade-badge"><?= htmlspecialchars($gradeCategories[$admission['gradeApplyingFor']] ?? 'N/A') ?></span>
                                </div>
                                <div>
                                    <span class="status-badge status-<?= strtolower($admission['status']) ?>">
                                        <?= htmlspecialchars($admission['status']) ?>
                                    </span>
                                </div>
                                <div>
                                    <span class="submit-date"><?= date('M j, Y', strtotime($admission['submittedAt'] ?? $admission['submitted_at'] ?? '1970-01-01')) ?></span>
                                </div>
                                <div>
                                    <div class="action-buttons">
                                        <button onclick="viewApplication('<?= htmlspecialchars($admission['id']) ?>')" 
                                                class="btn-small btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        
                                        <?php if ($admission['status'] !== 'Approved' && $admission['status'] !== 'Rejected'): ?>
                                            <button onclick="showAdmitModal('<?= htmlspecialchars($admission['id']) ?>', '<?= htmlspecialchars($admission['childFirstName'] . ' ' . $admission['childSurname']) ?>')" 
                                                    class="btn-small btn-admit">
                                                <i class="fas fa-check"></i> Admit
                                            </button>
                                            <button onclick="showRejectModal('<?= htmlspecialchars($admission['id']) ?>', '<?= htmlspecialchars($admission['childFirstName'] . ' ' . $admission['childSurname']) ?>')" 
                                                    class="btn-small btn-reject">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        <?php elseif ($admission['status'] === 'Approved'): ?>
                                            <button class="btn-small btn-disabled" disabled title="Already Admitted">
                                                <i class="fas fa-check"></i> Admitted
                                            </button>
                                        <?php elseif ($admission['status'] === 'Rejected'): ?>
                                            <button class="btn-small btn-disabled" disabled title="Already Rejected">
                                                <i class="fas fa-times"></i> Rejected
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="section-footer">
                    <a href="/admin/admissions" class="btn btn-primary">
                        <i class="fas fa-list"></i> View All Applications
                    </a>
                </div>
            </div>
            
            <div class="quick-actions">
                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                <div class="actions-grid">
                    <a href="/admin/admissions" class="action-card">
                        <div class="action-icon">📋</div>
                        <h3>Manage Applications</h3>
                        <p>Review and manage admission applications</p>
                    </a>
                    
                    <a href="/admin/users" class="action-card">
                        <div class="action-icon">👥</div>
                        <h3>Manage Users</h3>
                        <p>Create and manage user accounts</p>
                    </a>
                    
                    <a href="/admin/create-user" class="action-card">
                        <div class="action-icon">➕</div>
                        <h3>Create User</h3>
                        <p>Add new parent or headmaster accounts</p>
                    </a>
                    
                    <a href="/admin/settings" class="action-card">
                        <div class="action-icon">⚙️</div>
                        <h3>Settings</h3>
                        <p>Configure system settings</p>
                    </a>
                    
                    <a href="/gallery" class="action-card">
                        <div class="action-icon">🖼️</div>
                        <h3>Manage Gallery</h3>
                        <p>Update photos and media</p>
                    </a>
                    
                    <a href="#" onclick="exportData()" class="action-card">
                        <div class="action-icon">📊</div>
                        <h3>Export Data</h3>
                        <p>Download reports and data</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Application Details Modal -->
<div id="applicationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Application Details</h2>
            <span class="modal-close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
</div>

<style>
/* Admin Dashboard Styles */
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.admin-header {
    text-align: center;
    margin-bottom: 3rem;
}

.admin-header h1 {
    color: var(--secondary-color);
    font-size: 2.5rem;
    margin: 0 0 0.5rem 0;
    font-weight: 600;
}

.admin-header p {
    color: var(--text-light);
    font-size: 1.1rem;
    margin: 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px var(--shadow-light);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card.total {
    border-left: 5px solid var(--primary-color);
}

.stat-card.pending {
    border-left: 5px solid #f39c12;
}

.stat-card.approved {
    border-left: 5px solid #27ae60;
}

.stat-card.rejected {
    border-left: 5px solid #e74c3c;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-card.total .stat-icon {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

.stat-card.pending .stat-icon {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.stat-card.approved .stat-icon {
    background: linear-gradient(135deg, #27ae60, #229954);
}

.stat-card.rejected .stat-icon {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.stat-content h3 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 0.5rem 0;
}

.stat-content p {
    color: var(--text-light);
    font-size: 1rem;
    margin: 0;
    font-weight: 500;
}

.dashboard-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.recent-applications {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
    padding: 2rem;
}

.recent-applications h2 {
    color: var(--primary-color);
    margin: 0 0 2rem 0;
    font-size: 1.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.recent-applications h2 i {
    margin-right: 0.8rem;
}

.applications-table {
    overflow-x: auto;
}

.table-header {
    display: grid;
    grid-template-columns: 1.2fr 1.8fr 1.5fr 1fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--light-blue);
    border-radius: 10px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.table-row {
    display: grid;
    grid-template-columns: 1.2fr 1.8fr 1.5fr 1fr 1fr 1fr 1fr;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid var(--light-blue);
    align-items: center;
}

.table-row:hover {
    background: var(--warm-white);
}

.app-id {
    font-family: monospace;
    font-weight: 600;
    color: var(--primary-color);
}

.parent-info,
.child-info {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.parent-name,
.child-name {
    font-weight: 600;
    color: var(--text-dark);
}

.parent-email,
.child-age {
    font-size: 0.85rem;
    color: var(--text-light);
}

.grade-badge {
    background: var(--primary-color);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
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

.submit-date {
    font-size: 0.9rem;
    color: var(--text-light);
}

.btn-small {
    padding: 0.4rem 0.8rem;
    border: none;
    border-radius: 8px;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.btn-view {
    background: var(--primary-color);
    color: white;
}

.btn-small:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.no-data {
    text-align: center;
    padding: 3rem;
    color: var(--text-light);
}

.no-data i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: var(--light-blue);
}

.no-data h3 {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.section-footer {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--light-blue);
    text-align: center;
}

.quick-actions h2 {
    color: var(--secondary-color);
    margin: 0 0 2rem 0;
    font-size: 1.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.quick-actions h2 i {
    margin-right: 0.8rem;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.action-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px var(--shadow-light);
    text-decoration: none;
    color: var(--text-dark);
    transition: all 0.3s ease;
    text-align: center;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px var(--shadow-medium);
}

.action-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.action-card h3 {
    color: var(--primary-color);
    margin: 0 0 0.5rem 0;
    font-size: 1.2rem;
    font-weight: 600;
}

.action-card p {
    color: var(--text-light);
    margin: 0;
    font-size: 0.9rem;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.modal-content {
    position: relative;
    background: white;
    margin: 5% auto;
    padding: 0;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.modal-close {
    font-size: 2rem;
    cursor: pointer;
    transition: color 0.3s ease;
}

.modal-close:hover {
    color: var(--accent-color);
}

.modal-body {
    padding: 2rem;
    max-height: calc(90vh - 100px);
    overflow-y: auto;
}

@media (max-width: 1024px) {
    .dashboard-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .table-header,
    .table-row {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        font-size: 0.8rem;
    }
    
    .table-row > div {
        padding: 0.5rem;
    }
}

@media (max-width: 768px) {
    .admin-container {
        padding: 1rem 0.5rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        width: 95%;
        margin: 2% auto;
    }
}

/* Action Buttons and Modal Styles */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-small {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.btn-view {
    background: #17a2b8;
    color: white;
}

.btn-view:hover {
    background: #138496;
    transform: translateY(-1px);
}

.btn-admit {
    background: #28a745;
    color: white;
}

.btn-admit:hover {
    background: #218838;
    transform: translateY(-1px);
}

.btn-reject {
    background: #dc3545;
    color: white;
}

.btn-reject:hover {
    background: #c82333;
    transform: translateY(-1px);
}

.btn-disabled {
    background: #6c757d;
    color: white;
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-disabled:hover {
    background: #6c757d;
    transform: none;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 1.5rem;
    border-radius: 12px 12px 0 0;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    color: #495057;
    font-size: 1.5rem;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close:hover {
    color: #000;
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0 0 12px 12px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

/* Form Styles in Modal */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #495057;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
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
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838, #1ea085);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c82333, #d62c1a);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

/* Responsive Design for Modals */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-small {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function viewApplication(id) {
    // This would typically fetch data from server
    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `
        <div class="application-details">
            <h3>Application ID: ${id}</h3>
            <p><strong>Status:</strong> Under Review</p>
            <p><em>Detailed application information would be loaded here.</em></p>
            <div style="margin-top: 2rem; text-align: center;">
                <button onclick="window.location.href='/admin/admissions/view?id=${id}'" class="btn btn-primary">
                    <i class="fas fa-eye"></i> View Full Details
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('applicationModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('applicationModal').style.display = 'none';
}

function exportData() {
    alert('Export functionality would download all applications as CSV/Excel');
}

function showAdmitModal(applicationId, childName) {
    const modal = document.getElementById('admitModal');
    document.getElementById('admitApplicationId').value = applicationId;
    document.getElementById('admitChildName').textContent = childName;
    modal.style.display = 'block';
}

function showRejectModal(applicationId, childName) {
    const modal = document.getElementById('rejectModal');
    document.getElementById('rejectApplicationId').value = applicationId;
    document.getElementById('rejectChildName').textContent = childName;
    modal.style.display = 'block';
}

function closeAdmitModal() {
    document.getElementById('admitModal').style.display = 'none';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

function confirmAdmission() {
    const applicationId = document.getElementById('admitApplicationId').value;
    const enrollmentDate = document.getElementById('enrollmentDate').value;
    const classroom = document.getElementById('classroom').value;
    const teacher = document.getElementById('teacher').value;
    const notes = document.getElementById('admissionNotes').value;
    
    // Submit admission decision
    fetch('/admin/admit-application', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': window.csrfToken
        },
        body: JSON.stringify({
            applicationId: applicationId,
            enrollmentDate: enrollmentDate,
            classroom: classroom,
            teacher: teacher,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Application admitted successfully!');
            closeAdmitModal();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the admission.');
    });
}

function confirmRejection() {
    const applicationId = document.getElementById('rejectApplicationId').value;
    const reason = document.getElementById('rejectionReason').value;
    const requirements = document.getElementById('requirements').value;
    const contactOption = document.getElementById('contactOption').value;
    
    // Submit rejection decision
    fetch('/admin/reject-application', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': window.csrfToken
        },
        body: JSON.stringify({
            applicationId: applicationId,
            reason: reason,
            requirements: requirements,
            contactOption: contactOption
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Application rejected successfully!');
            closeRejectModal();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the rejection.');
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('applicationModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>

<!-- Admission Modal -->
<div id="admitModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-check-circle" style="color: #28a745;"></i> Admit Student</h2>
            <span class="close" onclick="closeAdmitModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p><strong>Student:</strong> <span id="admitChildName"></span></p>
            
            <div class="form-group">
                <label for="enrollmentDate">Enrollment Date *</label>
                <input type="date" id="enrollmentDate" name="enrollmentDate" required 
                       value="<?= date('Y-m-d') ?>">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="classroom">Classroom *</label>
                    <select id="classroom" name="classroom" required>
                        <option value="">Select Classroom</option>
                        <option value="Toddler Room A">Toddler Room A</option>
                        <option value="Toddler Room B">Toddler Room B</option>
                        <option value="Playgroup Room A">Playgroup Room A</option>
                        <option value="Playgroup Room B">Playgroup Room B</option>
                        <option value="Preschool Room A">Preschool Room A</option>
                        <option value="Preschool Room B">Preschool Room B</option>
                        <option value="Grade R Room A">Grade R Room A</option>
                        <option value="Grade R Room B">Grade R Room B</option>
                        <option value="Grade 1 Room A">Grade 1 Room A</option>
                        <option value="Grade 1 Room B">Grade 1 Room B</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="teacher">Assigned Teacher *</label>
                    <select id="teacher" name="teacher" required>
                        <option value="">Select Teacher</option>
                        <option value="Mrs. Johnson">Mrs. Johnson</option>
                        <option value="Mrs. Smith">Mrs. Smith</option>
                        <option value="Mrs. Williams">Mrs. Williams</option>
                        <option value="Mrs. Brown">Mrs. Brown</option>
                        <option value="Mrs. Davis">Mrs. Davis</option>
                        <option value="Mrs. Miller">Mrs. Miller</option>
                        <option value="Mrs. Wilson">Mrs. Wilson</option>
                        <option value="Mrs. Moore">Mrs. Moore</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="admissionNotes">Admission Notes</label>
                <textarea id="admissionNotes" name="admissionNotes" rows="3" 
                          placeholder="Any special notes about the admission..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeAdmitModal()">Cancel</button>
            <button type="button" class="btn btn-success" onclick="confirmAdmission()">
                <i class="fas fa-check"></i> Confirm Admission
            </button>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-times-circle" style="color: #dc3545;"></i> Reject Application</h2>
            <span class="close" onclick="closeRejectModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p><strong>Student:</strong> <span id="rejectChildName"></span></p>
            
            <div class="form-group">
                <label for="rejectionReason">Rejection Reason *</label>
                <select id="rejectionReason" name="rejectionReason" required>
                    <option value="">Select Reason</option>
                    <option value="Age not appropriate">Age not appropriate for selected grade</option>
                    <option value="Class full">Class is at full capacity</option>
                    <option value="Documentation incomplete">Required documentation missing</option>
                    <option value="Medical requirements">Medical requirements not met</option>
                    <option value="Payment issues">Payment or financial issues</option>
                    <option value="Other">Other reasons</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="requirements">Requirements for Future Application</label>
                <textarea id="requirements" name="requirements" rows="3" 
                          placeholder="Specify what needs to be addressed for future consideration..."></textarea>
            </div>
            
            <div class="form-group">
                <label for="contactOption">Contact Parent</label>
                <select id="contactOption" name="contactOption">
                    <option value="email">Send email notification</option>
                    <option value="phone">Phone call</option>
                    <option value="both">Email and phone call</option>
                    <option value="none">No contact needed</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Cancel</button>
            <button type="button" class="btn btn-danger" onclick="confirmRejection()">
                <i class="fas fa-times"></i> Confirm Rejection
            </button>
        </div>
    </div>
</div>

<input type="hidden" id="admitApplicationId" value="">
<input type="hidden" id="rejectApplicationId" value="">

<!-- CSRF Token for AJAX requests -->
<script>
window.csrfToken = '<?= htmlspecialchars($csrfToken) ?>';
</script>
