<?php
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

<main class="home-container">
    <section class="gallery-hero">
        <h1>🖼️ Tiny Tots Gallery</h1>
        <p>A glimpse into the wonderful world of learning and fun at our creche</p>
    </section>

    <section class="gallery-section">
        <h2>📚 Classrooms</h2>
        <div class="gallery-grid">
            <div class="gallery-item" onclick="openLightbox(0)">
                <img src="https://images.pexels.com/photos/256395/pexels-photo-256395.jpeg" alt="Bright and colorful classroom">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Bright Learning Space</div>
                    <div class="gallery-icon">👁️</div>
                </div>
            </div>
            <div class="gallery-item" onclick="openLightbox(1)">
                <img src="https://images.pexels.com/photos/3184287/pexels-photo-3184287.jpeg" alt="Reading corner">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Cozy Reading Corner</div>
                    <div class="gallery-icon">📖</div>
                </div>
            </div>
            <div class="gallery-item" onclick="openLightbox(2)">
                <img src="https://images.pexels.com/photos/8422203/pexels-photo-8422203.jpeg" alt="Story time session">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Interactive Story Time</div>
                    <div class="gallery-icon">🎭</div>
                </div>
            </div>
        </div>
    </section>

    <section class="gallery-section">
        <h2>🎨 Outdoor Play Area</h2>
        <div class="gallery-grid">
            <div class="gallery-item" onclick="openLightbox(3)">
                <img src="https://images.pexels.com/photos/2963011/pexels-photo-2963011.jpeg" alt="Children playing outdoors">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Active Outdoor Play</div>
                    <div class="gallery-icon">🏃‍♂️</div>
                </div>
            </div>
            <div class="gallery-item" onclick="openLightbox(4)">
                <img src="https://images.pexels.com/photos/3184292/pexels-photo-3184292.jpeg" alt="Playground equipment">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Safe Play Equipment</div>
                    <div class="gallery-icon">🎪</div>
                </div>
            </div>
            <div class="gallery-item" onclick="openLightbox(5)">
                <img src="https://images.pexels.com/photos/3662835/pexels-photo-3662835.jpeg" alt="Sports activities">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Fun Sports Activities</div>
                    <div class="gallery-icon">⚽</div>
                </div>
            </div>
        </div>
    </section>

    <section class="gallery-section">
        <h2>🎯 Learning Activities</h2>
        <div class="gallery-grid">
            <div class="gallery-item" onclick="openLightbox(6)">
                <img src="https://images.pexels.com/photos/8422208/pexels-photo-8422208.jpeg" alt="Art and craft activities">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Creative Art & Craft</div>
                    <div class="gallery-icon">🎨</div>
                </div>
            </div>
            <div class="gallery-item" onclick="openLightbox(7)">
                <img src="https://images.pexels.com/photos/4144094/pexels-photo-4144094.jpeg" alt="Group learning activities">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Collaborative Learning</div>
                    <div class="gallery-icon">🤝</div>
                </div>
            </div>
            <div class="gallery-item" onclick="openLightbox(8)">
                <img src="https://images.pexels.com/photos/4144222/pexels-photo-4144222.jpeg" alt="Painting activities">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Expressive Painting</div>
                    <div class="gallery-icon">🖌️</div>
                </div>
            </div>
        </div>
    </section>

    <section class="gallery-section">
        <h2>🎉 Events & Celebrations</h2>
        <div class="gallery-grid">
            <div class="gallery-item" onclick="openLightbox(9)">
                <img src="https://images.pexels.com/photos/1684072/pexels-photo-1684072.jpeg" alt="Birthday celebration">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Birthday Celebrations</div>
                    <div class="gallery-icon">🎂</div>
                </div>
            </div>
            <div class="gallery-item" onclick="openLightbox(10)">
                <img src="https://images.pexels.com/photos/1395907/pexels-photo-1395907.jpeg" alt="Cultural day event">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Cultural Day Events</div>
                    <div class="gallery-icon">🌍</div>
                </div>
            </div>
            <div class="gallery-item" onclick="openLightbox(11)">
                <img src="https://images.pexels.com/photos/1449791/pexels-photo-1449791.jpeg" alt="Fun fair activities">
                <div class="gallery-overlay">
                    <div class="gallery-caption">Annual Fun Fair</div>
                    <div class="gallery-icon">🎪</div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox" onclick="closeLightbox()">
    <span class="lightbox-close">&times;</span>
    <div class="lightbox-content">
        <img class="lightbox-image" id="lightboxImage" src="" alt="">
        <div class="lightbox-caption" id="lightboxCaption"></div>
        <div class="lightbox-nav">
            <button class="lightbox-btn prev-btn" onclick="navigateLightbox(-1)">‹</button>
            <button class="lightbox-btn next-btn" onclick="navigateLightbox(1)">›</button>
        </div>
    </div>
</div>

<style>
/* Gallery Page Styles */
.gallery-hero {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, var(--light-blue), var(--primary-color));
    border-radius: 20px;
    margin-bottom: 3rem;
    box-shadow: 0 8px 30px var(--shadow-light);
}

.gallery-hero h1 {
    color: var(--secondary-color);
    font-size: 2.8rem;
    margin: 0 0 1rem 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.gallery-hero p {
    color: var(--text-dark);
    font-size: 1.3rem;
    margin: 0;
    font-weight: 500;
}

.gallery-section {
    margin: 4rem 0;
}

.gallery-section h2 {
    color: var(--secondary-color);
    text-align: center;
    font-size: 2.2rem;
    margin-bottom: 2.5rem;
    font-weight: 600;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.gallery-item {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 25px var(--shadow-light);
    transition: all 0.3s ease;
    cursor: pointer;
    background: white;
}

.gallery-item:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 40px var(--shadow-medium);
}

.gallery-item img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.gallery-item:hover img {
    transform: scale(1.1);
}

.gallery-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
    padding: 1.5rem;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-overlay {
    transform: translateY(0);
}

.gallery-caption {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.gallery-icon {
    font-size: 1.5rem;
}

/* Lightbox Styles */
.lightbox {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    animation: fadeIn 0.3s ease;
}

.lightbox-content {
    position: relative;
    margin: auto;
    padding: 2rem;
    width: 90%;
    max-width: 900px;
    top: 50%;
    transform: translateY(-50%);
}

.lightbox-image {
    width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
}

.lightbox-caption {
    color: white;
    text-align: center;
    margin-top: 1.5rem;
    font-size: 1.1rem;
    line-height: 1.4;
}

.lightbox-close {
    position: absolute;
    top: 1rem;
    right: 2rem;
    color: white;
    font-size: 3rem;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
    z-index: 10001;
}

.lightbox-close:hover {
    color: var(--secondary-color);
}

.lightbox-nav {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 1rem;
}

.lightbox-btn {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-btn:hover {
    background: var(--secondary-color);
    border-color: var(--secondary-color);
    color: var(--text-dark);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .gallery-item img {
        height: 200px;
    }
    
    .lightbox-content {
        width: 95%;
        padding: 1rem;
    }
    
    .lightbox-close {
        top: 0.5rem;
        right: 1rem;
        font-size: 2rem;
    }
    
    .lightbox-nav {
        bottom: 1rem;
    }
    
    .lightbox-btn {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
}
</style>

<script>
const galleryImages = [
    {
        src: "https://images.pexels.com/photos/256395/pexels-photo-256395.jpeg",
        caption: "Bright Learning Space - Our colorful classrooms are designed to stimulate young minds and create an engaging learning environment."
    },
    {
        src: "https://images.pexels.com/photos/3184287/pexels-photo-3184287.jpeg",
        caption: "Cozy Reading Corner - A quiet space where children can explore books and develop their love for reading."
    },
    {
        src: "https://images.pexels.com/photos/8422203/pexels-photo-8422203.jpeg",
        caption: "Interactive Story Time - Teachers bring stories to life, fostering imagination and language development."
    },
    {
        src: "https://images.pexels.com/photos/2963011/pexels-photo-2963011.jpeg",
        caption: "Active Outdoor Play - Children develop gross motor skills and social interaction through supervised outdoor activities."
    },
    {
        src: "https://images.pexels.com/photos/3184292/pexels-photo-3184292.jpeg",
        caption: "Safe Play Equipment - Age-appropriate playground equipment designed for fun and safety."
    },
    {
        src: "https://images.pexels.com/photos/3662835/pexels-photo-3662835.jpeg",
        caption: "Fun Sports Activities - Regular physical activities to promote health and teamwork."
    },
    {
        src: "https://images.pexels.com/photos/8422208/pexels-photo-8422208.jpeg",
        caption: "Creative Art & Craft - Children express their creativity through various art mediums and craft activities."
    },
    {
        src: "https://images.pexels.com/photos/4144094/pexels-photo-4144094.jpeg",
        caption: "Collaborative Learning - Group activities that teach cooperation and social skills."
    },
    {
        src: "https://images.pexels.com/photos/4144222/pexels-photo-4144222.jpeg",
        caption: "Expressive Painting - Art activities that help children develop fine motor skills and self-expression."
    },
    {
        src: "https://images.pexels.com/photos/1684072/pexels-photo-1684072.jpeg",
        caption: "Birthday Celebrations - We celebrate each child's special day with joy and enthusiasm."
    },
    {
        src: "https://images.pexels.com/photos/1395907/pexels-photo-1395907.jpeg",
        caption: "Cultural Day Events - Celebrating diversity and teaching children about different cultures."
    },
    {
        src: "https://images.pexels.com/photos/1449791/pexels-photo-1449791.jpeg",
        caption: "Annual Fun Fair - A day of games, activities, and family fun at our creche."
    }
];

let currentImageIndex = 0;

function openLightbox(index) {
    currentImageIndex = index;
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCaption = document.getElementById('lightboxCaption');
    
    lightboxImage.src = galleryImages[index].src;
    lightboxCaption.textContent = galleryImages[index].caption;
    lightbox.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function navigateLightbox(direction) {
    currentImageIndex += direction;
    
    if (currentImageIndex < 0) {
        currentImageIndex = galleryImages.length - 1;
    } else if (currentImageIndex >= galleryImages.length) {
        currentImageIndex = 0;
    }
    
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCaption = document.getElementById('lightboxCaption');
    
    lightboxImage.src = galleryImages[currentImageIndex].src;
    lightboxCaption.textContent = galleryImages[currentImageIndex].caption;
}

// Keyboard navigation
document.addEventListener('keydown', function(event) {
    if (document.getElementById('lightbox').style.display === 'block') {
        if (event.key === 'Escape') {
            closeLightbox();
        } else if (event.key === 'ArrowLeft') {
            navigateLightbox(-1);
        } else if (event.key === 'ArrowRight') {
            navigateLightbox(1);
        }
    }
});

// Prevent lightbox from closing when clicking on image
document.getElementById('lightboxImage').addEventListener('click', function(event) {
    event.stopPropagation();
});
</script>

<?php require_once 'includes/footer.php'; ?>
