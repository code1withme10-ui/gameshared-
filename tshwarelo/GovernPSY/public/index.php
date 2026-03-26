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

    <main style="display: flex; flex-direction: column; align-items: center;">
        
        <header class="hero" style="width: 100%; height: 60vh; min-height: 400px; background-color: var(--navy); color: white; display: flex; align-items: center; justify-content: center; text-align: center;">
            <div class="hero-content">
                <h1 style="font-family: 'Montserrat', sans-serif; line-height: 1.1; margin: 0;">
                    BRIGHT MINDS.<br>
                    <span style="color: var(--gold);">STRONG ROOTS.</span>
                </h1>
                <p style="margin-top: 20px; font-size: 1.2rem; font-weight: 400;">Connecting academic learning with practical skills for real life.</p>
                <div class="hero-btns" style="margin-top: 30px;">
                    <a href="admissions.php" class="btn-primary" style="text-decoration: none; padding: 15px 40px; border-radius: 30px; background: var(--red); color: white; font-weight: bold; display: inline-block;">Apply Now</a>
                    <a href="contact.php" class="btn-secondary" style="text-decoration: none; padding: 13px 40px; border-radius: 30px; margin-left: 15px; border: 2px solid white; color: white; font-weight: bold; display: inline-block;">Contact Us</a>
                </div>
            </div>
        </header>

        <section class="info-grid" style="width: 100%; max-width: 1200px; padding: 50px 20px; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            
            <div class="info-card" style="padding: 30px; border-top: 5px solid var(--navy); background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; border-radius: 0 0 10px 10px;">
                <i class="fas fa-clock" style="font-size: 2.5rem; color: var(--red); margin-bottom: 20px;"></i>
                <h3 style="color: var(--navy); font-size: 1.3rem; margin-bottom: 15px;">Operating Hours</h3>
                <p style="font-size: 1rem; color: #555; line-height: 1.6;">Mon - Thu: 07:45 - 14:00<br>Friday: 07:45 - 13:30</p>
            </div>

            <div class="info-card" style="padding: 30px; border-top: 5px solid var(--red); background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; border-radius: 0 0 10px 10px;">
                <i class="fas fa-graduation-cap" style="font-size: 2.5rem; color: var(--navy); margin-bottom: 20px;"></i>
                <h3 style="color: var(--navy); font-size: 1.3rem; margin-bottom: 15px;">Age Groups</h3>
                <p style="font-size: 1rem; color: #555; line-height: 1.6;">Accepting learners from 2 years old up to Grade 3.</p>
            </div>

            <div class="info-card" style="padding: 30px; border-top: 5px solid var(--navy); background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; border-radius: 0 0 10px 10px;">
                <i class="fas fa-star" style="font-size: 2.5rem; color: var(--red); margin-bottom: 20px;"></i>
                <h3 style="color: var(--navy); font-size: 1.3rem; margin-bottom: 15px;">Our Values</h3>
                <p style="font-size: 1rem; color: #555; line-height: 1.6;">Empowerment • Excellence • Community</p>
            </div>

        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>