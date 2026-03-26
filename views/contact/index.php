<div class="content-wrapper">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-content">
            <h1>Contact Us</h1>
            <p>Get in touch with Tiny Tots Creche - we're here to help!</p>
        </div>
    </section>

    <div class="contact-container">
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form-section">
                <div class="form-card">
                    <h2>Send Us a Message</h2>
                    <p>Have questions about our programs or want to schedule a visit? Fill out the form below and we'll get back to you soon!</p>
                    
                    <form id="contactForm" method="POST" action="/contact/submit" class="contact-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">
                                    <i class="fas fa-user"></i> Full Name *
                                </label>
                                <input type="text" id="name" name="name" required 
                                       placeholder="Enter your full name">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i> Email Address *
                                </label>
                                <input type="email" id="email" name="email" required 
                                       placeholder="your.email@example.com">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">
                                    <i class="fas fa-phone"></i> Phone Number *
                                </label>
                                <input type="tel" id="phone" name="phone" required 
                                       placeholder="081 421 0084">
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">
                                    <i class="fas fa-tag"></i> Subject *
                                </label>
                                <select id="subject" name="subject" required>
                                    <option value="">Select a subject</option>
                                    <option value="admission">Admission Inquiry</option>
                                    <option value="tour">Schedule a Visit</option>
                                    <option value="general">General Question</option>
                                    <option value="feedback">Feedback</option>
                                    <option value="complaint">Complaint</option>
                                    <option value="employment">Employment</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">
                                <i class="fas fa-comment"></i> Message *
                            </label>
                            <textarea id="message" name="message" rows="5" required 
                                      placeholder="Tell us more about your inquiry..."></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-large">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="contact-info-section">
                <div class="info-card">
                    <h3><i class="fas fa-map-marker-alt"></i> Visit Us</h3>
                    <p><?= htmlspecialchars($contactInfo['address']) ?></p>
                    <div class="map-placeholder">
                        <i class="fas fa-map"></i>
                        <span>Interactive Map</span>
                        <small>Click to get directions</small>
                    </div>
                </div>
                
                <div class="info-card">
                    <h3><i class="fas fa-phone"></i> Call Us</h3>
                    <p><strong>Main:</strong> <?= htmlspecialchars($contactInfo['phone']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($contactInfo['email']) ?></p>
                    <div class="contact-buttons">
                        <a href="tel:<?= str_replace(' ', '', $contactInfo['phone']) ?>" class="btn btn-secondary">
                            <i class="fas fa-phone"></i> Call Now
                        </a>
                        <a href="mailto:<?= htmlspecialchars($contactInfo['email']) ?>" class="btn btn-outline">
                            <i class="fas fa-envelope"></i> Email Us
                        </a>
                    </div>
                </div>
                
                <div class="info-card">
                    <h3><i class="fas fa-clock"></i> Operating Hours</h3>
                    <ul class="hours-list">
                        <?php foreach ($contactInfo['hours'] as $hour): ?>
                            <li><?= htmlspecialchars($hour) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="info-card emergency">
                    <h3><i class="fas fa-exclamation-triangle"></i> Emergency Contact</h3>
                    <p><strong>Emergency:</strong> <?= htmlspecialchars($emergencyContact['phone']) ?></p>
                    <p><strong>Alternative:</strong> <?= htmlspecialchars($emergencyContact['alternative']) ?></p>
                    <p class="emergency-note">For urgent matters outside operating hours, please use our emergency contact.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <section class="additional-info">
        <div class="container">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Admissions</h3>
                    <p>Learn about our admission process, requirements, and current availability for different grade levels.</p>
                    <a href="/admission" class="btn btn-secondary">View Admission Info</a>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-images"></i>
                    </div>
                    <h3>Gallery</h3>
                    <p>Take a virtual tour of our facilities and see our happy children learning and playing.</p>
                    <a href="/gallery" class="btn btn-secondary">View Gallery</a>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h3>FAQ</h3>
                    <p>Find answers to commonly asked questions about our programs, policies, and procedures.</p>
                    <a href="/faq" class="btn btn-secondary">View FAQ</a>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Contact Page Styles */
.page-hero {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 4rem 0;
    text-align: center;
    margin-bottom: 3rem;
}

.page-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.page-hero p {
    font-size: 1.3rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.contact-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.contact-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
    margin-bottom: 4rem;
}

.form-card {
    background: white;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
}

.form-card h2 {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 600;
}

.form-card > p {
    color: var(--text-light);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.contact-form {
    margin-top: 2rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-group label i {
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--light-blue);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--warm-white);
    font-family: 'Poppins', sans-serif;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 8px rgba(135, 206, 235, 0.3);
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

.form-actions {
    margin-top: 2rem;
}

.info-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.info-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: 0 8px 25px var(--shadow-light);
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px var(--shadow-medium);
}

.info-card h3 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 600;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
}

.info-card h3 i {
    margin-right: 0.8rem;
    font-size: 1.2rem;
}

.info-card p {
    color: var(--text-light);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.info-card p strong {
    color: var(--text-dark);
    font-weight: 600;
}

.map-placeholder {
    background: linear-gradient(135deg, var(--light-blue), var(--primary-color));
    color: var(--text-dark);
    padding: 2rem;
    border-radius: 15px;
    text-align: center;
    margin-top: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.map-placeholder:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px var(--shadow-medium);
}

.map-placeholder i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
    display: block;
}

.map-placeholder span {
    font-size: 1.2rem;
    font-weight: 600;
    display: block;
    margin-bottom: 0.5rem;
}

.map-placeholder small {
    opacity: 0.8;
    font-size: 0.9rem;
}

.contact-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}

.hours-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.hours-list li {
    color: var(--text-light);
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--light-blue);
    display: flex;
    justify-content: space-between;
}

.hours-list li:last-child {
    border-bottom: none;
}

.info-card.emergency {
    background: linear-gradient(135deg, #ff6b6b, #ff8e53);
    color: white;
}

.info-card.emergency h3 {
    color: white;
}

.info-card.emergency p {
    color: white;
    opacity: 0.9;
}

.emergency-note {
    font-size: 0.9rem;
    font-style: italic;
    margin-top: 1rem;
}

.additional-info {
    padding: 4rem 0;
    background: white;
    border-radius: 20px;
    margin-bottom: 3rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.info-item {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 8px 25px var(--shadow-light);
    transition: all 0.3s ease;
    border: 2px solid var(--light-blue);
}

.info-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px var(--shadow-medium);
    border-color: var(--primary-color);
}

.info-icon {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
}

.info-item h3 {
    font-size: 1.5rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 600;
}

.info-item p {
    color: var(--text-light);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

@media (max-width: 768px) {
    .page-hero h1 {
        font-size: 2rem;
    }
    
    .page-hero p {
        font-size: 1.1rem;
    }
    
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-card {
        padding: 2rem;
    }
    
    .contact-buttons {
        flex-direction: column;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
