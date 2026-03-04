<div class="content-wrapper">
    <!-- Confetti Animation -->
    <div class="confetti-container">
        <div class="confetti"></div>
        <div class="confetti"></div>
        <div class="confetti"></div>
        <div class="confetti"></div>
        <div class="confetti"></div>
        <div class="confetti"></div>
        <div class="confetti"></div>
        <div class="confetti"></div>
        <div class="confetti"></div>
        <div class="confetti"></div>
    </div>
    
    <div class="success-container">
        <div class="success-card">
            <div class="success-header">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>🎉 Application Submitted Successfully! 🎉</h1>
                <p class="success-subtitle">Congratulations on taking the first step toward your child's bright future at Tiny Tots Creche!</p>
            </div>
            
            <div class="success-content">
                <div class="application-summary">
                    <h2><i class="fas fa-file-alt"></i> Application Details</h2>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <label>Application ID:</label>
                            <span class="highlight"><?= htmlspecialchars($applicationId) ?></span>
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
                                <h4>📧 Email Confirmation</h4>
                                <p>You will receive an email confirmation within 24 hours</p>
                            </div>
                        </li>
                        <li>
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4>🔍 Application Review</h4>
                                <p>Our admissions team will carefully review your application</p>
                            </div>
                        </li>
                        <li>
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4>📞 Contact & Interview</h4>
                                <p>You may be contacted for additional information or an interview</p>
                            </div>
                        </li>
                        <li>
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h4>📋 Status Updates</h4>
                                <p>Application status updates will be sent via email</p>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div class="celebration-message">
                    <div class="message-card">
                        <h3>🌟 Welcome to the Tiny Tots Family! 🌟</h3>
                        <p>We're excited to partner with you in your child's educational journey. Our dedicated team is committed to providing a nurturing, safe, and stimulating environment where your little one can thrive and grow.</p>
                        <div class="key-points">
                            <div class="point">
                                <i class="fas fa-heart"></i>
                                <span>Loving & Caring Environment</span>
                            </div>
                            <div class="point">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Quality Early Education</span>
                            </div>
                            <div class="point">
                                <i class="fas fa-shield-alt"></i>
                                <span>Safe & Secure Facilities</span>
                            </div>
                            <div class="point">
                                <i class="fas fa-users"></i>
                                <span>Experienced & Dedicated Staff</span>
                            </div>
                        </div>
                    </div>
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
                    <a href="/parent/portal" class="btn btn-primary btn-large">
                        <i class="fas fa-tachometer-alt"></i> Parent Portal
                    </a>
                    <a href="/" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                    <a href="/gallery" class="btn btn-outline">
                        <i class="fas fa-images"></i> View Gallery
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Impressive Success Page Styling */
.confetti-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
}

.confetti {
    position: absolute;
    width: 10px;
    height: 10px;
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #f9ca24, #f0932b, #eb4d4b, #6ab04c, #130f40);
    animation: confetti-fall 3s linear infinite;
}

.confetti:nth-child(1) { left: 10%; animation-delay: 0s; background: #ff6b6b; }
.confetti:nth-child(2) { left: 20%; animation-delay: 0.2s; background: #4ecdc4; }
.confetti:nth-child(3) { left: 30%; animation-delay: 0.4s; background: #45b7d1; }
.confetti:nth-child(4) { left: 40%; animation-delay: 0.6s; background: #f9ca24; }
.confetti:nth-child(5) { left: 50%; animation-delay: 0.8s; background: #f0932b; }
.confetti:nth-child(6) { left: 60%; animation-delay: 1s; background: #eb4d4b; }
.confetti:nth-child(7) { left: 70%; animation-delay: 1.2s; background: #6ab04c; }
.confetti:nth-child(8) { left: 80%; animation-delay: 1.4s; background: #130f40; }
.confetti:nth-child(9) { left: 90%; animation-delay: 1.6s; background: #ff6b6b; }
.confetti:nth-child(10) { left: 95%; animation-delay: 1.8s; background: #4ecdc4; }

@keyframes confetti-fall {
    0% {
        transform: translateY(-100vh) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}

.success-container {
    padding: 2rem;
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.success-card {
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: relative;
}

.success-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #28a745, #20c997, #17a2b8, #007bff);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.success-header {
    text-align: center;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    position: relative;
}

.success-icon {
    font-size: 4rem;
    color: #28a745;
    margin-bottom: 1rem;
    animation: bounceIn 1s ease-out;
}

@keyframes bounceIn {
    0% { transform: scale(0); opacity: 0; }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); opacity: 1; }
}

.success-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #155724;
    margin: 1rem 0;
    animation: slideIn 0.8s ease-out;
}

@keyframes slideIn {
    from { transform: translateX(-50px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.success-subtitle {
    font-size: 1.2rem;
    color: #155724;
    margin: 0;
    font-weight: 500;
}

.highlight {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-weight: 600;
    color: #856404;
    border: 2px solid #ffc107;
}

.celebration-message {
    margin: 2rem 0;
    animation: fadeIn 1s ease-out 0.5s both;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.message-card {
    background: linear-gradient(135deg, #e7f3ff, #cfe2ff);
    border-radius: 15px;
    padding: 2rem;
    border: 2px solid #b3d9ff;
    text-align: center;
}

.message-card h3 {
    font-size: 1.8rem;
    color: #004085;
    margin: 0 0 1rem 0;
}

.key-points {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.point {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.point i {
    font-size: 1.5rem;
    color: #007bff;
}

.btn-large {
    padding: 1rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-large:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
    .success-header {
        padding: 2rem 1rem;
    }
    
    .success-header h1 {
        font-size: 2rem;
    }
    
    .key-points {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-large {
        width: 100%;
        text-align: center;
    }
}
</style>

<script>
// Auto-hide confetti after 5 seconds
setTimeout(() => {
    const confettiContainer = document.querySelector('.confetti-container');
    if (confettiContainer) {
        confettiContainer.style.display = 'none';
    }
}, 5000);
</script>
