<?php
session_start();

require_once __DIR__ . '/../app/views/partials/header.php';
require_once __DIR__ . '/../app/views/partials/navbar.php';

// Gallery directory
$galleryDir = __DIR__ . '/assets/images/gallery/';
$images = glob($galleryDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

// Convert to browser URL
$baseUrl = '/assets/images/gallery/';
$imageUrls = array_map(fn($img) => $baseUrl . basename($img), $images);
?>

<div class="container" style="margin-top:60px; margin-bottom:60px;">

<section style="text-align:center; background: linear-gradient(to bottom, #081a2e, #0c2c4d); padding: 80px 20px; margin-top: 40px; margin-bottom: 50px; border-radius:10px;">
    <h2 style="color:#ffd700; font-size:38px; letter-spacing:2px; font-weight:700; margin-bottom:10px; font-family:'Outfit', sans-serif;">Our Gallery</h2>
    <p style="color:#ffd700; font-size:18px; max-width:700px; margin:0 auto; opacity:0.95;">A beautiful glimpse into daily life at Mudzunga Community Creche</p>
</section>

<?php if (!empty($imageUrls)): ?>

<div class="gallery-grid">
    <?php foreach ($imageUrls as $imgUrl): ?>
        <div class="gallery-item" onclick="openLightbox('<?php echo htmlspecialchars($imgUrl); ?>')">
            <img src="<?php echo htmlspecialchars($imgUrl); ?>" loading="lazy" alt="Creche Activity">
            <div class="gallery-overlay">
                <div class="gallery-icon">🔍</div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" onclick="closeLightbox(event)">
    <span id="lightbox-close">&times;</span>
    <img id="lightbox-img" src="" alt="Full Screen Activity">
</div>

<script>
    function openLightbox(url) {
        document.getElementById('lightbox-img').src = url;
        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
    
    function closeLightbox(e) {
        if (e.target.id === 'lightbox' || e.target.id === 'lightbox-close') {
            document.getElementById('lightbox').style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore scrolling
        }
    }
</script>

<?php else: ?>

<div style="
text-align:center;
padding:40px;
background:#f9f9f9;
border:1px dashed #ccc;
border-radius:8px;
">

<p style="color:#777;">
No images yet.<br>
Add photos to <code>public/assets/images/gallery/</code>
</p>

</div>

<?php endif; ?>

</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>

