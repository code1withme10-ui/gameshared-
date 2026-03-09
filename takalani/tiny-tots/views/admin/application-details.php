<div class="content-wrapper">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-content">
            <h1>Application Details</h1>
            <p>Review complete admission application information</p>
        </div>
    </section>

    <div class="admin-container">
        <?php if ($admission): ?>
            <!-- Application Header -->
            <div class="application-header">
                <div class="header-info">
                    <h2>Application #<?= htmlspecialchars($admission['applicationID']) ?></h2>
                    <div class="status-section">
                        <span class="status-badge status-<?= strtolower($admission['status']) ?>">
                            <?= htmlspecialchars($admission['status']) ?>
                        </span>
                        <small>Submitted: <?= date('F j, Y \a\t g:i A', strtotime($admission['submittedAt'] ?? $admission['submitted_at'] ?? '1970-01-01')) ?></small>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <?php if ($admission['status'] !== 'Approved' && $admission['status'] !== 'Rejected'): ?>
                        <button onclick="showAdmitModal('<?= htmlspecialchars($admission['id']) ?>', '<?= htmlspecialchars($admission['childFirstName'] . ' ' . $admission['childSurname']) ?>')" 
                                class="btn btn-success">
                            <i class="fas fa-check"></i> Admit Student
                        </button>
                        <button onclick="showRejectModal('<?= htmlspecialchars($admission['id']) ?>', '<?= htmlspecialchars($admission['childFirstName'] . ' ' . $admission['childSurname']) ?>')" 
                                class="btn btn-danger">
                            <i class="fas fa-times"></i> Reject Application
                        </button>
                    <?php endif; ?>
                    
                    <button onclick="window.print()" class="btn btn-outline">
                        <i class="fas fa-print"></i> Print Application
                    </button>
                    
                    <button onclick="window.history.back()" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </button>
                </div>
            </div>

            <!-- Application Sections -->
            <div class="application-content">
                <!-- Child Information -->
                <div class="info-section">
                    <h3><i class="fas fa-child"></i> Child Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Full Name</label>
                            <span><?= htmlspecialchars($admission['childFirstName'] . ' ' . $admission['childSurname']) ?></span>
                        </div>
                        <div class="info-item">
                            <label>Date of Birth</label>
                            <span><?= date('F j, Y', strtotime($admission['dateOfBirth'])) ?> (Age: <?= $admission['age'] ?> years)</span>
                        </div>
                        <div class="info-item">
                            <label>Gender</label>
                            <span><?= htmlspecialchars(ucfirst($admission['childGender'])) ?></span>
                        </div>
                        <div class="info-item">
                            <label>Grade Applying For</label>
                            <span><?= htmlspecialchars($gradeCategories[$admission['gradeApplyingFor']] ?? 'N/A') ?></span>
                        </div>
                        <div class="info-item">
                            <label>Home Address</label>
                            <span><?= htmlspecialchars($admission['childAddress'] ?? 'Not provided') ?></span>
                        </div>
                        <?php if (!empty($admission['medicalAllergies'])): ?>
                            <div class="info-item full-width">
                                <label>Medical Allergies</label>
                                <span><?= htmlspecialchars($admission['medicalAllergies']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($admission['specialNeeds'])): ?>
                            <div class="info-item full-width">
                                <label>Special Needs</label>
                                <span><?= htmlspecialchars($admission['specialNeeds']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Parent/Guardian Information -->
                <div class="info-section">
                    <h3><i class="fas fa-user"></i> Parent/Guardian Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Full Name</label>
                            <span><?= htmlspecialchars($admission['parentFirstName'] . ' ' . $admission['parentSurname']) ?></span>
                        </div>
                        <div class="info-item">
                            <label>Relationship to Child</label>
                            <span><?= htmlspecialchars($admission['relationshipToChild'] ?? 'Not specified') ?></span>
                        </div>
                        <div class="info-item">
                            <label>ID Number</label>
                            <span><?= htmlspecialchars($admission['parentIdNumber']) ?></span>
                        </div>
                        <div class="info-item">
                            <label>Residential Address</label>
                            <span><?= htmlspecialchars($admission['residentialAddress']) ?></span>
                        </div>
                        <div class="info-item">
                            <label>Email Address</label>
                            <span><?= htmlspecialchars($admission['emailAddress']) ?></span>
                        </div>
                        <div class="info-item">
                            <label>Phone Number</label>
                            <span><?= htmlspecialchars($admission['contactNumber']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Mother Information (if provided) -->
                <?php if (!empty($admission['motherFirstName'])): ?>
                    <div class="info-section">
                        <h3><i class="fas fa-female"></i> Mother Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Full Name</label>
                                <span><?= htmlspecialchars($admission['motherFirstName'] . ' ' . ($admission['motherSurname'] ?? '')) ?></span>
                            </div>
                            <?php if (!empty($admission['motherIdNumber'])): ?>
                                <div class="info-item">
                                    <label>ID Number</label>
                                    <span><?= htmlspecialchars($admission['motherIdNumber']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($admission['motherOccupation'])): ?>
                                <div class="info-item">
                                    <label>Occupation</label>
                                    <span><?= htmlspecialchars($admission['motherOccupation']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Father Information (if provided) -->
                <?php if (!empty($admission['fatherFirstName'])): ?>
                    <div class="info-section">
                        <h3><i class="fas fa-male"></i> Father Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Full Name</label>
                                <span><?= htmlspecialchars($admission['fatherFirstName'] . ' ' . ($admission['fatherSurname'] ?? '')) ?></span>
                            </div>
                            <?php if (!empty($admission['fatherIdNumber'])): ?>
                                <div class="info-item">
                                    <label>ID Number</label>
                                    <span><?= htmlspecialchars($admission['fatherIdNumber']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($admission['fatherOccupation'])): ?>
                                <div class="info-item">
                                    <label>Occupation</label>
                                    <span><?= htmlspecialchars($admission['fatherOccupation']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Emergency Contact -->
                <div class="info-section">
                    <h3><i class="fas fa-phone-alt"></i> Emergency Contact</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Full Name</label>
                            <span><?= htmlspecialchars($admission['emergencyContactName']) ?></span>
                        </div>
                        <div class="info-item">
                            <label>Address</label>
                            <span><?= htmlspecialchars($admission['emergencyContactAddress'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="info-item">
                            <label>Phone Number</label>
                            <span><?= htmlspecialchars($admission['emergencyContactPhone']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Transportation -->
                <?php if (!empty($admission['transportation']) && $admission['transportation'] !== 'none'): ?>
                    <div class="info-section">
                        <h3><i class="fas fa-bus"></i> Transportation</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Transportation Required</label>
                                <span class="transportation-badge"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $admission['transportation']))) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Supporting Documents -->
                <div class="info-section">
                    <h3><i class="fas fa-file-upload"></i> Supporting Documents</h3>
                    <div class="documents-grid">
                        <?php if (!empty($admission['childBirthCertificate'])): ?>
                            <div class="document-item">
                                <i class="fas fa-file-pdf"></i>
                                <div class="document-info">
                                    <h4>Child Birth Certificate/ID</h4>
                                    <a href="/<?= htmlspecialchars($admission['childBirthCertificate']) ?>" target="_blank" class="btn btn-sm btn-outline">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($admission['parentIdDocument'])): ?>
                            <div class="document-item">
                                <i class="fas fa-file-pdf"></i>
                                <div class="document-info">
                                    <h4>Parent ID Document</h4>
                                    <a href="/<?= htmlspecialchars($admission['parentIdDocument']) ?>" target="_blank" class="btn btn-sm btn-outline">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($admission['clinicalReport'])): ?>
                            <div class="document-item">
                                <i class="fas fa-file-medical"></i>
                                <div class="document-info">
                                    <h4>Clinical Report</h4>
                                    <a href="/<?= htmlspecialchars($admission['clinicalReport']) ?>" target="_blank" class="btn btn-sm btn-outline">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($admission['previousSchoolReport'])): ?>
                            <div class="document-item">
                                <i class="fas fa-file-alt"></i>
                                <div class="document-info">
                                    <h4>Previous School Report</h4>
                                    <a href="/<?= htmlspecialchars($admission['previousSchoolReport']) ?>" target="_blank" class="btn btn-sm btn-outline">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status History -->
                <?php if (!empty($admission['statusHistory'])): ?>
                    <div class="info-section">
                        <h3><i class="fas fa-history"></i> Status History</h3>
                        <div class="history-timeline">
                            <?php foreach ($admission['statusHistory'] as $history): ?>
                                <div class="history-item">
                                    <div class="history-date">
                                        <?= date('M j, Y g:i A', strtotime($history['date'])) ?>
                                    </div>
                                    <div class="history-content">
                                        <div class="history-status">
                                            Status changed to <strong><?= htmlspecialchars($history['status']) ?></strong>
                                        </div>
                                        <?php if (!empty($history['notes'])): ?>
                                            <div class="history-notes">
                                                <strong>Notes:</strong> <?= htmlspecialchars($history['notes']) ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($history['changedBy'])): ?>
                                            <div class="history-user">
                                                <small>Changed by: <?= htmlspecialchars($history['changedBy']) ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="error-section">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Application Not Found</h3>
                <p>The requested application could not be found or may have been deleted.</p>
                <button onclick="window.history.back()" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Applications
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Application Details Styles */
.admin-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.application-header {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 30px var(--shadow-light);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
}

.header-info h2 {
    color: var(--text-dark);
    margin: 0 0 1rem 0;
    font-size: 1.8rem;
    font-weight: 600;
}

.status-section {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.status-section small {
    color: var(--text-light);
    font-style: italic;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-width: 200px;
}

.application-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.info-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 30px var(--shadow-light);
}

.info-section h3 {
    color: var(--primary-color);
    margin: 0 0 1.5rem 0;
    font-size: 1.4rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    font-weight: 600;
    color: var(--text-light);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    color: var(--text-dark);
    font-size: 1.1rem;
    line-height: 1.4;
}

.transportation-badge {
    background: var(--primary-color);
    color: white;
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.9rem;
    font-weight: 600;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.document-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border: 1px solid var(--light-blue);
    border-radius: 10px;
    background: var(--warm-white);
}

.document-item i {
    font-size: 2rem;
    color: var(--primary-color);
}

.document-info h4 {
    color: var(--text-dark);
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.history-timeline {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.history-item {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 1rem;
    padding: 1rem;
    border-left: 3px solid var(--primary-color);
    background: var(--warm-white);
    border-radius: 0 10px 10px 0;
}

.history-date {
    color: var(--primary-color);
    font-weight: 600;
    font-size: 0.9rem;
}

.history-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.history-status {
    color: var(--text-dark);
}

.history-notes {
    background: var(--light-blue);
    padding: 0.5rem;
    border-radius: 5px;
    margin-top: 0.5rem;
}

.history-user {
    color: var(--text-light);
    font-style: italic;
    margin-top: 0.5rem;
}

.error-section {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 30px var(--shadow-light);
}

.error-section i {
    font-size: 4rem;
    color: #e74c3c;
    margin-bottom: 1rem;
}

.error-section h3 {
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.error-section p {
    color: var(--text-light);
    margin-bottom: 2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-container {
        padding: 1rem 0.5rem;
    }
    
    .application-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .action-buttons {
        flex-direction: row;
        justify-content: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .documents-grid {
        grid-template-columns: 1fr;
    }
    
    .history-item {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
}

/* Print Styles */
@media print {
    .action-buttons,
    .page-hero {
        display: none;
    }
    
    .admin-container {
        padding: 0;
    }
    
    .info-section {
        box-shadow: none;
        border: 1px solid #ccc;
        page-break-inside: avoid;
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
    
    if (event.target === admitModal) {
        closeAdmitModal();
    }
    if (event.target === rejectModal) {
        closeRejectModal();
    }
}
</script>
