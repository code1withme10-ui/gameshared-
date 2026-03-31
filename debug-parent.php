<?php
require_once 'config/config.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>🔍 Parent Portal Debug</h1>";

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo "<p style='color: red;'>❌ No user logged in</p>";
    echo "<p><a href='/login'>Login first</a></p>";
    exit;
}

echo "<h2>👤 Current User Info:</h2>";
echo "<p><strong>ID:</strong> " . $_SESSION['user']['id'] . "</p>";
echo "<p><strong>Name:</strong> " . $_SESSION['user']['name'] . "</p>";
echo "<p><strong>Username:</strong> " . $_SESSION['user']['username'] . "</p>";
echo "<p><strong>Role:</strong> " . $_SESSION['user']['role'] . "</p>";

// Check if user is a parent
if ($_SESSION['user']['role'] !== 'parent') {
    echo "<p style='color: red;'>❌ User is not a parent! Role: " . $_SESSION['user']['role'] . "</p>";
    exit;
}

// Load admissions and check for parent applications
require_once 'models/AdmissionModel.php';
$admissionModel = new AdmissionModel();
$parentApplications = $admissionModel->getApplicationsByParent($_SESSION['user']['id']);

echo "<h2>📋 Applications Found:</h2>";
echo "<p><strong>Parent ID:</strong> " . $_SESSION['user']['id'] . "</p>";
echo "<p><strong>Applications Count:</strong> " . count($parentApplications) . "</p>";

if (empty($parentApplications)) {
    echo "<p style='color: orange;'>⚠️ No applications found for this parent</p>";
    
    // Show all admissions for debugging
    echo "<h3>🔍 All Admissions in System:</h3>";
    $allAdmissions = $admissionModel->getAllAdmissions();
    foreach ($allAdmissions as $app) {
        echo "<p>";
        echo "<strong>App ID:</strong> " . $app['id'] . " | ";
        echo "<strong>Parent ID:</strong> " . ($app['parent_id'] ?? 'NULL') . " | ";
        echo "<strong>Child:</strong> " . ($app['child_name'] ?? $app['childFirstName'] . ' ' . $app['childSurname']) . " | ";
        echo "<strong>Status:</strong> " . $app['status'];
        echo "</p>";
    }
} else {
    echo "<h3>✅ Parent's Applications:</h3>";
    foreach ($parentApplications as $app) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
        echo "<p><strong>Application ID:</strong> " . $app['id'] . "</p>";
        echo "<p><strong>Child Name:</strong> " . ($app['child_name'] ?? $app['childFirstName'] . ' ' . $app['childSurname']) . "</p>";
        echo "<p><strong>Status:</strong> " . $app['status'] . "</p>";
        echo "<p><strong>Submitted:</strong> " . $app['submitted_at'] . "</p>";
        echo "</div>";
    }
}

echo "<hr>";
echo "<p><a href='/parent/portal'>Go to Parent Portal</a></p>";
echo "<p><a href='/admission'>Apply for Admission</a></p>";
echo "<p><a href='/logout'>Logout</a></p>";
?>
