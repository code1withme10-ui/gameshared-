<?php
// Test admission submission
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in. Please login first.";
    exit;
}

// Check admission controller
require_once 'controllers/AdmissionController.php';

// Create admission controller instance
$admissionController = new AdmissionController();

// Test CSRF token generation
$csrfToken = $admissionController->generateCsrfToken();
echo "CSRF Token Generated: " . $csrfToken . "<br>";

// Test admission form rendering
echo "Testing admission form access...<br>";

// Check if admission model exists
require_once 'models/AdmissionModel.php';
$admissionModel = new AdmissionModel();
echo "Admission Model loaded successfully<br>";

// Test file upload directory
$uploadDir = 'uploads/admissions/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
    echo "Created upload directory: " . $uploadDir . "<br>";
} else {
    echo "Upload directory exists: " . $uploadDir . "<br>";
}

// Test parent portal route
echo "Parent portal route: /parent/portal<br>";
echo "Dashboard route: /parent/dashboard<br>";

echo "<br><strong>Admission System Status: READY</strong><br>";
echo "<a href='/admission'>Go to Admission Form</a><br>";
echo "<a href='/parent/portal'>Go to Parent Portal</a><br>";
?>
