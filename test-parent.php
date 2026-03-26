<?php
echo "<h1>Parent Portal Test</h1>";
echo "<p>Testing if parent directory is accessible...</p>";
echo "<p>Current working directory: " . getcwd() . "</p>";
echo "<p>Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "</p>";

// Test if parent directory exists
if (file_exists('parent/portal.php')) {
    echo "<p>✅ parent/portal.php exists</p>";
} else {
    echo "<p>❌ parent/portal.php not found</p>";
}

// Test if we can include the file
if (file_exists('parent/portal.php')) {
    echo "<p>Trying to include parent/portal.php...</p>";
    try {
        include 'parent/portal.php';
    } catch (Exception $e) {
        echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    }
}
?>
