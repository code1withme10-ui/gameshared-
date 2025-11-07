<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Kids Creche</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<!-- header.php -->
<nav>
    <ul>
        <li><a href="index.php">Home</a></li> |
        <li><a href="about.php">About Us</a></li> |
        <li><a href="gallery.php">Gallery</a></li> |
        <li><a href="code-of-conduct.php">Code Of Conduct</a></li> |
        <li><a href="registration.php">Admission</a></li> |
        <?php if (isset($_SESSION['parent_name'])): ?>
            <li><a href="welcome.php">Welcome</a></li> |
            <li><a href="logout.php">Log Out</a></li> |
        <?php else: ?>
            <li><a href="login.php">Parent Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
