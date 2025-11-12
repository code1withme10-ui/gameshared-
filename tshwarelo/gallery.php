<?php
// GALLERY PAGE: gallery.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gallery - Humulani Pre School</title>
    
    <style>
        /* (CSS from index.php is included here for brevity, assume consistency) */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes rainbowShine {
            0% { border-color: #ff0000; } 50% { border-color: #0000ff; } 100% { border-color: #ff0000; }
        }
        /* --- Base & Layout --- */
        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background-color: #fcfcfc; text-align: left; }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; }
        h1 { color: #008000; font-size: 2.8em; margin-top: 30px; border-bottom: 3px solid #ff9900; padding-bottom: 10px; margin-bottom: 40px; }
        /* --- Navigation Styles --- */
        .site-header { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; background-color: #fff; border-bottom: 3px solid transparent; animation: rainbowShine 8s infinite alternate; }
        .nav-link { text-decoration: none; font-weight: bold; margin: 0 10px; }
        .nav-link:nth-child(1) { color: #ff0000; } .nav-link:nth-child(2) { color: #ff9900; } .nav-link:nth-child(3) { color: #008000; } 
        .nav-link:nth-child(4) { color: #0000ff; } .nav-link:nth-child(5) { color: #4b0082; } .nav-link:nth-child(6) { color: #ee82ee; } 

        /* --- Carousel Styles --- */
        .carousel-container { max-width: 900px; position: relative; margin: auto; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.2); overflow: hidden; }
        .slide { display: none; width: 100%; height: 500px; }
        .slide img { width: 100%; height: 100%; object-fit: cover; animation: fadeIn 1.5s; }
        .dot-container { text-align: center; padding: 10px 0; background-color: #fff; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; }
        .dot { height: 15px; width: 15px; margin: 0 2px; background-color: #bbb; border-radius: 50%; display: inline-block; transition: background-color 0.6s ease; cursor: pointer; }
        .active-dot { background-color: #008000; box-shadow: 0 0 5px rgba(0, 128, 0, 0.5); }
        
        /* --- Gallery Grid Styles --- */
        .gallery-sub-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 50px; margin-bottom: 50px; }
        .gallery-item { overflow: hidden; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); position: relative; transition: transform 0.3s; }
        .gallery-item:hover { transform: scale(1.05); }
        .gallery-item img { width: 100%; height: 200px; object-fit: cover; display: block; }

        footer { margin-top: 40px; padding: 20px 0; border-top: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>
    
    <header>
        <div class="container site-header">
            <div style="font-size: 1.5em; font-weight: bold; color: #333;">Humulani Pre School</div>
            <nav>
                <a href="index.php" class="nav-link">Home</a>
                <a href="about.php" class="nav-link">About Us</a>
                <a href="gallery.php" class="nav-link">Gallery</a>
               <a href="admission.php" class="nav-link">Admission</a>
                <a href="contact.php" class="nav-link">Contact</a>
                <a href="login.php" class="nav-link">Login</a>
            </nav>
        </div>
    </header>

    <div class="container">
        
        <h1>Photo Gallery of Humulani</h1>
        <p style="font-size: 1.1em; color: #555;">Discover our vibrant classrooms, safe playgrounds, and engaging learning environment through our photo album.</p>

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
        
        
        <h2 style="color: #4b0082; margin-top: 40px; border-bottom: 1px solid #eee;">More Moments</h2>
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
    
    <script>
        let slideIndex = 1;
        let autoSlideTimeout;
        
        function currentSlide(n) {
            // Stops auto-play when user clicks a dot
            clearTimeout(autoSlideTimeout); 
            showSlides(slideIndex = n);
            // Re-start the auto-play timer after 5 seconds of manual click
            autoSlideTimeout = setTimeout(autoShowSlides, 5000);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("slide");
            let dots = document.getElementsByClassName("dot");
            
            // Loop controls
            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            
            // Hide all and reset dots
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
                dots[i].className = dots[i].className.replace(" active-dot", "");
            }
            
            // Display current slide and activate dot
            slides[slideIndex-1].style.display = "block";  
            dots[slideIndex-1].className += " active-dot";
        }

        function autoShowSlides() {
            let i;
            let slides = document.getElementsByClassName("slide");
            let dots = document.getElementsByClassName("dot");
            
            // Hide current slide (before incrementing index)
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
                dots[i].className = dots[i].className.replace(" active-dot", "");
            }
            
            slideIndex++;
            if (slideIndex > slides.length) {slideIndex = 1} // Loop back to start
            
            // Show new slide
            slides[slideIndex-1].style.display = "block";
            dots[slideIndex-1].className += " active-dot";
            
            // Set the timeout for the next slide (5 seconds)
            autoSlideTimeout = setTimeout(autoShowSlides, 5000); 
        }
        
        // Initialization
        showSlides(slideIndex); // Show the first slide (1)
        autoShowSlides(); // Start the auto-play loop
    </script>
</body>
</html>