<?php
// No login required for this page
?>

<!-- Link to CSS in public folder -->
<link rel="stylesheet" href="css/style.css">

<?php 
// Include menu bar from the new structure
include __DIR__ . '/../includes/menu-bar.php'; 
?>

<div class="container">
    <h2>Gallery</h2>
    <p>Explore photos from Sunshine Preschool activities and events.</p>

    <div class="gallery">
        <img src="images/photo1.jpg" alt="Activity 1" style="width:30%; margin:1%">
        <img src="images/photo2.jpg" alt="Activity 2" style="width:30%; margin:1%">
        <img src="images/photo3.jpg" alt="Activity 3" style="width:30%; margin:1%">
        <!-- Add more images as needed -->
    </div>
</div>


