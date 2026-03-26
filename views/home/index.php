<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="hero-overlay"></div>
    </div>
    
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fas fa-star"></i>
            <span>Est. 2014 • Musina's Trusted Creche</span>
        </div>
        
        <h1 class="hero-title">
            Welcome to<br>
            <span class="highlight">Tiny Tots Creche</span>
        </h1>
        
        <p class="hero-subtitle">
            Where little minds grow, explore, and shine!
        </p>
        
        <p class="hero-tagline">
            <i class="fas fa-baby"></i> From 3 months to Grade RR • 
            <i class="fas fa-clock"></i> Aftercare available • 
            <i class="fas fa-heart"></i> Safe & Nurturing
        </p>
        
        <div class="hero-buttons">
            <a href="/register" class="btn btn-primary btn-large hero-btn-primary">
                <i class="fas fa-user-plus"></i> 
                <span>Register</span>
            </a>
            <a href="/login" class="btn btn-secondary btn-large hero-btn-secondary">
                <i class="fas fa-sign-in-alt"></i> 
                <span>Login</span>
            </a>
        </div>
        
        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-number">10+</div>
                <div class="stat-label">Years Experience</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Happy Children</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">5-Star Reviews</div>
            </div>
        </div>
    </div>
    
    <div class="hero-image">
        <div class="hero-visual">
            <div class="floating-elements">
                <div class="float-element float-1">
                    <i class="fas fa-book"></i>
                </div>
                <div class="float-element float-2">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="float-element float-3">
                    <i class="fas fa-music"></i>
                </div>
                <div class="float-element float-4">
                    <i class="fas fa-gamepad"></i>
                </div>
            </div>
            
            <a href="/admission" class="hero-main-visual" style="text-decoration: none; display: block; cursor: pointer;">
                <div class="creche-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="creche-text">
                    <h3 style="margin-bottom: 0;">Apply Now</h3>
                    <p style="margin-top: 5px;">Click to Start</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Authentication Section -->
<section class="auth-section">
    <div class="container">
        <div class="auth-content">
            <h2>Ready to Join Our Family?</h2>
            <p>Create an account or login to start your child's admission journey</p>
            
            <div class="auth-features">
                <a href="/forgot-password" class="feature-item" style="text-decoration: none; color: inherit; cursor: pointer;">
                    <i class="fas fa-shield-alt"></i>
                    <span>Secure Account<br><small style="font-size: 0.75rem; opacity: 0.8;">Forgot Password?</small></span>
                </a>
                <div class="feature-item">
                    <i class="fas fa-clock"></i>
                    <span>Save Progress</span>
                </div>
                <a href="/login" class="feature-item" style="text-decoration: none; color: inherit; cursor: pointer;">
                    <i class="fas fa-file-alt"></i>
                    <span>Track Applications<br><small style="font-size: 0.75rem; opacity: 0.8;">Login to Dashboard</small></span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Welcome Section -->
<section class="welcome-section">
    <div class="container">
        <div class="welcome-content">
            <h2>Welcome to <?= htmlspecialchars($heroTitle ?? 'Tiny Tots Creche') ?>!</h2>
            <p><?= htmlspecialchars($welcomeMessage ?? 'We provide a safe, nurturing, and stimulating environment where children are encouraged to discover their talents, build confidence, and develop a love for learning.') ?></p>
            <p><?= htmlspecialchars($welcomeDetails ?? 'Our dedicated caregivers are passionate about supporting every child\'s emotional, social, physical, and intellectual development through structured activities, creative play, and positive guidance.') ?></p>
        </div>
    </div>
</section>

<!-- Key Highlights -->
<section class="highlights-section">
    <div class="container">
        <h2 class="section-title">Why Choose Tiny Tots Creche?</h2>
        <div class="highlights-grid">
            <?php 
            $highlights = $highlights ?? [
                ['icon' => 'fas fa-home', 'title' => 'Home-Away-From-Home', 'description' => 'Warm, family-like atmosphere where children feel loved and secure', 'image' => ''],
                ['icon' => 'fas fa-user', 'title' => 'Individual Attention', 'description' => 'We understand each child\'s unique personality and needs', 'image' => ''],
                ['icon' => 'fas fa-handshake', 'title' => 'Parent Partnerships', 'description' => 'Open communication and active parent involvement', 'image' => ''],
                ['icon' => 'fas fa-star', 'title' => 'Holistic Development', 'description' => 'Nurturing emotional intelligence, social skills, and creativity', 'image' => '']
            ];
            foreach ($highlights as $highlight): 
                $bgImage = isset($highlight['image']) && $highlight['image'] ? "url('" . htmlspecialchars($highlight['image']) . "')" : 'none';
                $bgStyle = $bgImage !== 'none' ? "background: linear-gradient(to bottom, rgba(0,0,0,0.5), rgba(0,0,0,0.8)), $bgImage center/cover no-repeat; color: white; border: none;" : '';
                $titleStyle = $bgImage !== 'none' ? "color: white;" : "";
                $descStyle = $bgImage !== 'none' ? "color: #f1f1f1;" : "";
            ?>
                <div class="highlight-card" style="<?= $bgStyle ?>">
                    <div class="highlight-icon" style="font-size: 2.5rem; margin-bottom: 1rem; color: var(--secondary-color);"><i class="<?= htmlspecialchars($highlight['icon'] ?? 'fas fa-star') ?>"></i></div>
                    <h3 style="<?= $titleStyle ?>"><?= htmlspecialchars($highlight['title']) ?></h3>
                    <p style="<?= $descStyle ?>"><?= htmlspecialchars($highlight['description']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Give Your Child the Best Start?</h2>
            <p>Join our Tiny Tots family and watch your child grow in a loving, educational environment.</p>
            <div class="cta-buttons">
                <a href="/admission" class="btn btn-primary btn-large">
                    <i class="fas fa-graduation-cap"></i> Start Application
                </a>
                <a href="/contact" class="btn btn-outline btn-large">
                    <i class="fas fa-phone"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contact Info -->
<section class="contact-info-section">
    <div class="container">
        <div class="contact-info-grid">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Location</h3>
                <p><?= htmlspecialchars($contactInfo['address'] ?? '4 Copper Street, Musina, Limpopo, 0900') ?></p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h3>Phone</h3>
                <p><?= htmlspecialchars($contactInfo['phone'] ?? '081 421 0084') ?></p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email</h3>
                <p><?= htmlspecialchars($contactInfo['email'] ?? 'mollerv40@gmail.com') ?></p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Hours</h3>
                <p><?= htmlspecialchars($contactInfo['hours'] ?? 'Monday to Friday: 7:00 AM - 5:30 PM') ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Recent News/Updates -->
<section class="news-section">
    <div class="container">
        <h2 class="section-title">Latest Updates</h2>
        <div class="news-grid">
            <article class="news-card">
                <div class="news-date">March 2026</div>
                <h3>2026 Admissions Now Open</h3>
                <p>We are now accepting applications for 2026 academic year. Limited spaces available!</p>
                <a href="/admission" class="read-more">Apply Now <i class="fas fa-arrow-right"></i></a>
            </article>
            
            <article class="news-card">
                <div class="news-date">February 2026</div>
                <h3>New Playground Equipment</h3>
                <p>We've installed new, safe playground equipment to enhance your child's outdoor play experience.</p>
                <a href="/gallery" class="read-more">View Photos <i class="fas fa-arrow-right"></i></a>
            </article>
            
            <article class="news-card">
                <div class="news-date">January 2026</div>
                <h3>Parent-Teacher Meetings</h3>
                <p>Join us for our quarterly parent-teacher meetings to discuss your child's progress.</p>
                <a href="/contact" class="read-more">Contact Us <i class="fas fa-arrow-right"></i></a>
            </article>
        </div>
    </div>
</section>
