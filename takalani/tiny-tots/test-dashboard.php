<?php
// Simulate headmaster login session
session_start();
$_SESSION['user'] = [
    'id' => 'headmaster_001',
    'username' => 'admin',
    'name' => 'Vanessa Roets',
    'email' => 'mollerv40@gmail.com',
    'phone' => '081 421 0084',
    'role' => 'headmaster'
];

// Now test the dashboard
require_once 'config/config.php';

// Test the dashboard method directly
$controller = new AdmissionController();
echo "Testing dashboard method...<br>";

try {
    ob_start();
    $controller->dashboard();
    $output = ob_get_clean();
    echo "Dashboard method executed successfully!<br>";
    echo "Output length: " . strlen($output) . " characters<br>";
    if (strpos($output, 'Headmaster Dashboard') !== false) {
        echo "Dashboard content found in output!<br>";
    } else {
        echo "Dashboard content NOT found in output<br>";
    }
} catch (Exception $e) {
    echo "Error executing dashboard: " . $e->getMessage() . "<br>";
}
?>
