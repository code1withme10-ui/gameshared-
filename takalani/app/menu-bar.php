<?php
// SAFE SESSION START — Only runs if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Check if the user is logged in
$is_logged_in = isset($_SESSION['user']);
$user_role = $_SESSION['user']['role'] ?? 'guest';
$is_headmaster = $is_logged_in && $user_role === 'headmaster';
$is_parent = $is_logged_in && $user_role === 'parent';

?>
<header style="background: linear-gradient(90deg, #FF6B6B, #FFA94D, #FFD93D, #6BCB77, #4D96FF, #A66DD4); padding: 10px 0;">
  <nav style="text-align: center;">

    <a href="/" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Home</a>
    <a href="/about" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">About</a>
    <a href="/admission" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Admission</a>
    
    <?php if ($is_headmaster): ?>
        <a href="/headmaster" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Dashboard</a>
    <?php elseif ($is_parent): ?>
        <a href="/parent" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Guardian</a>
    <?php endif; ?>

    <a href="/gallery" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Gallery</a>
    <a href="/staff" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Staff</a>
    <a href="/code-of-conduct" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Code of Conduct</a>
    <a href="/help" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Help</a>

    <?php if ($is_logged_in): ?>
        <a href="/logout" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Logout</a>
    <?php elseif (!$is_parent): ?>
        <a href="/headmaster-login" style="color:white; margin:0 12px; text-decoration:none; font-weight:bold;">Headmaster Login</a>
    <?php endif; ?>

  </nav>
</header>