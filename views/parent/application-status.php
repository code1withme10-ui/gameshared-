<?php require_once VIEWS_PATH . '/layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="application-status-container">
        <div class="status-header">
            <div class="breadcrumb">
                <a href="/parent/portal">← Back to Dashboard</a>
            </div>
            <h1>Application Status Details</h1>
        </div>
        
        <div class="status-content">
            <!-- Application Overview -->
            <div class="overview-card">
                <div class="overview-header">
                    <div class="child-info">
                        <h2><?= htmlspecialchars($application['child_name'] ?? $application['childFirstName'] . ' ' . $application['childSurname']) ?></h2>
                        <p>Application ID: <?= htmlspecialchars($application['id']) ?></p>
                    </div>
                    <div class="status-badge-large">
                        <?php
                        $statusClass = '';
                        $statusIcon = '';
                        $statusText = '';
                        switch ($application['status']) {
                            case 'pending':
                                $statusClass = 'status-pending';
                                $statusIcon = 'fas fa-clock';
                                $statusText = 'Pending Review';
                                break;
                            case 'under-review':
                                $statusClass = 'status-review';
                                $statusIcon = 'fas fa-eye';
                                $statusText = 'Under Review';
                                break;
                            case 'approved':
                                $statusClass = 'status-approved';
                                $statusIcon = 'fas fa-check-circle';
                                $statusText = 'Approved';
                                break;
                            case 'rejected':
                                $statusClass = 'status-rejected';
                                $statusIcon = 'fas fa-times-circle';
                                $statusText = 'Rejected';
                                break;
                            default:
                                $statusClass = 'status-pending';
                                $statusIcon = 'fas fa-clock';
                                $statusText = 'Pending Review';
                        }
                        ?>
                        <span class="status-badge <?= $statusClass ?>">
                            <i class="<?= $statusIcon ?>"></i>
                            <?= $statusText ?>
                        </span>
                    </div>
                </div>
                
                <div class="overview-details">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Child's Age:</label>
                            <span><?= htmlspecialchars($application['child_age']) ?> years</span>
                        </div>
                        <div class="detail-item">
                            <label>Grade Applied:</label>
                            <span><?= htmlspecialchars($application['grade']) ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Date Submitted:</label>
                            <span><?= date('F j, Y, g:i a', strtotime($application['submitted_at'])) ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Last Updated:</label>
                            <span><?= date('F j, Y, g:i a', strtotime($application['updated_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Application Timeline -->
            <div class="timeline-card">
                <h3>📅 Application Timeline</h3>
                <div class="timeline">
                    <div class="timeline-item completed">
                        <div class="timeline-marker">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>Application Submitted</h4>
                            <p><?= date('F j, Y, g:i a', strtotime($application['submitted_at'])) ?></p>
                            <span>Your application has been successfully submitted and is awaiting review.</span>
                        </div>
                    </div>
                    
                    <?php if ($application['status'] !== 'pending'): ?>
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h4>Application Received</h4>
                                <p><?= date('F j, Y', strtotime($application['submitted_at'] . ' +1 day')) ?></p>
                                <span>Your application has been received by our admissions team.</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($application['status'] === 'under-review' || $application['status'] === 'approved' || $application['status'] === 'rejected'): ?>
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h4>Under Review</h4>
                                <p><?= date('F j, Y', strtotime($application['updated_at'])) ?></p>
                                <span>Your application is currently being reviewed by our admissions committee.</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($application['status'] === 'approved'): ?>
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h4>Application Approved</h4>
                                <p><?= date('F j, Y', strtotime($application['updated_at'])) ?></p>
                                <span>Congratulations! Your application has been approved. You will receive further instructions via email.</span>
                            </div>
                        </div>
                    <?php elseif ($application['status'] === 'rejected'): ?>
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="timeline-content">
                                <h4>Application Not Approved</h4>
                                <p><?= date('F j, Y', strtotime($application['updated_at'])) ?></p>
                                <span>We regret to inform you that your application was not approved at this time.</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="timeline-item pending">
                            <div class="timeline-marker">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="timeline-content">
                                <h4>Decision Pending</h4>
                                <p>Expected within 5-7 business days</p>
                                <span>Our admissions team is reviewing your application. You will be notified of the decision via email.</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Next Steps -->
            <div class="next-steps-card">
                <h3>📋 Next Steps</h3>
                <div class="steps-list">
                    <?php if ($application['status'] === 'pending'): ?>
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4>Wait for Review</h4>
                                <p>Our admissions team will review your application within 5-7 business days.</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>Check Email</h4>
                                <p>You will receive an email notification when there's an update on your application.</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4>Possible Interview</h4>
                                <p>You may be contacted for an interview or additional information.</p>
                            </div>
                        </div>
                    <?php elseif ($application['status'] === 'under-review'): ?>
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4>Application Under Review</h4>
                                <p>Your application is currently being reviewed by our admissions committee.</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>Wait for Decision</h4>
                                <p>You will receive a decision within the next few business days.</p>
                            </div>
                        </div>
                    <?php elseif ($application['status'] === 'approved'): ?>
                        <div class="step-item completed">
                            <div class="step-number">✓</div>
                            <div class="step-content">
                                <h4>Application Approved!</h4>
                                <p>Congratulations! Check your email for next steps and enrollment instructions.</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4>Accept Offer</h4>
                                <p>Follow the instructions in your approval email to accept the placement.</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>Complete Enrollment</h4>
                                <p>Submit required documents and complete the enrollment process.</p>
                            </div>
                        </div>
                    <?php elseif ($application['status'] === 'rejected'): ?>
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4>Review Feedback</h4>
                                <p>Check your email for any feedback regarding the decision.</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>Consider Reapplication</h4>
                                <p>You may reapply in the future if circumstances change.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="contact-card">
                <h3>📞 Need Assistance?</h3>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Admissions Office:</strong>
                            <span>+27 12 345 6789</span>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email:</strong>
                            <span>admissions@tinytotscreche.co.za</span>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Office Hours:</strong>
                            <span>Monday - Friday: 8:00 AM - 4:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="/parent/portal" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <?php if ($application['status'] === 'pending'): ?>
                    <a href="/admission" class="btn btn-secondary">
                        <i class="fas fa-edit"></i> Edit Application
                    </a>
                <?php endif; ?>
                <a href="/contact" class="btn btn-outline">
                    <i class="fas fa-envelope"></i> Contact Admissions
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Application Status Page Styles */
.application-status-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem;
}

.status-header {
    margin-bottom: 2rem;
}

.breadcrumb a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 1rem;
    display: inline-block;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.status-header h1 {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin: 0;
}

.overview-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 25px var(--shadow-light);
    margin-bottom: 2rem;
}

.overview-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.child-info h2 {
    font-size: 1.8rem;
    color: var(--primary-color);
    margin: 0 0 0.5rem 0;
}

.child-info p {
    color: var(--text-light);
    margin: 0;
}

.status-badge-large .status-badge {
    font-size: 1rem;
    padding: 0.8rem 1.5rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    padding: 1rem;
    background: var(--warm-white);
    border-radius: 10px;
    border: 1px solid var(--light-blue);
}

.detail-item label {
    color: var(--text-light);
    font-weight: 500;
}

.detail-item span {
    color: var(--text-dark);
    font-weight: 600;
}

.timeline-card, .next-steps-card, .contact-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 25px var(--shadow-light);
    margin-bottom: 2rem;
}

.timeline-card h3, .next-steps-card h3, .contact-card h3 {
    font-size: 1.5rem;
    color: var(--primary-color);
    margin: 0 0 2rem 0;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--light-blue);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    color: white;
}

.timeline-item.pending .timeline-marker {
    background: #ffc107;
    color: white;
}

.timeline-content h4 {
    font-size: 1.1rem;
    color: var(--text-dark);
    margin: 0 0 0.5rem 0;
}

.timeline-content p {
    color: var(--primary-color);
    font-weight: 500;
    margin: 0 0 0.5rem 0;
}

.timeline-content span {
    color: var(--text-light);
    font-size: 0.9rem;
}

.steps-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.step-item {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.step-number {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    flex-shrink: 0;
}

.step-item.completed .step-number {
    background: #28a745;
}

.step-content h4 {
    font-size: 1.1rem;
    color: var(--text-dark);
    margin: 0 0 0.5rem 0;
}

.step-content p {
    color: var(--text-light);
    margin: 0;
}

.contact-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--warm-white);
    border-radius: 10px;
    border: 1px solid var(--light-blue);
}

.contact-item i {
    font-size: 1.5rem;
    color: var(--primary-color);
}

.contact-item strong {
    display: block;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.contact-item span {
    color: var(--text-light);
    font-size: 0.9rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .application-status-container {
        padding: 1rem;
    }
    
    .status-header h1 {
        font-size: 2rem;
    }
    
    .overview-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline-marker {
        left: -1.5rem;
        width: 2rem;
        height: 2rem;
        font-size: 0.7rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php require_once VIEWS_PATH . '/layouts/footer.php'; ?>
