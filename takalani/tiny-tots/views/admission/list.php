<div class="content-wrapper">
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-graduation-cap"></i> Admission Applications</h1>
            <p>Manage and review all admission applications</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-info">
                    <h3><?= count($admissions) ?></h3>
                    <p>Total Applications</p>
                </div>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-icon">⏳</div>
                <div class="stat-info">
                    <h3><?= $stats['pending'] ?></h3>
                    <p>Pending Review</p>
                </div>
            </div>
            
            <div class="stat-card approved">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <h3><?= $stats['approved'] ?></h3>
                    <p>Approved</p>
                </div>
            </div>
            
            <div class="stat-card rejected">
                <div class="stat-icon">❌</div>
                <div class="stat-info">
                    <h3><?= $stats['rejected'] ?></h3>
                    <p>Rejected</p>
                </div>
            </div>
        </div>
        
        <div class="filter-section">
            <div class="filter-controls">
                <form method="GET" class="filter-form">
                    <select name="status" onchange="this.form.submit()">
                        <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>All Status</option>
                        <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Approved" <?= $status === 'Approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="Rejected" <?= $status === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </form>
                
                <div class="export-buttons">
                    <button onclick="exportData()" class="btn btn-secondary">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <button onclick="printReports()" class="btn btn-outline">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
        
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
            
            <?php if (empty($admissions)): ?>
                <div class="no-data">
                    <i class="fas fa-inbox"></i>
                    <h3>No applications found</h3>
                    <p>There are no applications with the selected status.</p>
                </div>
            <?php else: ?>
                <?php foreach ($admissions as $admission): ?>
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
                            <div class="action-buttons">
                                <button onclick="viewApplication('<?= htmlspecialchars($admission['id']) ?>')" 
                                        class="btn-small btn-view">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button onclick="updateStatus('<?= htmlspecialchars($admission['id']) ?>', 'Approved')" 
                                        class="btn-small btn-approve"
                                        <?= $admission['status'] === 'Approved' ? 'disabled' : '' ?>>
                                    <i class="fas fa-check"></i>
                                </button>
                                <button onclick="updateStatus('<?= htmlspecialchars($admission['id']) ?>', 'Rejected')" 
                                        class="btn-small btn-reject"
                                        <?= $admission['status'] === 'Rejected' ? 'disabled' : '' ?>>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
/* Admin Admissions Styles */
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

.filter-section {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px var(--shadow-light);
    margin-bottom: 2rem;
}

.filter-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.filter-form select {
    padding: 0.8rem 1rem;
    border: 2px solid var(--light-blue);
    border-radius: 10px;
    font-size: 1rem;
    background: var(--warm-white);
}

.export-buttons {
    display: flex;
    gap: 1rem;
}

.applications-table {
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px var(--shadow-light);
    overflow: hidden;
}

.table-header {
    display: grid;
    grid-template-columns: 1.2fr 1.8fr 1.5fr 1fr 1fr 1fr 1.5fr;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--light-blue);
    font-weight: 600;
    color: var(--text-dark);
}

.table-row {
    display: grid;
    grid-template-columns: 1.2fr 1.8fr 1.5fr 1fr 1fr 1fr 1.5fr;
    gap: 1rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--light-blue);
    align-items: center;
    transition: background-color 0.3s ease;
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

.action-buttons {
    display: flex;
    gap: 0.5rem;
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

.btn-approve {
    background: #51cf66;
    color: white;
}

.btn-reject {
    background: #ff6b6b;
    color: white;
}

.btn-small:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.btn-small:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.no-data {
    text-align: center;
    padding: 4rem 2rem;
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

@media (max-width: 1024px) {
    .table-header,
    .table-row {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        font-size: 0.8rem;
    }
    
    .table-row > div {
        padding: 0.5rem;
        border-bottom: 1px solid var(--light-blue);
    }
    
    .action-buttons {
        justify-content: center;
        margin-top: 1rem;
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
    
    .filter-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .export-buttons {
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
                <button onclick="updateStatus('${id}', 'Approved')" class="btn btn-success">Approve</button>
                <button onclick="updateStatus('${id}', 'Rejected')" class="btn btn-danger">Reject</button>
            </div>
        </div>
    `;
    
    document.getElementById('applicationModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('applicationModal').style.display = 'none';
}

function updateStatus(id, status) {
    if (confirm(`Are you sure you want to ${status.toLowerCase()} this application?`)) {
        // In a real application, this would make an AJAX call
        alert(`Application ${id} marked as ${status}`);
        closeModal();
        location.reload();
    }
}

function exportData() {
    alert('Export functionality would download all applications as CSV/Excel');
}

function printReports() {
    alert('Print functionality would generate printable reports');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('applicationModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>
