<div class="content-wrapper">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-content">
            <h1>About Tiny Tots Creche</h1>
            <p>Discover our story, vision, and commitment to nurturing young minds</p>
        </div>
    </section>

    <!-- Our Story -->
    <section class="story-section">
        <div class="container">
            <div class="story-content">
                <div class="story-text">
                    <h2>Our Story</h2>
                    <p><?= htmlspecialchars($story) ?></p>
                    <p>Since our establishment, we have been dedicated to creating a warm, loving environment where children feel safe, valued, and excited to learn. Our team of experienced educators works tirelessly to ensure each child receives the individual attention they deserve.</p>
                    <p>We believe that every child is unique, with their own strengths, interests, and learning style. That's why we've developed a comprehensive curriculum that caters to diverse learning needs while maintaining high educational standards.</p>
                </div>
                <div class="story-image">
                    <div class="image-placeholder">
                        <i class="fas fa-school"></i>
                        <span>Our Beautiful Creche</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="vision-mission-section">
        <div class="container">
            <div class="vm-grid">
                <div class="vm-card vision">
                    <div class="vm-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p><?= htmlspecialchars($vision) ?></p>
                </div>
                
                <div class="vm-card mission">
                    <div class="vm-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p><?= htmlspecialchars($mission) ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="values-section">
        <div class="container">
            <h2 class="section-title">Our Core Values</h2>
            <div class="values-grid">
                <?php foreach ($values as $value): ?>
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3><?= htmlspecialchars($value['title']) ?></h3>
                        <p><?= htmlspecialchars($value['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Leadership -->
    <section class="leadership-section">
        <div class="container">
            <h2 class="section-title">Our Leadership</h2>
            <div class="leadership-grid">
                <?php foreach ($leadership as $leader): ?>
                    <div class="leader-card">
                        <div class="leader-image">
                            <div class="image-placeholder">
                                <i class="fas fa-user-tie"></i>
                                <span><?= htmlspecialchars($leader['name']) ?></span>
                            </div>
                        </div>
                        <div class="leader-info">
                            <h3><?= htmlspecialchars($leader['name']) ?></h3>
                            <p class="leader-position"><?= htmlspecialchars($leader['position']) ?></p>
                            <p class="leader-bio"><?= htmlspecialchars($leader['bio']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Facilities -->
    <section class="facilities-section">
        <div class="container">
            <h2 class="section-title">Our Facilities</h2>
            <div class="facilities-grid">
                <?php foreach ($facilities as $facility): ?>
                    <div class="facility-card">
                        <div class="facility-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3><?= htmlspecialchars($facility['name']) ?></h3>
                        <p><?= htmlspecialchars($facility['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Join Our Tiny Tots Family</h2>
                <p>Experience the difference that quality early childhood education can make in your child's life.</p>
                <div class="cta-buttons">
                    <a href="/admission" class="btn btn-primary btn-large">
                        <i class="fas fa-graduation-cap"></i> Apply Now
                    </a>
                    <a href="/contact" class="btn btn-outline btn-large">
                        <i class="fas fa-phone"></i> Visit Us
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* About Page Styles */
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

.story-section {
    padding: 4rem 0;
    background: white;
    margin-bottom: 3rem;
}

.story-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
    align-items: center;
}

.story-text h2 {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 2rem;
    font-weight: 600;
}

.story-text p {
    font-size: 1.1rem;
    color: var(--text-light);
    line-height: 1.8;
    margin-bottom: 1.5rem;
}

.story-image .image-placeholder {
    background: linear-gradient(135deg, var(--light-blue), var(--primary-color));
    color: var(--text-dark);
    padding: 3rem;
    border-radius: 20px;
    text-align: center;
    font-size: 1.2rem;
    box-shadow: 0 8px 30px var(--shadow-medium);
}

.story-image .image-placeholder i {
    font-size: 4rem;
    margin-bottom: 1rem;
    display: block;
}

.vision-mission-section {
    padding: 4rem 0;
    margin-bottom: 3rem;
}

.vm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
}

.vm-card {
    background: white;
    padding: 3rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 8px 25px var(--shadow-light);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.vm-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px var(--shadow-medium);
}

.vm-card.vision:hover {
    border-color: var(--primary-color);
}

.vm-card.mission:hover {
    border-color: var(--secondary-color);
}

.vm-icon {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 2rem;
}

.vm-card h3 {
    font-size: 1.8rem;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.vm-card p {
    font-size: 1.1rem;
    color: var(--text-light);
    line-height: 1.8;
}

.values-section {
    padding: 4rem 0;
    background: white;
    margin-bottom: 3rem;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.value-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 8px 25px var(--shadow-light);
    transition: all 0.3s ease;
    border: 2px solid var(--light-blue);
}

.value-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px var(--shadow-medium);
    border-color: var(--secondary-color);
}

.value-icon {
    background: var(--secondary-color);
    color: var(--text-dark);
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 1.8rem;
}

.value-card h3 {
    font-size: 1.5rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 600;
}

.value-card p {
    color: var(--text-light);
    line-height: 1.6;
}

.leadership-section {
    padding: 4rem 0;
    margin-bottom: 3rem;
}

.leadership-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 3rem;
}

.leader-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px var(--shadow-light);
    transition: all 0.3s ease;
}

.leader-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px var(--shadow-medium);
}

.leader-image .image-placeholder {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--text-dark);
    padding: 3rem;
    text-align: center;
    font-size: 1.1rem;
}

.leader-image .image-placeholder i {
    font-size: 3rem;
    margin-bottom: 1rem;
    display: block;
}

.leader-info {
    padding: 2rem;
}

.leader-info h3 {
    font-size: 1.5rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.leader-position {
    color: var(--secondary-color);
    font-weight: 500;
    margin-bottom: 1rem;
}

.leader-bio {
    color: var(--text-light);
    line-height: 1.6;
}

.facilities-section {
    padding: 4rem 0;
    background: white;
    margin-bottom: 3rem;
}

.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.facility-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 8px 25px var(--shadow-light);
    transition: all 0.3s ease;
    border: 2px solid var(--light-blue);
}

.facility-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px var(--shadow-medium);
    border-color: var(--primary-color);
}

.facility-icon {
    background: var(--primary-color);
    color: var(--text-dark);
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 1.8rem;
}

.facility-card h3 {
    font-size: 1.5rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 600;
}

.facility-card p {
    color: var(--text-light);
    line-height: 1.6;
}

.cta-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 20px;
    text-align: center;
    margin-bottom: 3rem;
}

.cta-content h2 {
    font-size: 2.5rem;
    color: var(--text-dark);
    margin-bottom: 1rem;
    font-weight: 600;
}

.cta-content p {
    font-size: 1.2rem;
    color: var(--text-dark);
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .page-hero h1 {
        font-size: 2rem;
    }
    
    .page-hero p {
        font-size: 1.1rem;
    }
    
    .story-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .vm-grid {
        grid-template-columns: 1fr;
    }
    
    .leadership-grid {
        grid-template-columns: 1fr;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-buttons .btn {
        width: 100%;
        max-width: 300px;
    }
}
</style>
