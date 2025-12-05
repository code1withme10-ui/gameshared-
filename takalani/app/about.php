<?php
if (session_status() === PHP_SESSION_NONE) {
     
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - SubixStar Pre-School</title>
  <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
  <?php require_once "../app/menu-bar.php"; ?>

  <main style="max-width:800px; margin:50px auto; text-align:center;">
    <h2>About SubixStar Pre-School</h2>
    <p>
      SubixStar Pre-School is dedicated to providing a warm and creative space where young children can
      explore, learn, and grow at their own pace. Our goal is to nurture curiosity and confidence from
      the very start.
    </p>

    <h3>Our Mission</h3>
    <p>
      To create a safe, joyful environment that encourages early learning through play, discovery,
      and care — helping every child reach their full potential.
    </p>

    <h3>Our Team</h3>
    <p>
      Our caring educators are trained in early childhood development and bring years of experience
      to ensure your child’s well-being and growth.
    </p>
  </main>

  <?php include 'footer.php'; ?>
</body>
</html>
