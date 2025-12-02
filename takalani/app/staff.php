<?php
if (session_status() === PHP_SESSION_NONE) {
     
}
require_once "../app/menu-bar.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Staff - SubixStar Pre-School</title>
    <link rel="stylesheet" href="public/css/styles.css"> <!-- keep your site-wide styles -->
</head>

<body>
    <header style="background-color:#f8f9fa; color:#333; text-align:center; padding:1.5rem; border-bottom:1px solid #ddd;">
        <h1>Meet Our Caring Staff</h1>
        <p>Dedicated educators who nurture your child’s early learning journey.</p>
    </header>

    <?php
    // Staff array
    $aTeachers = [
        ['name' => 'Mashudu', 'picture' => 'images/mashudu.jpg', 'role' => 'Head Teacher'],
        ['name' => 'Mpho', 'picture' => 'images/mpho.jpg', 'role' => 'Assistant Teacher'],
        ['name' => 'Sipho', 'picture' => 'images/sipho.jpg', 'role' => 'Math Tutor'],
        ['name' => 'Thandi', 'picture' => 'images/thandi.jpg', 'role' => 'Arts & Crafts Teacher'],
        ['name' => 'Kabelo', 'picture' => 'images/kabelo.jpg', 'role' => 'PE Coach'],
        ['name' => 'Nandi', 'picture' => 'images/nandi.jpg', 'role' => 'Language Teacher'],
        ['name' => 'Tebogo', 'picture' => 'images/tebogo.jpg', 'role' => 'Classroom Assistant'],
        ['name' => 'Zanele', 'picture' => 'images/zanele.jpg', 'role' => 'Music Teacher'],
    ];
    ?>

    <main style="max-width:900px; margin:30px auto; text-align:center;">
        <h2>Our Staff Team</h2>
        <p><strong>Total Staff Members:</strong> <?= count($aTeachers) ?></p>

        <?php foreach ($aTeachers as $teacher): ?>
            <div class="staffinfo" style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:15px; margin:15px auto; width:80%; display:flex; align-items:center; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
                <img src="<?= htmlspecialchars($teacher['picture']) ?>" alt="<?= htmlspecialchars($teacher['name']) ?>" style="width:80px; height:80px; border-radius:50%; border:2px solid #ccc; object-fit:cover; margin-right:20px;">
                <div>
                    <h4 style="margin:0; color:#2c3e50;"><?= htmlspecialchars($teacher['name']) ?></h4>
                    <p style="color:#666;"><?= htmlspecialchars($teacher['role']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
