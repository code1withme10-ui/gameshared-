<div class="content-wrapper">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-content">
            <h1>Admission Applications</h1>
            <p>Review and manage learner admission applications</p>
        </div>
    </section>

    <div class="admin-container">
        <!-- Filters and Search -->
        <div class="filters-section">
            <div class="filters-header">
                <h2><i class="fas fa-filter"></i> Filter & Search</h2>
            </div>
            
            <form method="GET" class="filters-form">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" onchange="this.form.submit()">
                            <option value="all" <?= ($status ?? 'all') === 'all' ? 'selected' : '' ?>>All Applications</option>
                            <option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Pending Review</option>
                            <option value="approved" <?= ($status ?? '') === 'approved' ? 'selected' : '' ?>>Accepted</option>
                            <option value="rejected" <?= ($status ?? '') === 'rejected' ? 'selected' : '' ?>>Declined</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="search">Search</label>
                        <div class="search-input-group">
                            <input type="text" id="search" name="search" 
                                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                                   placeholder="Search by child name, parent name, or application ID...">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label for="sortBy">Sort By</label>
                        <select id="sortBy" name="sortBy" onchange="this.form.submit()">
                            <option value="date" <?= ($_GET['sortBy'] ?? 'date') === 'date' ? 'selected' : '' ?>>Submission Date</option>
                            <option value="name" <?= ($_GET['sortBy'] ?? '') === 'name' ? 'selected' : '' ?>>Child Name</option>
                            <option value="status" <?= ($_GET['sortBy'] ?? '') === 'status' ? 'selected' : '' ?>>Status</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="export">Export</label>
                        <button type="button" onclick="exportApplications()" class="btn btn-outline">
                            <i class="fas fa-download"></i> Export Data
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Applications Table -->
        <div class="applications-section">
            <div class="section-header">
                <h2><i class="fas fa-list"></i> Applications List</h2>
                <div class="results-count">
                    Showing <strong><?= count($admissions) ?></strong> applications
                    <?php if ($status !== 'all'): ?>
                        of status <strong><?= ucfirst($status) ?></strong>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (empty($admissions)): ?>
                <div class="no-results">
                    <i class="fas fa-inbox"></i>
                    <h3>No applications found</h3>
                    <p>Try adjusting your filters or search criteria.</p>
                </div>
            <?php else: ?>
                <div class="applications-table">
                    <div class="table-header">
                        <div class="sortable" onclick="sortApplications('applicationID')">
                            Application ID
                            <i class="fas fa-sort"></i>
                        </div>
                        <div class="sortable" onclick="sortApplications('childName')">
                            Child Name
                            <i class="fas fa-sort"></i>
                        </div>
                        <div class="sortable" onclick="sortApplications('parentName')">
                            Parent/Guardian
                            <i class="fas fa-sort"></i>
                        </div>
                        <div>Grade</div>
                        <div class="sortable" onclick="sortApplications('submittedAt')">
                            Submitted Date
                            <i class="fas fa-sort"></i>
                        </div>
                        <div>Status</div>
                        <div>Actions</div>
                    </div>
                    
                    <?php foreach ($admissions as $application): ?>
                        <div class="table-row" data-status="<?= $application['status'] ?>">
                            <div>
                                <span class="app-id"><?= htmlspecialchars($application['applicationID']) ?></span>
                            </div>
                            <div>
                                <div class="child-info">
                                    <span class="child-name"><?= htmlspecialchars($application['childFirstName'] . ' ' . $application['childSurname']) ?></span>
                                    <small class="child-age">Age: <?= $application['age'] ?? 'N/A' ?></small>
                                </div>
                            </div>
                            <div>
                                <div class="parent-info">
                                    <span class="parent-name"><?= htmlspecialchars($application['parentFirstName'] . ' ' . $application['parentSurname']) ?></span>
                                    <small class="parent-email"><?= htmlspecialchars($application['emailAddress']) ?></small>
                                </div>
                            </div>
                            <div>
                                <span class="grade-badge"><?= htmlspecialchars($gradeCategories[$application['gradeApplyingFor']] ?? 'N/A') ?></span>
                            </div>
                            <div>
                                <span class="submit-date"><?= date('M j, Y', strtotime($application['submittedAt'])) ?></span>
                            </div>
                            <div>
                                <span class="status-badge status-<?= strtolower($application['status']) ?>">
                                    <?= htmlspecialchars($application['status']) ?>
                                </span>
                            </div>
                            <div>
                                <div class="action-buttons">
                                    <button onclick="viewApplication('<?= htmlspecialchars($application['id']) ?>')" 
                                            class="btn btn-sm btn-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <?php if ($application['status'] === 'Pending'): ?>
                                        <button onclick="updateStatus('<?= htmlspecialchars($application['id']) ?>', 'approved')" 
                                                class="btn btn-sm btn-success" title="Accept Application">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="updateStatus('<?= htmlspecialchars($application['id']) ?>', 'rejected')" 
                                                class="btn btn-sm btn-danger" title="Decline Application">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if (count($admissions) > 20): ?>
                    <div class="pagination">
                        <button class="btn btn-outline" onclick="previousPage()">
                            <i class="fas fa-chevron-left"></i> Previous
                        </button>
                        <span class="page-info">Page 1 of 1</span>
                        <button class="btn btn-outline" onclick="nextPage()">
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                <?php endif; ?>
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

<!-- Status Update Modal -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Update Application Status</h2>
            <span class="modal-close" onclick="closeStatusModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="statusForm">
                <input type="hidden" id="statusApplicationId" name="applicationId">
                
                <div class="form-group">
                    <label for="newStatus">New Status</label>
                    <select id="newStatus" name="status" required>
                        <option value="">Select Status</option>
                        <option value="approved">Accept Application</option>
                        <option value="rejected">Decline Application</option>
                        <option value="pending">Keep Pending</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="adminNotes">Notes/Reason</label>
                    <textarea id="adminNotes" name="notes" rows="4" 
                              placeholder="Enter reason for decision or additional notes..."></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" onclick="closeStatusModal()" class="btn btn-outline">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Admin Applications Styles */
.admin-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Filters Section */
.filters-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 30px var(--shadow-light);
}

.filters-header h2 {
    color: var(--text-dark);
    margin: 0 0 2rem 0;
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filters-form {
    margin: 0;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.filter-group select,
.filter-group input {
    padding: 0.8rem;
    border: 2px solid var(--light-blue);
    border-radius: 8px;
    font-size: 1rem;
    background: white;
}

.search-input-group {
    display: flex;
    gap: 0.5rem;
}

.search-input-group input {
    flex: 1;
}

/* Applications Section */
.applications-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 30px var(--shadow-light);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--light-blue);
}

.section-header h2 {
    color: var(--text-dark);
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.results-count {
    color: var(--text-light);
    font-size: 1rem;
}

.results-count strong {
    color: var(--primary-color);
}

/* Table Styles */
.applications-table {
    overflow-x: auto;
}

.table-header {
    display: grid;
    grid-template-columns: 1.2fr 1.5fr 1.8fr 1.2fr 1.2fr 1fr 1.5fr;
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
    grid-template-columns: 1.2fr 1.5fr 1.8fr 1.2fr 1.2fr 1fr 1.5fr;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid var(--light-blue);
    align-items: center;
    transition: background-color 0.3s ease;
}

.table-row:hover {
    background: var(--warm-white);
}

.sortable {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sortable:hover {
    color: var(--primary-color);
}

.app-id {
    font-family: monospace;
    font-weight: 600;
    color: var(--primary-color);
}

.child-info,
.parent-info {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.child-name,
.parent-name {
    font-weight: 600;
    color: var(--text-dark);
}

.child-age,
.parent-email {
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

.submit-date {
    font-size: 0.9rem;
    color: var(--text-light);
}

.status-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #f39c12;
    color: white;
}

.status-approved {
    background: #27ae60;
    color: white;
}

.status-rejected {
    background: #e74c3c;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
}

.btn-success {
    background: #27ae60;
    color: white;
}

.btn-danger {
    background: #e74c3c;
    color: white;
}

.no-results {
    text-align: center;
    padding: 3rem;
    color: var(--text-light);
}

.no-results i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: var(--light-blue);
}

.no-results h3 {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--light-blue);
}

.page-info {
    color: var(--text-light);
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
    max-width: 900px;
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

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-group select,
.form-group textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--light-blue);
    border-radius: 8px;
    font-size: 1rem;
    background: white;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
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
    
    .filters-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .modal-content {
        width: 95%;
        margin: 2% auto;
    }
}
</style>

<script>
function viewApplication(id) {
    // Fetch application details via AJAX
    fetch(`/admin/admissions/view?id=${id}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalBody').innerHTML = html;
            document.getElementById('applicationModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error loading application:', error);
            alert('Failed to load application details');
        });
}

function updateStatus(id, status) {
    document.getElementById('statusApplicationId').value = id;
    document.getElementById('newStatus').value = status;
    document.getElementById('statusModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('applicationModal').style.display = 'none';
}

function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
}

function sortApplications(field) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('sortBy', field);
    window.location.href = currentUrl.toString();
}

function exportApplications() {
    const status = document.getElementById('status').value;
    window.location.href = `/admin/export?status=${status}`;
}

// Handle status form submission
document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('/admin/admissions/updateStatus', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Application status updated successfully');
            closeStatusModal();
            location.reload();
        } else {
            alert('Error: ' + result.error);
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
        alert('Failed to update application status');
    });
});

// Close modals when clicking outside
window.onclick = function(event) {
    const applicationModal = document.getElementById('applicationModal');
    const statusModal = document.getElementById('statusModal');
    
    if (event.target === applicationModal) {
        closeModal();
    }
    if (event.target === statusModal) {
        closeStatusModal();
    }
}
</script>
