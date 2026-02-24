<?php
session_start();
require_once __DIR__ . '/../app/views/partials/header.php';
require_once __DIR__ . '/../app/views/partials/navbar.php';
?>

<div class="container">

    <!-- HERO -->
    <section class="card" style="background-color:#09223b; color:#ffd700; text-align:center; margin-top:50px;">
        <h1>Help Desk</h1>
        <p style="font-size:16px; margin-top:10px;">
            For more information or queries, please contact the creche practitioner using the details below.
        </p>
    </section>

    <!-- CONTACT DETAILS -->
    <section class="card" style="margin-top:40px; max-width:600px;">
        <h2>Contact Information</h2>
        <p><strong>Creche Name:</strong> Mudzunga Community Creche</p>
        <p><strong>Contact Person:</strong> Creche Practitioner</p>
        <p><strong>Phone Number:</strong> 083 507 3848</p>
        <p><strong>Email Address:</strong> <a href="mailto:chivhuvhu77@gmail.com">chivhuvhu77@gmail.com</a></p>
        <p><strong>Creche Address:</strong> Tshakhuma, Luvhalani A (opposite Tshakhuma Butchery)</p>
    </section>

</div>

<?php
require_once __DIR__ . '/../app/views/partials/footer.php';
?>

