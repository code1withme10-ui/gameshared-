<?php
session_start();

require_once __DIR__ . '/partials/header.php';
require_once __DIR__ . '/partials/navbar.php';

// Correct gallery directory on filesystem
$galleryDir = __DIR__ . '/assets/images/gallery/';

// Get images
$images = glob($galleryDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

// Convert filesystem paths to URL paths
$baseUrl = '/assets/images/gallery/';
$imageUrls = array_map(function($img){
    return $baseUrl . basename($img);
}, $images);
?>

<div class="container" style="margin-top:60px; margin-bottom:60px;">

<h2 style="text-align:center; color:#0a1f44; margin-bottom:10px;">
Our Gallery
</h2>

<p style="text-align:center; color:#555; margin-bottom:30px;">
A glimpse into life at <strong>Mudzunga Community Creche</strong>
</p>

<?php if (!empty($imageUrls)): ?>

<div style="
display:grid;
grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
gap:20px;
">

<?php foreach ($imageUrls as $imgUrl): ?>

<div style="
background:#fff;
border:2px solid #f2c400;
border-radius:8px;
padding:8px;
box-shadow:0 4px 8px rgba(0,0,0,0.1);
transition:transform 0.3s ease;
">

<a href="<?php echo $imgUrl; ?>" target="_blank">

<img
src="<?php echo $imgUrl; ?>"
alt="Creche Activity"
style="
width:100%;
height:180px;
object-fit:cover;
border-radius:5px;
transition:transform 0.3s ease;
"
onmouseover="this.style.transform='scale(1.08)'"
onmouseout="this.style.transform='scale(1)'"
>

</a>

</div>

<?php endforeach; ?>

</div>

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

<?php require_once __DIR__ . '/partials/footer.php'; ?>

