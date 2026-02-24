<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Optional delay message before redirect
$redirectUrl = '/login.php';
$delaySeconds = 2; // seconds before redirect
?>

<?php require_once __DIR__ . '/../app/views/partials/header.php'; ?>
<?php require_once __DIR__ . '/../app/views/partials/navbar.php'; ?>

<div class="container" style="text-align:center; margin-top:100px;">
    <div class="card" style="max-width:400px; margin:auto; padding:30px;">
        <h2>Logged Out</h2>
        <p>You have been successfully logged out.</p>
        <p>Redirecting to <a href="<?php echo $redirectUrl; ?>">login page</a> in <?php echo $delaySeconds; ?> seconds...</p>
    </div>
</div>

<script>
    setTimeout(() => {
        window.location.href = "<?php echo $redirectUrl; ?>";
    }, <?php echo $delaySeconds * 1000; ?>);
</script>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>

