<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Govern Psy Educational Center</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #003366;
            --red: #C41E3A;
            --gold: #FFD700;
        }
        .gallery-item {
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
            transition: transform 0.3s ease;
            background: #eee;
        }
        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .gallery-img {
            width: 100%; 
            height: 250px; 
            object-fit: cover; 
            display: block;
        }
    </style>
</head>
<body style="background: #fdfdfd; margin: 0; padding: 0;">

    <?php include 'includes/navbar.php'; ?>

    <header class="page-banner" style="background: var(--navy); padding: 80px 20px; border-bottom: 5px solid var(--red); text-align: center; color: white;">
        <h1 style="font-family: 'Montserrat', sans-serif; font-weight: 900; letter-spacing: 1px; font-size: 2.8rem; margin-bottom: 5px; text-transform: uppercase;">
            ABOUT OUR CENTER
        </h1>
        <p style="font-size: 1rem; opacity: 0.9; font-weight: 400; letter-spacing: 2px; text-transform: uppercase;">
            Govern PSY EDU CENTER | Nurturing Excellence Since 2019
        </p>
    </header>

    <main class="container" style="max-width: 1200px; margin: auto; padding: 40px 20px;">
        
        <section style="margin-bottom: 60px;">
            <div style="border-left: 8px solid var(--red); padding: 40px; background: white; border-radius: 0 15px 15px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                <h2 style="color: var(--navy); margin-bottom: 20px; font-weight: 800; font-size: 1.8rem;">
                    <i class="fas fa-history" style="color: var(--red); margin-right: 12px;"></i> Our Humble Beginnings
                </h2>
                <p style="font-size: 1.1rem; color: #444; margin-bottom: 20px; line-height: 1.8;">
                    Located in the heart of <strong>Somerset TRUST, Mpumalanga</strong>, Govern Psy Educational Centre was established in <strong>2019</strong>. We started with a small, dedicated group of just five children in a local church.
                </p>
                <p style="font-size: 1.1rem; color: #444; line-height: 1.8;">
                    Today, we have grown into a vibrant learning hub, providing a safe and empowering environment for children from <strong>Age 2 to Grade 3</strong>. Our journey is a testament to community and the power of early education.
                </p>
            </div>
        </section>

        <section id="gallery" style="margin-bottom: 70px;">
            <h2 style="color: var(--navy); margin-bottom: 30px; font-weight: 800; text-align: center; text-transform: uppercase;">
                <i class="fas fa-images" style="color: var(--red); margin-right: 10px;"></i> Inside Our Classrooms
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                
                <div class="gallery-item">
                    <img src="images/classroom_1.jpeg" class="gallery-img" alt="Classroom 1">
                </div>

                <div class="gallery-item">
                    <img src="images/classroom_2.jpeg" class="gallery-img" alt="Classroom 2">
                </div>

                <div class="gallery-item">
                    <img src="images/classroom_3.jpeg" class="gallery-img" alt="Classroom 3">
                </div>

                <div class="gallery-item">
                    <img src="images/classroom_4.jpeg" class="gallery-img" alt="Classroom 4">
                </div>

                <div class="gallery-item">
                    <img src="images/classroom_5.jpeg" class="gallery-img" alt="Classroom 5">
                </div>

            </div>
        </section>

        <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px; margin-bottom: 70px;">
            
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-top: 6px solid var(--navy);">
                <h3 style="color: var(--navy); font-size: 1.5rem; margin-bottom: 20px;">
                    <i class="fas fa-palette" style="color: var(--red); margin-right: 10px;"></i> Sports & Culture
                </h3>
                <p style="color: #666; line-height: 1.7;">
                    We believe in holistic development. Beyond academics, our learners participate in activities that build teamwork and creativity:
                </p>
                <ul style="color: #444; line-height: 2; margin-top: 15px;">
                    <li>Traditional Dance & Choir</li>
                    <li>Soccer and Netball basics</li>
                    <li>Annual Cultural Day Celebrations</li>
                </ul>
            </div>

            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-top: 6px solid var(--red);">
                <h3 style="color: var(--navy); font-size: 1.5rem; margin-bottom: 20px;">
                    <i class="fas fa-info-circle" style="color: var(--red); margin-right: 10px;"></i> General Information
                </h3>
                <table style="width: 100%; border-collapse: collapse; color: #444;">
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 0; font-weight: 700;">Operating Hours</td>
                        <td style="padding: 12px 0; text-align: right;">07:45 — 14:00 (M-T)</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 0; font-weight: 700;">Fridays</td>
                        <td style="padding: 12px 0; text-align: right;">07:45 — 13:30</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px 0; font-weight: 700;">School Uniform</td>
                        <td style="padding: 12px 0; text-align: right;">Navy & Gold</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 0; font-weight: 700;">Enrollment</td>
                        <td style="padding: 12px 0; text-align: right;">Age 2 to Grade 3</td>
                    </tr>
                </table>
            </div>
        </section>

        <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-bottom: 50px;">
            <div style="background: var(--navy); padding: 40px; border-radius: 15px; text-align: center; color: white;">
                <i class="fas fa-eye" style="font-size: 2.5rem; color: var(--gold); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 15px; font-size: 1.6rem;">OUR VISION</h3>
                <p style="font-style: italic; opacity: 0.9;">"Bright minds. Strong roots. Global futures."</p>
            </div>
            <div style="background: var(--red); padding: 40px; border-radius: 15px; text-align: center; color: white;">
                <i class="fas fa-bullseye" style="font-size: 2.5rem; color: white; margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 15px; font-size: 1.6rem;">OUR MISSION</h3>
                <p style="opacity: 0.95;">"To provide high-quality education that connects academic learning with practical skills for real life."</p>
            </div>
        </section>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>