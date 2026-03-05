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
                        <div class="sortable" onclick="sortApplications('submitted_at')">
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
                                <span class="submit-date"><?= date('M j, Y', strtotime($application['submittedAt'] ?? $application['submitted_at'] ?? '1970-01-01')) ?></span>
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
                                    
                                    <?php if ($application['status'] !== 'Approved' && $application['status'] !== 'Rejected'): ?>
                                        <button onclick="showAdmitModal('<?= htmlspecialchars($application['id']) ?>', '<?= htmlspecialchars($application['childFirstName'] . ' ' . $application['childSurname']) ?>')" 
                                                class="btn btn-sm btn-success" title="Admit Student">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="showRejectModal('<?= htmlspecialchars($application['id']) ?>', '<?= htmlspecialchars($application['childFirstName'] . ' ' . $application['childSurname']) ?>')" 
                                                class="btn btn-sm btn-danger" title="Reject Application">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php elseif ($application['status'] === 'Approved'): ?>
                                        <button class="btn btn-sm btn-disabled" disabled title="Already Admitted">
                                            <i class="fas fa-check"></i> Admitted
                                        </button>
                                    <?php elseif ($application['status'] === 'Rejected'): ?>
                                        <button class="btn btn-sm btn-disabled" disabled title="Already Rejected">
                                            <i class="fas fa-times"></i> Rejected
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
window.csrfToken = '<?= htmlspecialchars($csrfToken ?? '') ?>';
</script>

<script>
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
    const admitModal = document.getElementById('admitModal');
    const rejectModal = document.getElementById('rejectModal');
    const applicationModal = document.getElementById('applicationModal');
    const statusModal = document.getElementById('statusModal');
    
    if (event.target === admitModal) {
        closeAdmitModal();
    }
    if (event.target === rejectModal) {
        closeRejectModal();
    }
    if (event.target === applicationModal) {
        closeModal();
    }
    if (event.target === statusModal) {
        closeStatusModal();
    }
}
</script>
