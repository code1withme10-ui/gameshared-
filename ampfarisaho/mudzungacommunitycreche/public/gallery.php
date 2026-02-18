<?php
require_once __DIR__ . '/partials/header.php';
require_once __DIR__ . '/partials/navbar.php';
session_start();

// Set the gallery folder
$galleryFolder = __DIR__ . '/assets/images/gallery/';

// Get all image files in the folder
$images = glob($galleryFolder . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
?>

<div class="container" style="margin-top:50px;">
    <h2>Mudzunga Community Creche Gallery</h2>

    <?php if (!empty($images)): ?>
        <div style="display:flex; flex-wrap:wrap; gap:15px;">
            <?php foreach ($images as $img): ?>
                <div style="flex:0 0 200px;">
                    <a href="<?php echo str_replace(__DIR__ . '/','/',$img); ?>" target="_blank">
                        <img src="<?php echo str_replace(__DIR__ . '/','/',$img); ?>" 
                             alt="Gallery Image" 
                             style="width:100%; height:auto; border:1px solid #ccc; padding:3px;">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No images found. Please add images to <code>assets/images/gallery/</code>.</p>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/partials/footer.php';
?>

