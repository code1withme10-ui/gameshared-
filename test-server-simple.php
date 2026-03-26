<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Server Test</h1>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Current Directory: " . getcwd() . "</p>";
echo "<p>Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "</p>";
echo "<p>Request Method: " . ($_SERVER['REQUEST_METHOD'] ?? 'Not set') . "</p>";

// Test if main files exist
$files = [
    'config/config.php',
    'controllers/AuthController.php',
    'controllers/HomeController.php',
    'views/layouts/header.php'
];

echo "<h2>File Check:</h2>";
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file<br>";
    } else {
        echo "❌ $file<br>";
    }
}

// Try to load main application
echo "<h2>Load Test:</h2>";
try {
    require_once 'config/config.php';
    echo "✅ Config loaded<br>";
} catch (Exception $e) {
    echo "❌ Config error: " . $e->getMessage() . "<br>";
}
?>
