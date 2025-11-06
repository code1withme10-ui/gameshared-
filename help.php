<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help - SubixStar Pre-School</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
// Include the existing menu bar for navigation
require_once 'menu-bar.php';
?>

<main style="text-align:center; margin-top:50px;">
    <h2>Help & Support</h2>
    <p>If you need any assistance using our platform, please see the information below:</p>

    <section style="margin-top:30px;">
        <h3>📞 Contact Support</h3>
        <p>Email: <a href="mailto:support@subixstar.co.za">support@subixstar.co.za</a></p>
        <p>Phone: +27 71 234 5678</p>
    </section>

    <section style="margin-top:30px;">
        <h3>💡 Common Questions</h3>
        <ul style="list-style:none; padding:0;">
            <li>• How do I register? — Go to the <a href="registration.php">Registration Page</a>.</li>
            <li>• How do I log in? — Visit the <a href="login.php">Login Page</a>.</li>
            <li>• I forgot my password — Contact the school administrator.</li>
        </ul>
    </section>
</main>

<footer style="text-align:center; margin-top:30px;">
    <p>© 2025 SubixStar Pre-School</p>
</footer>

</body>
</html>
