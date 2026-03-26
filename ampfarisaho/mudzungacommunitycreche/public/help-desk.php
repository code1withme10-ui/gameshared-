<?php
session_start();
require_once __DIR__ . '/../app/views/partials/header.php';
require_once __DIR__ . '/../app/views/partials/navbar.php';
?>

<div class="container">

    <!-- PREMIUM HERO SECTION -->
    <section style="
        text-align:center;
        background: linear-gradient(to bottom, #081a2e, #0c2c4d);
        padding:80px 20px;
        margin-top:40px;
        border-radius:10px;
    ">

        <h1 style="
            color:#ffd700;
            font-size:38px;
            letter-spacing:2px;
            font-weight:700;
            margin-bottom:10px;
        ">
            HELP DESK
        </h1>

        <div style="
            width:100px;
            height:3px;
            background-color:#ffd700;
            margin:15px auto 20px auto;
        "></div>

        <p style="
            color:#ffd700;
            font-size:18px;
            max-width:700px;
            margin:0 auto;
            opacity:0.95;
        ">
            For further information, assistance, or general enquiries, 
            please contact the creche practitioner using the details below.
        </p>

    </section>


    <!-- CONTACT SECTION -->
    <section style="
        margin-top:60px;
        margin-bottom:80px;
        background-color:#f8f9fa;
        padding:50px 40px;
        border-radius:10px;
        max-width:700px;
    ">

        <h2 style="color:#09223b; font-size:28px;">
            Contact Information
        </h2>

        <div style="
            width:80px;
            height:3px;
            background-color:#ffd700;
            margin:12px 0 30px 0;
        "></div>

        <p style="font-size:17px; line-height:1.8;">
            <strong>Creche Name:</strong> Mudzunga Community Creche
        </p>

        <p style="font-size:17px; line-height:1.8; margin-bottom:15px;">
            <strong>Creche Practitioner (Tshifhiwa):</strong> 
            <span style="display:inline-flex; align-items:center; gap:12px; margin-left:8px;">
                <a href="tel:0835073848" style="text-decoration:none; color:#09223b; font-weight:600;">
                    <i class="bi bi-telephone-fill" style="color:#09223b;"></i> 083 507 3848
                </a>
                <a href="https://wa.me/27835073848" target="_blank" style="text-decoration:none; color:#25D366; font-size:20px;" title="WhatsApp Tshifhiwa">
                    <i class="bi bi-whatsapp"></i>
                </a>
            </span>
        </p>

        <p style="font-size:17px; line-height:1.8; margin-bottom:25px;">
            <strong>Creche Practitioner (Julia):</strong> 
            <span style="display:inline-flex; align-items:center; gap:12px; margin-left:8px;">
                <a href="tel:0824053167" style="text-decoration:none; color:#09223b; font-weight:600;">
                    <i class="bi bi-telephone-fill" style="color:#09223b;"></i> 082 405 3167
                </a>
                <a href="https://wa.me/27824053167" target="_blank" style="text-decoration:none; color:#25D366; font-size:20px;" title="WhatsApp Julia">
                    <i class="bi bi-whatsapp"></i>
                </a>
            </span>
        </p>

        <p style="font-size:17px; line-height:1.8;">
            <strong>Email Address:</strong> 
            <a href="mailto:chivhuvhu77@gmail.com" 
               style="color:#09223b; font-weight:bold; text-decoration:none;">
               chivhuvhu77@gmail.com
            </a>
        </p>

        <p style="font-size:17px; line-height:1.8;">
            <strong>Address:</strong> Tshakhuma next to Netshikhudini butchery
        </p>

    </section>

</div>

<?php
require_once __DIR__ . '/../app/views/partials/footer.php';
?>

