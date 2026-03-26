<?php
// Simple working router that uses existing files
$request = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request, PHP_URL_PATH);
$path = rtrim($path, '/');
$path = $path === '' ? '/' : $path;

// Basic routing
switch ($path) {
    case '/':
        include 'home.php';
        break;
    case '/about':
        include 'about.php';
        break;
    case '/contact':
        include 'contact.php';
        break;
    case '/gallery':
        include 'gallery.php';
        break;
    case '/login':
        include 'login.php';
        break;
    case '/admission':
        include 'admission.php';
        break;
    default:
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The page '$path' was not found.</p>";
        echo "<a href='/'>Go Home</a>";
}
?>
