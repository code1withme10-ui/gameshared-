<div class="content-wrapper">
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
            <p>Welcome back, <?= htmlspecialchars($user['name']) ?>!</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-info">
                    <h3><?= $stats['total_admissions'] ?></h3>
                    <p>Total Applications</p>
                </div>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-icon">⏳</div>
                <div class="stat-info">
                    <h3><?= $stats['pending_admissions'] ?></h3>
                    <p>Pending Review</p>
                </div>
            </div>
            
            <div class="stat-card approved">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <h3><?= $stats['approved_admissions'] ?></h3>
                    <p>Approved</p>
                </div>
            </div>
            
            <div class="stat-card rejected">
                <div class="stat-icon">❌</div>
                <div class="stat-info">
                    <h3><?= $stats['rejected_admissions'] ?></h3>
                    <p>Rejected</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-info">
                    <h3><?= $stats['total_users'] ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👨‍👩‍👧‍👦</div>
                <div class="stat-info">
                    <h3><?= $stats['total_parents'] ?></h3>
                    <p>Parents</p>
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
                                    <span class="submit-date"><?= date('M j, Y', strtotime($admission['submittedAt'])) ?></span>
                                </div>
                                <div>
                                    <button onclick="viewApplication('<?= htmlspecialchars($admission['id']) ?>')" 
                                            class="btn-small btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </button>
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

.stat-card.pending {
    border-left: 5px solid #ffa500;
}

.stat-card.approved {
    border-left: 5px solid #51cf66;
}

.stat-card.rejected {
    border-left: 5px solid #ff6b6b;
}

.stat-icon {
    font-size: 3rem;
    width: 60px;
    text-align: center;
}

.stat-info h3 {
    color: var(--text-dark);
    font-size: 2rem;
    margin: 0 0 0.5rem 0;
    font-weight: 600;
}

.stat-info p {
    color: var(--text-light);
    margin: 0;
    font-size: 0.9rem;
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

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('applicationModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>
