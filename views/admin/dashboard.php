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
                        <div>Age</div>
                        <div>Grade</div>
                        <div>Address</div>
                        <div>Contact</div>
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
                                    <span class="age-badge"><?= htmlspecialchars($admission['age'] ?? 'N/A') ?> years</span>
                                </div>
                                <div>
                                    <span class="grade-badge"><?= htmlspecialchars($gradeCategories[$admission['gradeApplyingFor']] ?? 'N/A') ?></span>
                                </div>
                                <div>
                                    <div class="address-info">
                                        <span class="address-line"><?= htmlspecialchars($admission['residentialAddress'] ?? 'N/A') ?></span>
                                        <span class="city-line"><?= htmlspecialchars($admission['city'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="contact-info">
                                        <span class="phone"><?= htmlspecialchars($admission['contactNumber']) ?></span>
                                        <span class="email-small"><?= htmlspecialchars($admission['emailAddress']) ?></span>
                                    </div>
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
                                    <div class="action-buttons-inline">
                                        <button onclick="testFunction('<?= htmlspecialchars($admission['id']) ?>')" 
                                                class="btn-small btn-test">
                                            <i class="fas fa-bug"></i> Test
                                        </button>
                                        <button onclick="admitApplication('<?= htmlspecialchars($admission['id']) ?>')" 
                                                class="btn-small btn-admit">
                                            <i class="fas fa-check"></i> Admit
                                        </button>
                                        <button onclick="rejectApplication('<?= htmlspecialchars($admission['id']) ?>')" 
                                                class="btn-small btn-reject">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
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

<style>
/* Admin Dashboard Styles */
.admin-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 3rem 2rem;
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 2.5rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 3rem;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: var(--light-blue);
}

.stat-card.total .stat-icon {
    background: var(--primary-color);
    color: white;
}

.stat-card.pending .stat-icon {
    background: #ffc107;
    color: white;
}

.stat-card.approved .stat-icon {
    background: #28a745;
    color: white;
}

.stat-card.rejected .stat-icon {
    background: #dc3545;
    color: white;
}

.stat-content h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    color: var(--text-dark);
}

.stat-content p {
    margin: 0;
    color: var(--text-light);
    font-size: 1.1rem;
}

.dashboard-content {
    display: grid;
    grid-template-columns: 1fr;
    gap: 3rem;
}

.recent-applications {
    background: white;
    border-radius: 15px;
    padding: 2.5rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.recent-applications h2 {
    color: var(--primary-color);
    font-size: 1.8rem;
    margin: 0 0 2rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.applications-table {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.table-header {
    display: grid;
    grid-template-columns: 0.8fr 1.5fr 1.5fr 0.8fr 1fr 1.8fr 1.5fr 1fr 1.2fr 1.5fr;
    gap: 1rem;
    padding: 1rem;
    background: var(--warm-white);
    border-radius: 10px;
    font-weight: 600;
    color: var(--text-dark);
    border-bottom: 2px solid var(--primary-color);
}

.table-row {
    display: grid;
    grid-template-columns: 0.8fr 1.5fr 1.5fr 0.8fr 1fr 1.8fr 1.5fr 1fr 1.2fr 1.5fr;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: 10px;
    border: 1px solid var(--light-blue);
    align-items: center;
    transition: all 0.3s ease;
}

.table-row:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-color: var(--primary-color);
}

.parent-info {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.parent-name {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 1rem;
}

.parent-email {
    font-size: 0.9rem;
    color: var(--text-light);
}

.child-info {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.child-name {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 1rem;
}

.child-age {
    font-size: 0.9rem;
    color: var(--text-light);
}

.grade-badge {
    background: var(--primary-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    text-align: center;
}

.age-badge {
    background: var(--primary-color);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-align: center;
}

.address-info {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.address-line {
    font-size: 0.8rem;
    color: var(--text-dark);
    font-weight: 500;
}

.city-line {
    font-size: 0.75rem;
    color: var(--text-light);
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.phone {
    font-size: 0.8rem;
    color: var(--text-dark);
    font-weight: 500;
}

.email-small {
    font-size: 0.7rem;
    color: var(--text-light);
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    text-align: center;
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

.submit-date {
    font-size: 0.9rem;
    color: var(--text-light);
}

.btn-small {
    padding: 0.8rem 1.2rem;
    font-size: 0.9rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-view {
    background: var(--primary-color);
    color: white;
}

.btn-view:hover {
    background: var(--secondary-color);
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

.action-buttons-inline {
    display: flex;
    gap: 0.5rem;
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

.btn-test {
    background: #ffc107;
    color: #212529;
}

.btn-test:hover {
    background: #e0a800;
    transform: translateY(-1px);
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
}
</style>

<script>
function testFunction(id) {
    alert('Test function called! ID: ' + id);
    console.log('Test function - ID:', id);
}

function admitApplication(id) {
    console.log('Admit function called with ID:', id);
    
    if (confirm('Admit application ' + id + '?')) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('status', 'Approved');
        formData.append('notes', 'Approved via dashboard');
        
        fetch('/admin/admissions/updateStatus', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            if (data.success) {
                alert('Application admitted successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.error || data.message));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        });
    }
}

function rejectApplication(id) {
    console.log('Reject function called with ID:', id);
    
    const reason = prompt('Enter rejection reason:');
    if (!reason) {
        alert('Please provide a reason');
        return;
    }
    
    if (confirm('Reject application ' + id + '?')) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('status', 'Rejected');
        formData.append('notes', reason);
        
        fetch('/admin/admissions/updateStatus', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            if (data.success) {
                alert('Application rejected successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.error || data.message));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: ' + error.message);
        });
    }
}

function exportData() {
    alert('Export functionality would download all applications as CSV/Excel');
}
</script>
