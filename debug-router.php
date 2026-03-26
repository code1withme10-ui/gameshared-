<?php
// Debug router to test all routes
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Tiny Tots - Route Debug</h1>";

$request = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request, PHP_URL_PATH);
$path = rtrim($path, '/');
$path = $path === '' ? '/' : $path;

echo "<p>Current Path: '$path'</p>";

// Test if controller exists
if (file_exists('controllers/HomeController.php')) {
    echo "<p>✅ HomeController exists</p>";
} else {
    echo "<p>❌ HomeController missing</p>";
}

// Test routes
echo "<h2>Route Test:</h2>";
switch ($path) {
    case '/':
        echo "<p>✅ Home route matched</p>";
        break;
    case '/about':
        echo "<p>✅ About route matched</p>";
        break;
    case '/contact':
        echo "<p>✅ Contact route matched</p>";
        break;
    case '/gallery':
        echo "<p>✅ Gallery route matched</p>";
        break;
    default:
        echo "<p>❌ Route not found: '$path'</p>";
}

echo "<hr>";
echo "<a href='/'>Home</a> | ";
echo "<a href='/about'>About</a> | ";
echo "<a href='/contact'>Contact</a> | ";
echo "<a href='/gallery'>Gallery</a>";
?>
