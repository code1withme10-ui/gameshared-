<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Govern Psy Educational Center</title>
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
            background-color: var(--light-bg);
            margin: 0;
            color: #333;
        }

        /* --- CONSISTENT SLIM HEADER --- */
        .page-banner {
            background: linear-gradient(135deg, var(--navy) 0%, #001a33 100%);
            padding: 40px 20px;
            position: relative;
            border-bottom: 5px solid var(--red);
            text-align: center;
            color: white;
        }

        .page-banner h1 {
            font-weight: 900;
            font-size: 2rem; /* Matches About Page */
            letter-spacing: 1px;
            margin: 0;
            text-transform: uppercase;
        }

        .gold-divider {
            width: 40px; 
            height: 3px; 
            background: var(--gold); 
            margin: 10px auto;
        }

        /* --- CONTACT LAYOUT --- */
        .contact-container {
            max-width: 1100px;
            margin: 50px auto;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 30px;
            padding: 0 20px;
        }

        @media (max-width: 850px) {
            .contact-container { grid-template-columns: 1fr; }
        }

        .info-card, .form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 40px;
        }

        .info-card { border-top: 5px solid var(--navy); }
        .form-card { border-top: 5px solid var(--red); }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .icon-box {
            width: 45px;
            height: 45px;
            background: #f0f4f8;
            color: var(--navy);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .info-text h4 { margin: 0; font-size: 0.8rem; color: #888; text-transform: uppercase; }
        .info-text p { margin: 2px 0 0; font-weight: 700; color: var(--navy); }

        /* --- FORM STYLING --- */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 700; font-size: 0.8rem; margin-bottom: 8px; color: var(--navy); }
        
        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            box-sizing: border-box;
        }

        .submit-btn {
            background: var(--red);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 800;
            width: 100%;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover { background: #a31830; transform: translateY(-2px); }

        .badge {
            background: #eee;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <header class="page-banner">
        <h1>Contact Us</h1>
        <div class="gold-divider"></div>
        <p style="text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; opacity: 0.8;">Get in touch with our team</p>
    </header>

    <main class="contact-container">
        
        <section class="info-card">
            <h2 style="margin-bottom: 30px; font-weight: 900; color: var(--navy);">Get In Touch</h2>
            
            <div class="info-item">
                <div class="icon-box"><i class="fas fa-user-tie"></i></div>
                <div class="info-text">
                    <h4>Principal</h4>
                    <p>Simon Hlungwani</p>
                </div>
            </div>

            <div class="info-item">
                <div class="icon-box"><i class="fas fa-phone-alt"></i></div>
                <div class="info-text">
                    <h4>WhatsApp</h4>
                    <p>072 719 2946</p>
                </div>
            </div>

            <div class="info-item">
                <div class="icon-box"><i class="fas fa-envelope"></i></div>
                <div class="info-text">
                    <h4>Email</h4>
                    <p>gaverneducenter@gmail.com</p>
                </div>
            </div>

            <div class="info-item">
                <div class="icon-box"><i class="fas fa-map-marker-alt"></i></div>
                <div class="info-text">
                    <h4>Location</h4>
                    <p>Somerset Village, Mpumalanga</p>
                    <span class="badge">Mon-Thu: 07:45-14:00</span>
                </div>
            </div>
        </section>

        <section class="form-card">
            <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; border-left: 5px solid #28a745;">
                    <i class="fas fa-check-circle"></i> Message sent successfully!
                </div>
            <?php endif; ?>

            <h2 style="margin-bottom: 10px; font-weight: 900; color: var(--navy);">Send a Message</h2>
            <p style="color: #666; font-size: 0.9rem; margin-bottom: 30px;">We'll get back to you as soon as possible.</p>

            <form action="actions/process_contact.php" method="POST">
                <div class="form-group">
                    <label>YOUR NAME</label>
                    <input type="text" name="guest_name" class="form-input" placeholder="Full Name" required>
                </div>

                <div class="form-group">
                    <label>PHONE NUMBER</label>
                    <input type="tel" name="phone_number" class="form-input" placeholder="072 123 4567" required>
                </div>

                <div class="form-group">
                    <label>MESSAGE</label>
                    <textarea name="message" class="form-input" rows="5" placeholder="How can we help you?" required style="resize: none;"></textarea>
                </div>

                <button type="submit" class="submit-btn">SEND MESSAGE</button>
            </form>
        </section>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>