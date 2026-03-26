<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery | Govern Psy Educational Center</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        /* Smooth Fade Effect for the Slideshow */
        .fade {
            animation-name: fade;
            animation-duration: 1.5s;
        }
        @keyframes fade {
            from {opacity: .4} 
            to {opacity: 1}
        }
    </style>
</head>
<body class="no-scroll-page">

    <?php include 'includes/navbar.php'; ?>

    <header class="page-banner" style="background: var(--navy); padding: 30px 20px; border-bottom: 5px solid var(--red); text-align: center; color: white;">
        <h1 style="font-family: 'Montserrat', sans-serif; font-weight: 900; letter-spacing: 1px; font-size: 2rem; margin-bottom: 5px;">
            OUR PHOTO GALLERY
        </h1>
        <p style="font-size: 0.9rem; opacity: 0.9; text-transform: uppercase;">A glimpse into life at our Somerset center</p>
    </header>

    <main style="padding: 20px; flex-direction: column;">
        
        <section class="gallery-full-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; width: 100%; max-width: 1200px; margin: 0 auto;">
            
            <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; height: 250px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); background: #eee;">
                <img src="images/classroom_1.jpeg" class="classroom-slide fade" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                <img src="images/classroom_2.jpeg" class="classroom-slide fade" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                <img src="images/classroom_3.jpeg" class="classroom-slide fade" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                <img src="images/classroom_4.jpeg" class="classroom-slide fade" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                <img src="images/classroom_5.jpeg" class="classroom-slide fade" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                
                <div class="img-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,51,102,0.8); color: white; padding: 10px; text-align: center; font-size: 0.85rem; font-weight: bold; z-index: 5;">
                    <span>Creative Learning</span>
                </div>
            </div>

            <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; height: 250px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <img src="images/child2.jpg" alt="Play Area" style="width: 100%; height: 100%; object-fit: cover;">
                <div class="img-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(190,30,45,0.8); color: white; padding: 10px; text-align: center; font-size: 0.85rem; font-weight: bold;">
                    <span>Safe Play Areas</span>
                </div>
            </div>

            <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; height: 250px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <img src="images/facility1.jpg" alt="Secure Entrance" style="width: 100%; height: 100%; object-fit: cover;">
                <div class="img-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,51,102,0.8); color: white; padding: 10px; text-align: center; font-size: 0.85rem; font-weight: bold;">
                    <span>Secure Campus</span>
                </div>
            </div>

            <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; height: 250px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <img src="images/child3.jpg" alt="Group Activity" style="width: 100%; height: 100%; object-fit: cover;">
                <div class="img-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(190,30,45,0.8); color: white; padding: 10px; text-align: center; font-size: 0.85rem; font-weight: bold;">
                    <span>Team Building</span>
                </div>
            </div>

        </section>

        <div style="text-align: center; margin-top: 30px;">
            <a href="about.php" class="btn-secondary" style="text-decoration: none; padding: 10px 25px; border-radius: 5px; font-weight: bold; font-size: 0.9rem;">
                <i class="fas fa-arrow-left"></i> Back to About Us
            </a>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("classroom-slide");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
            }
            slideIndex++;
            if (slideIndex > slides.length) {slideIndex = 1}    
            slides[slideIndex-1].style.display = "block";  
            setTimeout(showSlides, 3000); // Changes image every 3 seconds
        }
    </script>

</body>
</html>