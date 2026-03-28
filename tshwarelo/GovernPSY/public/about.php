<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Govern Psy Educational Center</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #003366;
            --red: #C41E3A;
            --gold: #FFD700;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            line-height: 1.6;
            background: var(--light-bg);
            margin: 0;
            padding: 0;
        }

        /* --- SLIMMED HEADER (2rem) --- */
        .page-banner {
            background: linear-gradient(135deg, var(--navy) 0%, #001a33 100%);
            padding: 40px 20px; /* Compact padding */
            position: relative;
            overflow: hidden;
            border-bottom: 5px solid var(--red);
            text-align: center;
            color: white;
        }

        .page-banner h1 {
            font-weight: 900;
            letter-spacing: 1px;
            font-size: 2rem; /* Exact size you requested */
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .page-banner p {
            font-size: 0.85rem;
            opacity: 0.8;
            font-weight: 400;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .gold-divider {
            width: 40px; 
            height: 3px; 
            background: var(--gold); 
            margin: 10px auto;
        }

        /* --- CONTENT CARDS --- */
        .pro-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.02);
            overflow: hidden;
        }

        .section-title {
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 25px;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            width: 40px;
            height: 3px;
            background: var(--red);
            position: absolute;
            bottom: -8px;
            left: 0;
        }

        /* --- GALLERY --- */
        .gallery-item img {
            width: 100%; 
            height: 250px; 
            object-fit: cover; 
            display: block;
            transition: transform 0.4s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.08);
        }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <header class="page-banner">
        <div style="position: relative; z-index: 2;">
            <h1>About Our Center</h1>
            <div class="gold-divider"></div>
            <p>Nurturing Excellence Since 2019</p>
        </div>
    </header>

    <main class="container" style="max-width: 1100px; margin: auto; padding: 40px 20px;">
        
        <section style="margin-bottom: 60px;">
            <div class="pro-card" style="display: flex; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 300px; background: var(--navy); color: white; padding: 40px;">
                    <h2 style="font-weight: 800; font-size: 1.8rem; margin-bottom: 15px; color: var(--gold);">Our Story</h2>
                    <p style="font-size: 1rem; opacity: 0.9;">
                        From a small church group of five to a leading educational center in Somerset TRUST, our growth is fueled by a passion for early childhood development.
                    </p>
                </div>
                <div style="flex: 2; min-width: 300px; padding: 40px;">
                    <h2 class="section-title">Humble Beginnings</h2>
                    <p style="color: #555; margin-top: 10px;">
                        Established in <strong>2019</strong>, Govern Psy Educational Centre serves children from <strong>Age 2 to Grade 3</strong>. We provide a foundation built on safety, community, and excellence.
                    </p>
                </div>
            </div>
        </section>

        <section id="gallery" style="margin-bottom: 60px;">
            <h2 class="section-title" style="display: block; text-align: center; margin-bottom: 40px;">Inside Our Classrooms</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                <div class="gallery-item pro-card"><img src="images/classroom_1.jpeg" alt="Classroom 1"></div>
                <div class="gallery-item pro-card"><img src="images/classroom_2.jpeg" alt="Classroom 2"></div>
                <div class="gallery-item pro-card"><img src="images/classroom_3.jpeg" alt="Classroom 3"></div>
                <div class="gallery-item pro-card"><img src="images/classroom_4.jpeg" alt="Classroom 4"></div>
            </div>
        </section>

        <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; margin-bottom: 60px;">
            
            <div class="pro-card" style="padding: 30px; border-top: 5px solid var(--navy);">
                <h3 style="color: var(--navy); font-weight: 800; margin-bottom: 20px;">
                    <i class="fas fa-palette" style="color: var(--red); margin-right: 10px;"></i> Sports & Culture
                </h3>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <span style="background: #eee; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">⚽ Soccer</span>
                    <span style="background: #eee; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">🏀 Netball</span>
                    <span style="background: #eee; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">💃 Culture</span>
                    <span style="background: #eee; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">🎶 Choir</span>
                </div>
            </div>

            <div class="pro-card" style="padding: 30px; border-top: 5px solid var(--red);">
                <h3 style="color: var(--navy); font-weight: 800; margin-bottom: 20px;">
                    <i class="fas fa-info-circle" style="color: var(--red); margin-right: 10px;"></i> Key Info
                </h3>
                <table style="width: 100%; font-size: 0.9rem;">
                    <tr style="border-bottom: 1px solid #f0f0f0;"><td style="padding: 8px 0;">Hours</td><td style="text-align: right; font-weight: 700;">07:45 — 14:00</td></tr>
                    <tr><td style="padding: 8px 0;">Uniform</td><td style="text-align: right; font-weight: 700;">Navy & Red</td></tr>
                </table>
            </div>
        </section>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>