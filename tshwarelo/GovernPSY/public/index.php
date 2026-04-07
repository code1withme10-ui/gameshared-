<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Govern Psy Educational Center | Home</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
</head>

<body>

    <?php include 'includes/navbar.php'; ?>

    <main>
        
        <header class="hero">
            <div class="hero-content">
                <h1>
                    BRIGHT MINDS.<br>
                    <span class="highlight">STRONG ROOTS.</span>
                </h1>
                <p>Connecting academic learning with practical skills for real life.</p>
                <div class="hero-btns">
                    <a href="admissions.php" class="btn-primary">Apply Now</a>
                    <a href="contact.php" class="btn-secondary">Contact Us</a>
                </div>
            </div>
        </header>

        <section class="info-grid">
            
            <div class="info-card card-navy">
                <i class="fas fa-clock"></i>
                <h3>Operating Hours</h3>
                <p>Mon - Thu: 07:45 - 14:00<br>Friday: 07:45 - 13:30</p>
            </div>

            <div class="info-card card-red">
                <i class="fas fa-graduation-cap"></i>
                <h3>Age Groups</h3>
                <p>Accepting learners from 2 years old up to Grade 7.</p>
            </div>

            <div class="info-card card-navy">
                <i class="fas fa-star"></i>
                <h3>Our Values</h3>
                <p>Empowerment · Excellence · Community</p>
            </div>

        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        const menuBtn = document.getElementById('mobile-menu');
        const navList = document.getElementById('nav-list');

        if(menuBtn) {
            menuBtn.addEventListener('click', () => {
                navList.classList.toggle('active');
            });
        }
    </script>

</body>
</html>