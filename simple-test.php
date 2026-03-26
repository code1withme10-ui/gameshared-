<?php
// Simple test without complex routing
echo "<!DOCTYPE html>";
echo "<html><head><title>Tiny Tots Test</title></head>";
echo "<body><h1>🧸 Tiny Tots Creche - Test Page</h1>";
echo "<p>If you see this, PHP is working!</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
echo "<hr>";

// Test CSS loading
echo "<link rel='stylesheet' href='public/css/styles.css'>";
echo "<div class='container'>";
echo "<h2 style='color: var(--primary-color);'>Welcome Test</h2>";
echo "<p>Testing basic functionality...</p>";
echo "</div></body></html>";
?>
