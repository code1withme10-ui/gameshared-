<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title"><?= htmlspecialchars($heroTitle ?? 'Tiny Tots Creche') ?></h1>
        <p class="hero-subtitle"><?= htmlspecialchars($heroSubtitle ?? 'Where little minds grow, explore, and shine!') ?></p>
        <p class="hero-tagline"><?= htmlspecialchars($heroTagline ?? 'From 3 months to Grade RR • Aftercare also available') ?></p>
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
            <h2>Welcome to <?= htmlspecialchars($heroTitle ?? 'Tiny Tots Creche') ?>!</h2>
            <p><?= htmlspecialchars($welcomeMessage ?? 'We provide a safe, nurturing, and stimulating environment where children are encouraged to discover their talents, build confidence, and develop a love for learning.') ?></p>
            <p><?= htmlspecialchars($welcomeDetails ?? 'Our dedicated caregivers are passionate about supporting every child\'s emotional, social, physical, and intellectual development through structured activities, creative play, and positive guidance.') ?></p>
        </div>
    </div>
</section>

<!-- Key Highlights -->
<section class="highlights-section">
    <div class="container">
        <h2 class="section-title">Why Choose <?= htmlspecialchars($heroTitle ?? 'Tiny Tots') ?>?</h2>
        <div class="highlights-grid">
            <?php 
            $highlights = $highlights ?? [
                ['title' => '🏠 Home-Away-From-Home', 'description' => 'Warm, family-like atmosphere where children feel loved and secure'],
                ['title' => '👤 Individual Attention', 'description' => 'We understand each child\'s unique personality and needs'],
                ['title' => '🤝 Parent Partnerships', 'description' => 'Open communication and active parent involvement'],
                ['title' => '🌟 Holistic Development', 'description' => 'Nurturing emotional intelligence, social skills, and creativity']
            ];
            foreach ($highlights as $highlight): 
            ?>
                <div class="highlight-card">
                    <div class="highlight-icon"><?= $highlight['title'] ?></div>
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
