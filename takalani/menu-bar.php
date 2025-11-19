<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header style="background: linear-gradient(90deg, #FF6B6B, #FFA94D, #FFD93D, #6BCB77, #4D96FF, #A66DD4); padding: 10px 0;">
  <nav style="text-align: center;">
    <a href="index.php" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Home</a>
    <a href="about.php" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">About</a>
    <a href="admission.php" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Admission</a>
    <a href="parent.php" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Guardian</a>
    <a href="gallery.php" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Gallery</a>
    <a href="staff.php" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Staff</a>
    <a href="help.php" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Help</a>
    <a href="code-of-conduct.php" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Code of Conduct</a>

    <?php if (isset($_SESSION['user'])): ?>
      <a href="logout.php" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">
        Logout (<?= htmlspecialchars($_SESSION['user']['email'] ?? $_SESSION['user']['username']) ?>)
      </a>
    <?php endif; ?>
  </nav>
</header>
