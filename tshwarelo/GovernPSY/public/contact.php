<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Govern Psy Educational Center</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>

<body class="no-scroll-page">

    <?php include 'includes/navbar.php'; ?>

    <main>
        <div class="container" style="width: 100%; max-width: 1200px; padding: 0 20px;">
            
            <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #c3e6cb; font-weight: bold;">
                    <i class="fas fa-check-circle"></i> Thank you! Your inquiry has been sent to the Headmaster.
                </div>
            <?php endif; ?>

            <div class="contact-layout">
                
                <section class="contact-info-card" style="border-top: 5px solid var(--navy);">
                    <div class="card-header">
                        <h2 style="font-family: 'Montserrat', sans-serif; font-weight: 900;">GET IN TOUCH</h2>
                        <p class="cta-message">We are ready to welcome your child.</p>
                    </div>

                    <div class="info-list">
                        <div class="info-row">
                            <div class="icon-box"><i class="fas fa-user-tie"></i></div>
                            <div class="text-box">
                                <h3>Principal / Founder</h3>
                                <p>Simon Hlungwani</p>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="icon-box"><i class="fas fa-phone-alt"></i></div>
                            <div class="text-box">
                                <h3>Phone / WhatsApp</h3>
                                <p><strong>072 719 2946</strong></p>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="icon-box"><i class="fas fa-envelope"></i></div>
                            <div class="text-box">
                                <h3>Email Address</h3>
                                <p><a href="mailto:gaverneducenter@gmail.com" style="color: var(--navy); text-decoration: none;">gaverneducenter@gmail.com</a></p>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="icon-box"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="text-box">
                                <h3>Location</h3>
                                <p>Somerset Village, Mpumalanga</p>
                                <div style="margin-top: 5px;">
                                    <span class="badge">Mon-Thu: 07:45-14:00</span>
                                    <span class="badge">Fri: 07:45-13:30</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="form-card" style="border-top: 5px solid var(--red);">
                    <div class="card-header">
                        <h2 style="font-family: 'Montserrat', sans-serif; font-weight: 900;">SEND A MESSAGE</h2>
                        <p style="color: #666; font-size: 0.9rem;">We typically respond within 24 hours.</p>
                    </div>

                    <form action="actions/process_contact.php" method="POST" class="styled-form">
                        <div class="input-group">
                            <label>Parent/Guardian Name</label>
                            <input type="text" name="guest_name" placeholder="Full Name" required>
                        </div>

                        <div class="input-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone_number" placeholder="e.g. 071 234 5678" required>
                        </div>

                        <div class="input-group">
                            <label>Your Message</label>
                            <textarea name="message" rows="4" placeholder="How can we help you?" required style="resize: none;"></textarea>
                        </div>

                        <button type="submit" class="submit-btn">SUBMIT INQUIRY</button>
                    </form>
                </section>

            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>