<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title"><?= htmlspecialchars($heroTitle) ?></h1>
        <p class="hero-subtitle"><?= htmlspecialchars($heroSubtitle) ?></p>
        <div class="hero-buttons">
            <a href="/admission" class="btn btn-primary btn-large">
                <i class="fas fa-graduation-cap"></i> Apply Now
            </a>
            <a href="/gallery" class="btn btn-secondary btn-large">
                <i class="fas fa-images"></i> View Gallery
            </a>
        </div>
    </div>
    <div class="hero-image">
        <div class="hero-placeholder">
            <i class="fas fa-baby"></i>
            <span>Happy Children Learning</span>
        </div>
    </div>
</section>

<!-- Welcome Section -->
<section class="welcome-section">
    <div class="container">
        <div class="welcome-content">
            <h2>Welcome to Tiny Tots Creche</h2>
            <p>At Tiny Tots Creche, we believe in providing a nurturing, safe, and stimulating environment where every child can thrive. Our dedicated team of educators is committed to fostering a love for learning through play-based education and individualized attention.</p>
            <p>We understand that the early years are crucial for development, and we strive to create experiences that support your child's cognitive, social, emotional, and physical growth.</p>
        </div>
    </div>
</section>

<!-- Key Highlights -->
<section class="highlights-section">
    <div class="container">
        <h2 class="section-title">Why Choose Tiny Tots?</h2>
        <div class="highlights-grid">
            <?php foreach ($highlights as $highlight): ?>
                <div class="highlight-card">
                    <div class="highlight-icon"><?= $highlight['icon'] ?></div>
                    <h3><?= htmlspecialchars($highlight['title']) ?></h3>
                    <p><?= htmlspecialchars($highlight['description']) ?></p>
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
                <p><?= htmlspecialchars($contactInfo['address']) ?></p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h3>Phone</h3>
                <p><?= htmlspecialchars($contactInfo['phone']) ?></p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email</h3>
                <p><?= htmlspecialchars($contactInfo['email']) ?></p>
            </div>
            
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Hours</h3>
                <p><?= htmlspecialchars($contactInfo['hours']) ?></p>
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
                <div class="news-date">March 2024</div>
                <h3>2024 Admissions Now Open</h3>
                <p>We are now accepting applications for the 2024 academic year. Limited spaces available!</p>
                <a href="/admission" class="read-more">Apply Now <i class="fas fa-arrow-right"></i></a>
            </article>
            
            <article class="news-card">
                <div class="news-date">February 2024</div>
                <h3>New Playground Equipment</h3>
                <p>We've installed new, safe playground equipment to enhance your child's outdoor play experience.</p>
                <a href="/gallery" class="read-more">View Photos <i class="fas fa-arrow-right"></i></a>
            </article>
            
            <article class="news-card">
                <div class="news-date">January 2024</div>
                <h3>Parent-Teacher Meetings</h3>
                <p>Join us for our quarterly parent-teacher meetings to discuss your child's progress.</p>
                <a href="/contact" class="read-more">Contact Us <i class="fas fa-arrow-right"></i></a>
            </article>
        </div>
    </div>
</section>
