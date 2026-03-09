<?php
// GALLERY PAGE: gallery.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gallery - Humulani Pre School</title>
   <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <div class="page-container">
        <h1>Photo Gallery of Humulani</h1>
        <p>Discover our vibrant classrooms, safe playgrounds, and engaging learning environment through our photo album.</p>

        <!-- Carousel -->
        <div class="carousel-container">
            <div class="slide">
                <img src="images/finger-painting.jpeg" alt="Children painting at art station">
            </div>
            <div class="slide">
                <img src="images/lunchtime.jpeg" alt="Nutritious meal time">
            </div>
            <div class="slide">
                <img src="images/blocks-area.jpeg" alt="Educational blocks and toys area">
            </div>
            <div class="slide">
                <img src="images/graduation-day.jpeg" alt="Graduation day celebration">
            </div>

            <div class="dot-container">
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
                <span class="dot" onclick="currentSlide(4)"></span>
            </div>
        </div>

        <h2>More Moments</h2>
        <div class="gallery-sub-grid">
            <div class="gallery-item">
                <img src="images/finger-painting.jpeg" alt="Finger painting art activity">
            </div>
            <div class="gallery-item">
                <img src="images/lunchtime.jpeg" alt="Nutritious lunch time at school">
            </div>
            <div class="gallery-item">
                <img src="images/blocks-area.jpeg" alt="Educational blocks and toys area">
            </div>
            <div class="gallery-item">
                <img src="images/graduation-day.jpeg" alt="Graduation day celebration">
            </div>
        </div>

        <footer>
            <p>&copy; 2026 Humulani Pre School</p>
        </footer>
    </div>

    <!-- Carousel Script -->
    <script>
        let slideIndex = 1;
        let autoSlideTimeout;

        function currentSlide(n) {
            clearTimeout(autoSlideTimeout);
            showSlides(slideIndex = n);
            autoSlideTimeout = setTimeout(autoShowSlides, 5000);
        }

        function showSlides(n) {
            let slides = document.getElementsByClassName("slide");
            let dots = document.getElementsByClassName("dot");
            if (n > slides.length) {slideIndex = 1;}
            if (n < 1) {slideIndex = slides.length;}
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
                dots[i].className = dots[i].className.replace(" active-dot", "");
            }
            slides[slideIndex-1].style.display = "block";
            dots[slideIndex-1].className += " active-dot";
        }

        function autoShowSlides() {
            let slides = document.getElementsByClassName("slide");
            let dots = document.getElementsByClassName("dot");
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
                dots[i].className = dots[i].className.replace(" active-dot", "");
            }
            slideIndex++;
            if (slideIndex > slides.length) {slideIndex = 1;}
            slides[slideIndex-1].style.display = "block";
            dots[slideIndex-1].className += " active-dot";
            autoSlideTimeout = setTimeout(autoShowSlides, 5000);
        }

        showSlides(slideIndex);
        autoShowSlides();
    </script>

</body>
</html>
