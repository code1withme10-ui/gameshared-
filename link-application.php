<?php
require_once 'config/config.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is a parent
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'parent') {
    echo "Please login as a parent first";
    exit;
}

$parentId = $_SESSION['user']['id'];
echo "<h1>🔗 Link Application to Parent</h1>";
echo "<p><strong>Current Parent ID:</strong> $parentId</p>";

// Load admissions
require_once 'models/AdmissionModel.php';
$admissionModel = new AdmissionModel();
$allAdmissions = $admissionModel->getAllAdmissions();

echo "<h2>📋 Available Applications:</h2>";
foreach ($allAdmissions as $index => $app) {
    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
    echo "<p><strong>Application ID:</strong> " . $app['id'] . "</p>";
    echo "<p><strong>Child:</strong> " . ($app['child_name'] ?? $app['childFirstName'] . ' ' . $app['childSurname']) . "</p>";
    echo "<p><strong>Current Parent ID:</strong> " . ($app['parent_id'] ?? 'NULL') . "</p>";
    echo "<p><strong>Status:</strong> " . $app['status'] . "</p>";
    
    if ($app['parent_id'] === $parentId) {
        echo "<p style='color: green;'>✅ Already linked to this parent</p>";
    } else {
        echo "<form method='post' style='margin-top: 10px;'>";
        echo "<input type='hidden' name='application_id' value='" . $app['id'] . "'>";
        echo "<input type='hidden' name='parent_id' value='$parentId'>";
        echo "<button type='submit' name='link_application' style='background: #007bff; color: white; padding: 5px 10px; border: none; cursor: pointer;'>🔗 Link to My Account</button>";
        echo "</form>";
    }
    echo "</div>";
}

// Handle linking
if (isset($_POST['link_application'])) {
    $applicationId = $_POST['application_id'];
    $newParentId = $_POST['parent_id'];
    
    // Read admissions
    $admissions = $admissionModel->readJsonFile();
    
    // Find and update the application
    foreach ($admissions as &$app) {
        if ($app['id'] === $applicationId) {
            $app['parent_id'] = $newParentId;
            break;
        }
    }
    
    // Save back
    $admissionModel->writeJsonFile($admissions);
    
    echo "<script>alert('Application linked successfully!'); window.location.href = window.location.href;</script>";
}

echo "<hr>";
echo "<p><a href='/parent/portal'>Go to Parent Portal</a></p>";
echo "<p><a href='/debug-parent.php'>Debug Parent Portal</a></p>";
?>
