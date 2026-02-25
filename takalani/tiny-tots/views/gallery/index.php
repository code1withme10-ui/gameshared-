<div class="content-wrapper">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-content">
            <h1>Gallery</h1>
            <p>Take a visual journey through life at Tiny Tots Creche</p>
        </div>
    </section>

    <div class="gallery-container">
        <?php foreach ($categories as $categoryKey => $category): ?>
            <section class="gallery-section">
                <div class="container">
                    <h2 class="section-title"><?= htmlspecialchars($category['title']) ?></h2>
                    <div class="gallery-grid">
                        <?php foreach ($category['images'] as $image): ?>
                            <div class="gallery-item">
                                <div class="gallery-image-container">
                                    <img src="<?= htmlspecialchars($image['src']) ?>" 
                                         alt="<?= htmlspecialchars($image['alt']) ?>" 
                                         class="gallery-image"
                                         loading="lazy">
                                    <div class="gallery-overlay">
                                        <div class="overlay-content">
                                            <i class="fas fa-search-plus"></i>
                                            <span><?= htmlspecialchars($image['alt']) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Join Our Family?</h2>
                <p>See your child thrive in our nurturing environment. Schedule a visit today!</p>
                <div class="cta-buttons">
                    <a href="/admission" class="btn btn-primary btn-large">
                        <i class="fas fa-graduation-cap"></i> Apply Now
                    </a>
                    <a href="/contact" class="btn btn-outline btn-large">
                        <i class="fas fa-calendar"></i> Schedule Visit
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox">
    <div class="lightbox-content">
        <span class="lightbox-close">&times;</span>
        <img class="lightbox-image" src="" alt="">
        <div class="lightbox-nav">
            <button class="lightbox-prev">&lt;</button>
            <button class="lightbox-next">&gt;</button>
        </div>
        <div class="lightbox-caption"></div>
    </div>
</div>

<style>
/* Gallery Page Styles */
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

.gallery-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.gallery-section {
    margin-bottom: 4rem;
}

.section-title {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 2rem;
    font-weight: 600;
    text-align: center;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 8px 25px var(--shadow-light);
    transition: all 0.3s ease;
    background: white;
}

.gallery-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px var(--shadow-medium);
}

.gallery-image-container {
    position: relative;
    width: 100%;
    height: 250px;
    overflow: hidden;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(135, 206, 235, 0.9), rgba(255, 215, 0, 0.9));
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    cursor: pointer;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.1);
}

.overlay-content {
    text-align: center;
    color: var(--text-dark);
}

.overlay-content i {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    display: block;
}

.overlay-content span {
    font-size: 1rem;
    font-weight: 500;
    padding: 0 1rem;
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

/* Lightbox Styles */
.lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    display: none;
    z-index: 10000;
    animation: fadeIn 0.3s ease-out;
}

.lightbox.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.lightbox-close {
    position: absolute;
    top: -40px;
    right: 0;
    color: white;
    font-size: 2.5rem;
    cursor: pointer;
    background: none;
    border: none;
    transition: color 0.3s ease;
}

.lightbox-close:hover {
    color: var(--secondary-color);
}

.lightbox-image {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 10px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 0 20px;
    pointer-events: none;
}

.lightbox-prev,
.lightbox-next {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: none;
    padding: 15px 20px;
    font-size: 1.5rem;
    cursor: pointer;
    border-radius: 50%;
    transition: all 0.3s ease;
    pointer-events: all;
}

.lightbox-prev:hover,
.lightbox-next:hover {
    background: rgba(255, 255, 255, 0.4);
    transform: scale(1.1);
}

.lightbox-caption {
    color: white;
    text-align: center;
    margin-top: 1rem;
    font-size: 1.1rem;
    max-width: 80%;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-hero h1 {
        font-size: 2rem;
    }
    
    .page-hero p {
        font-size: 1.1rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .gallery-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .gallery-image-container {
        height: 200px;
    }
    
    .overlay-content i {
        font-size: 2rem;
    }
    
    .overlay-content span {
        font-size: 0.9rem;
    }
    
    .lightbox-nav {
        padding: 0 10px;
    }
    
    .lightbox-prev,
    .lightbox-next {
        padding: 10px 15px;
        font-size: 1.2rem;
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

@media (max-width: 480px) {
    .gallery-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .gallery-image-container {
        height: 250px;
    }
    
    .lightbox-content {
        max-width: 95%;
    }
    
    .lightbox-image {
        max-height: 70vh;
    }
}
</style>

<script>
// Gallery functionality
document.addEventListener('DOMContentLoaded', function() {
    const galleryImages = document.querySelectorAll('.gallery-image');
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = lightbox.querySelector('.lightbox-image');
    const lightboxCaption = lightbox.querySelector('.lightbox-caption');
    const lightboxClose = lightbox.querySelector('.lightbox-close');
    const lightboxPrev = lightbox.querySelector('.lightbox-prev');
    const lightboxNext = lightbox.querySelector('.lightbox-next');
    
    let currentImageIndex = 0;
    const images = Array.from(galleryImages);
    
    // Open lightbox when clicking on gallery images
    galleryImages.forEach((img, index) => {
        img.addEventListener('click', function() {
            currentImageIndex = index;
            openLightbox(this.src, this.alt);
        });
    });
    
    function openLightbox(src, alt) {
        lightboxImage.src = src;
        lightboxCaption.textContent = alt;
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    function showNextImage() {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        const nextImage = images[currentImageIndex];
        openLightbox(nextImage.src, nextImage.alt);
    }
    
    function showPrevImage() {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        const prevImage = images[currentImageIndex];
        openLightbox(prevImage.src, prevImage.alt);
    }
    
    // Event listeners
    lightboxClose.addEventListener('click', closeLightbox);
    lightboxNext.addEventListener('click', showNextImage);
    lightboxPrev.addEventListener('click', showPrevImage);
    
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (lightbox.classList.contains('active')) {
            switch (e.key) {
                case 'Escape':
                    closeLightbox();
                    break;
                case 'ArrowRight':
                    showNextImage();
                    break;
                case 'ArrowLeft':
                    showPrevImage();
                    break;
            }
        }
    });
    
    // Touch/swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    lightbox.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    lightbox.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        if (touchEndX < touchStartX - 50) {
            showNextImage(); // Swipe left
        }
        if (touchEndX > touchStartX + 50) {
            showPrevImage(); // Swipe right
        }
    }
});
</script>
