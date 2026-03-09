<?php
require_once __DIR__ . '/../app/views/partials/header.php';
require_once __DIR__ . '/../app/views/partials/navbar.php';
?>

<div class="container">

<!-- HERO SECTION -->
<section style="
    text-align:center;
    background: linear-gradient(to bottom, #081a2e, #0c2c4d);
    padding:90px 20px;
    margin-top:40px;
    border-radius:10px;
    color:white;
">

    <!-- Logo -->
    <img src="/assets/images/logo.png"
         alt="Mudzunga Logo"
         style="width:170px; height:auto; margin-bottom:25px;">

    <!-- Creche Name -->
    <h1 style="
        color:#ffd700;
        font-size:44px;
        letter-spacing:2px;
        font-weight:800;
        margin-bottom:10px;
    ">
        MUDZUNGA COMMUNITY CRECHE
    </h1>

    <!-- Divider -->
    <div style="
        width:140px;
        height:3px;
        background:#ffd700;
        margin:18px auto;
    "></div>

    <!-- Slogan -->
    <p style="
        color:#f2f2f2;
        font-size:20px;
        font-style:italic;
        max-width:720px;
        margin:auto;
        line-height:1.6;
    ">
        A safe, happy, and caring place where children learn, grow, and thrive.
    </p>

    <!-- Buttons -->
    <div style="margin-top:45px;">
        <a href="/register.php"
           style="
                background:#ffd700;
                color:#09223b;
                padding:14px 30px;
                text-decoration:none;
                font-weight:bold;
                border-radius:6px;
                margin:10px;
                display:inline-block;
                font-size:16px;
           ">
            Register
        </a>

        <a href="/login.php"
           style="
                border:2px solid #ffd700;
                color:#ffd700;
                padding:14px 30px;
                text-decoration:none;
                font-weight:bold;
                border-radius:6px;
                margin:10px;
                display:inline-block;
                font-size:16px;
           ">
            Login
        </a>
    </div>

</section>


<!-- ABOUT PREVIEW -->
<section class="card" style="margin-top:50px;">

    <h2 style="font-size:28px;">About Our Creche</h2>

    <p style="font-size:17px; line-height:1.7;">
        <strong>Mudzunga Community Creche</strong> was established in 1985 in the heart of Venda
        (Limpopo, Tshakhuma Luvhalani A, opposite Tshakhuma Butchery). The creche was founded
        to provide a safe, loving, and nurturing environment where young children can begin
        their early learning journey.
    </p>

    <p style="font-size:17px; line-height:1.7;">
        We believe that every child deserves the opportunity to grow in a caring environment
        that encourages curiosity, creativity, and confidence. Our dedicated practitioners
        ensure that children are supported emotionally, socially, and academically during
        their early development years.
    </p>

    <p style="font-size:17px;">
        <strong>Our Mission:</strong>
        To provide a safe, nurturing environment where little ones learn, grow, and thrive.
    </p>

    <a href="/about.php"
       class="btn btn-primary"
       style="margin-top:20px; width:200px;">
        Learn More
    </a>

</section>


<!-- FEATURES SECTION -->
<section style="margin-top:40px; display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:25px;">

    <div class="card">
        <h3>👶 Safe Environment</h3>
        <p>
            Our creche provides a secure and child-friendly environment where children
            feel protected, valued, and supported every day.
        </p>
    </div>

    <div class="card">
        <h3>📚 Early Learning</h3>
        <p>
            We focus on early childhood development through play-based learning,
            creativity, and interactive activities.
        </p>
    </div>

    <div class="card">
        <h3>❤️ Caring Staff</h3>
        <p>
            Our experienced practitioners are passionate about nurturing children's
            emotional, social, and intellectual growth.
        </p>
    </div>

</section>

</div>

<?php
require_once __DIR__ . '/../app/views/partials/footer.php';
?>
