<?php
require_once __DIR__ . '/../app/views/partials/header.php';
require_once __DIR__ . '/../app/views/partials/navbar.php';
?>

<div class="container">

    <!-- HERO / WELCOME SECTION -->
    <section class="card" style="text-align:center; background-color:#09223b; color:#ffd700; margin-top:50px;">
        <h1>Welcome to Mudzunga Community Creche</h1>
        <p style="font-size:18px; margin-top:10px;">
            A safe, happy, and caring place where children learn, grow, and thrive.
        </p>
        <div style="margin-top:30px;">
            <a href="/register.php" class="btn btn-primary" style="margin-bottom:10px; display:block; width:180px; margin-left:auto; margin-right:auto;">Register</a>
            <a href="/login.php" class="btn btn-outline" style="display:block; width:180px; margin-left:auto; margin-right:auto;">Login</a>
        </div>
    </section>

    <!-- ABOUT US PREVIEW -->
    <section class="card" style="margin-top:40px;">
        <h2>About Us</h2>
        <p>
            MUDZUNGA COMMUNITY CRECHE was established in 1985 in the heart of Venda (Limpopo, Venda, Tshakhuma Luvhalani A, opposite Tshakhuma Butchery) with the aim of giving young children a safe, happy, and caring place to learn and grow. 
        </p>
        <p><strong>Our Mission:</strong> Providing a safe, nurturing environment where little ones learn, grow, and thrive.</p>
        <a href="/about.php" class="btn btn-primary" style="margin-top:15px;">Learn More</a>
    </section>

</div>

<?php
require_once __DIR__ . '/../app/views/partials/footer.php';
?>
