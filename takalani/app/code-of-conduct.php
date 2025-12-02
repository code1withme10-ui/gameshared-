<?php
if (session_status() === PHP_SESSION_NONE) {
     
}
require_once "../app/menu-bar.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Code of Conduct</title>
    <link rel="stylesheet" href="public/css/styles.css" />
</head>
<body>
    <main>
        <h2>Our Commitment</h2>
        <p>We are dedicated to maintaining a safe, respectful, and supportive environment for children, parents, and staff.</p>

        <h3>Expectations for Parents & Guardians</h3>
        <ul>
            <li>Respect all staff and follow creche policies.</li>
            <li>Communicate openly and honestly about your child's needs.</li>
            <li>Maintain confidentiality about other children and families.</li>
        </ul>

        <h3>Expectations for Children</h3>
        <ul>
            <li>Be kind and respectful to peers and adults.</li>
            <li>Listen and follow instructions from staff.</li>
            <li>Care for creche property and materials.</li>
        </ul>

        <h3>Expectations for Staff</h3>
        <ul>
            <li>Provide nurturing and safe care at all times.</li>
            <li>Communicate clearly and respectfully with families.</li>
            <li>Ensure children’s safety and well-being as a top priority.</li>
        </ul>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
