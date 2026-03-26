<?php
// Simple server that works
require_once 'config/config.php';

// Get the request
$request = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request, PHP_URL_PATH);
$path = rtrim($path, '/');
$path = $path === '' ? '/' : $path;

// Simple routing
switch ($path) {
    case '/':
        echo "<h1>Welcome to Tiny Tots Creche</h1>";
        echo "<p>Server is working!</p>";
        echo "<a href='/login'>Login</a> | ";
        echo "<a href='/admission'>Admission</a> | ";
        echo "<a href='/contact'>Contact</a>";
        break;
    case '/login':
        echo "<h1>Login Page</h1>";
        echo "<p>Login form would go here</p>";
        break;
    case '/admission':
        echo "<h1>Admission Page</h1>";
        echo "<p>Admission form would go here</p>";
        break;
    case '/contact':
        echo "<h1>Contact Page</h1>";
        echo "<p>Contact form would go here</p>";
        break;
    default:
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The page '$path' was not found.</p>";
        echo "<a href='/'>Go Home</a>";
}
?>
