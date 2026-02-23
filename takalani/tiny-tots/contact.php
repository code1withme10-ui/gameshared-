<?php
require_once 'includes/functions.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = '';
    $success = '';
    
    try {
        // Validate required fields
        $requiredFields = ['name', 'email', 'subject', 'message'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all required fields.");
            }
        }
        
        // Validate email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please enter a valid email address.");
        }
        
        // Validate phone (optional)
        if (!empty($_POST['phone']) && !preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
            throw new Exception("Please enter a valid 10-digit phone number.");
        }
        
        // Sanitize input
        $contactData = [
            'name' => sanitizeInput($_POST['name']),
            'email' => sanitizeInput($_POST['email']),
            'phone' => sanitizeInput($_POST['phone'] ?? ''),
            'subject' => sanitizeInput($_POST['subject']),
            'message' => sanitizeInput($_POST['message']),
            'submittedAt' => date('Y-m-d H:i:s')
        ];
        
        // Here you would typically send an email or save to database
        // For demo purposes, we'll just show success message
        $success = "✅ Your message has been sent successfully! We'll respond within 24 hours.";
        
    } catch (Exception $e) {
        $error = "❌ " . $e->getMessage();
    }
}

require_once 'includes/header.php';
?>

<main class="home-container">
    <section class="contact-hero">
        <h1>📞 Contact Us</h1>
        <p>Get in touch with Tiny Tots Creche - we're here to help!</p>
    </section>

    <div class="contact-content">
        <div class="contact-info-section">
            <h2>📍 Our Information</h2>
            
            <div class="contact-card">
                <h3>🏢 Address</h3>
                <p>4 Copper Street<br>Musina, Limpopo<br>0900</p>
            </div>
            
            <div class="contact-card">
                <h3>📱 Contact Details</h3>
                <p><strong>Phone:</strong> 081 421 0084</p>
                <p><strong>Email:</strong> mollerv40@gmail.com</p>
                <p><strong>EMIS NR:</strong> 973304431</p>
            </div>
            
            <div class="contact-card">
                <h3>🕐 Operating Hours</h3>
                <p><strong>Monday - Friday:</strong><br>7:00 AM - 5:30 PM</p>
                <p><strong>Saturday - Sunday:</strong><br>Closed</p>
            </div>
            
            <div class="emergency-card">
                <h3>🚨 Emergency Contact</h3>
                <p>For urgent matters outside operating hours:</p>
                <p><strong>Emergency Phone:</strong> 081 421 0084</p>
                <p><small>We'll respond to emergency calls immediately</small></p>
            </div>
        </div>
        
        <div class="contact-form-section">
            <h2>📝 Send Us a Message</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <form id="contactForm" method="POST" class="contact-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" placeholder="0812345678">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <select id="subject" name="subject" required>
                            <option value="">-- Select Subject --</option>
                            <option value="admission">Admission Inquiry</option>
                            <option value="general">General Information</option>
                            <option value="tour">Schedule a Visit</option>
                            <option value="fees">Fee Information</option>
                            <option value="feedback">Feedback</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" rows="6" required 
                              placeholder="Tell us how we can help you..."></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Send Message</button>
                    <button type="reset" class="btn btn-secondary">Clear Form</button>
                </div>
            </form>
        </div>
    </div>
    
    <section class="location-section">
        <h2>🗺️ Find Us</h2>
        <div class="map-container">
            <div class="map-placeholder">
                <h3>📍 Tiny Tots Creche</h3>
                <p>4 Copper Street, Musina, Limpopo, 0900</p>
                <p>Conveniently located in the heart of Musina</p>
                <div class="directions">
                    <h4>🧭 Directions:</h4>
                    <ul>
                        <li>From Musina CBD, head towards the residential area</li>
                        <li>Turn onto Copper Street</li>
                        <li>We're located on the left side, look for our colorful sign!</li>
                        <li>Safe parking available on premises</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Contact Page Styles */
.contact-hero {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, var(--light-blue), var(--primary-color));
    border-radius: 20px;
    margin-bottom: 3rem;
    box-shadow: 0 8px 30px var(--shadow-light);
}

.contact-hero h1 {
    color: var(--secondary-color);
    font-size: 2.8rem;
    margin: 0 0 1rem 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.contact-hero p {
    color: var(--text-dark);
    font-size: 1.3rem;
    margin: 0;
    font-weight: 500;
}

.contact-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-bottom: 4rem;
}

.contact-info-section h2 {
    color: var(--secondary-color);
    margin: 0 0 2rem 0;
    font-size: 2rem;
    font-weight: 600;
}

.contact-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px var(--shadow-light);
    margin-bottom: 1.5rem;
    transition: transform 0.3s ease;
}

.contact-card:hover {
    transform: translateY(-5px);
}

.contact-card h3 {
    color: var(--primary-color);
    margin: 0 0 1rem 0;
    font-size: 1.3rem;
    font-weight: 600;
}

.contact-card p {
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 1rem;
    line-height: 1.5;
}

.emergency-card {
    background: linear-gradient(135deg, #ff6b6b, #ffa500);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
    margin-top: 2rem;
}

.emergency-card h3 {
    color: white;
    margin: 0 0 1rem 0;
    font-size: 1.3rem;
    font-weight: 600;
}

.emergency-card p {
    color: white;
    margin-bottom: 0.5rem;
}

.emergency-card small {
    opacity: 0.8;
    font-style: italic;
}

.contact-form-section h2 {
    color: var(--secondary-color);
    margin: 0 0 2rem 0;
    font-size: 2rem;
    font-weight: 600;
}

.contact-form {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-light);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    color: var(--text-dark);
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 1rem;
    border: 2px solid var(--light-blue);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--warm-white);
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
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

.location-section {
    margin-top: 4rem;
}

.location-section h2 {
    color: var(--secondary-color);
    text-align: center;
    font-size: 2rem;
    margin-bottom: 2rem;
    font-weight: 600;
}

.map-container {
    max-width: 800px;
    margin: 0 auto;
}

.map-placeholder {
    background: linear-gradient(135deg, var(--light-blue), var(--primary-color));
    padding: 3rem;
    border-radius: 20px;
    text-align: center;
    color: var(--text-dark);
    box-shadow: 0 8px 30px var(--shadow-light);
}

.map-placeholder h3 {
    color: var(--secondary-color);
    margin: 0 0 1rem 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.directions {
    margin-top: 2rem;
    text-align: left;
    background: rgba(255, 255, 255, 0.2);
    padding: 1.5rem;
    border-radius: 10px;
}

.directions h4 {
    color: var(--text-dark);
    margin: 0 0 1rem 0;
    font-size: 1.2rem;
}

.directions ul {
    list-style: none;
    padding: 0;
}

.directions li {
    margin-bottom: 0.8rem;
    padding-left: 1.5rem;
    position: relative;
    font-size: 1rem;
}

.directions li::before {
    content: '→';
    position: absolute;
    left: 0;
    color: var(--secondary-color);
    font-weight: bold;
}

@media (max-width: 768px) {
    .contact-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 250px;
        text-align: center;
    }
    
    .map-placeholder {
        padding: 2rem 1.5rem;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
