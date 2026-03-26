<?php
// Debug test file
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Test - Working!</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Current Directory: " . __DIR__ . "</p>";
echo "<p>Server URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "</p>";

// Test config loading
try {
    require_once 'config/config.php';
    echo "<p style='color: green;'>✅ Config loaded successfully</p>";
    echo "<p>VIEWS_PATH: " . VIEWS_PATH . "</p>";
    echo "<p>DATA_PATH: " . DATA_PATH . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Config error: " . $e->getMessage() . "</p>";
}

// Test controller loading
try {
    require_once 'controllers/BaseController.php';
    require_once 'controllers/HomeController.php';
    echo "<p style='color: green;'>✅ Controllers loaded successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Controller error: " . $e->getMessage() . "</p>";
}

// Test view loading
try {
    if (file_exists('views/home/index.php')) {
        echo "<p style='color: green;'>✅ Home view exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Home view missing</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ View error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<a href='/'>← Back to Home</a>";
?>
