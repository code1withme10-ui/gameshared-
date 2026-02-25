<div class="content-wrapper">
    <div class="success-container">
        <div class="success-card">
            <div class="success-header">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Application Submitted Successfully!</h1>
                <p>Thank you for your interest in Tiny Tots Creche</p>
            </div>
            
            <div class="success-content">
                <div class="application-summary">
                    <h2><i class="fas fa-file-alt"></i> Application Details</h2>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <label>Application ID:</label>
                            <span><?= htmlspecialchars($applicationId) ?></span>
                        </div>
                        <div class="summary-item">
                            <label>Status:</label>
                            <span class="status-pending">Pending Review</span>
                        </div>
                        <div class="summary-item">
                            <label>Submitted:</label>
                            <span><?= date('F j, Y, g:i a') ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="next-steps">
                    <h2><i class="fas fa-tasks"></i> What Happens Next?</h2>
                    <ul class="steps-list">
                        <li>
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4>You will receive an email confirmation within 24 hours</h4>
                            </div>
                        </li>
                        <li>
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>Our admissions team will review your application</h4>
                            </div>
                        </li>
                        <li>
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4>You may be contacted for additional information or an interview</h4>
                            </div>
                        </li>
                        <li>
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h4>Application status updates will be sent via email</h4>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div class="contact-info">
                    <h2><i class="fas fa-phone"></i> Need Assistance?</h2>
                    <div class="contact-grid">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>Phone:</strong>
                                <span><?= htmlspecialchars($contactInfo['phone']) ?></span>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email:</strong>
                                <span><?= htmlspecialchars($contactInfo['email']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="/" class="btn btn-primary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                    <a href="/gallery" class="btn btn-secondary">
                        <i class="fas fa-images"></i> View Gallery
                    </a>
                    <a href="/contact" class="btn btn-outline">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
