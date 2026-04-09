<?php
require_once 'config/config.php';

// Load the AdmissionModel
require_once 'models/AdmissionModel.php';
$admissionModel = new AdmissionModel();

// Get all admissions
$allAdmissions = $admissionModel->getAllAdmissions();

echo "<h1>🔍 Admin Dashboard Debug</h1>";

echo "<h2>📊 Statistics:</h2>";
echo "<p><strong>Total Applications:</strong> " . count($allAdmissions) . "</p>";

if (!empty($allAdmissions)) {
    $stats = [
        'total' => count($allAdmissions),
        'pending' => count(array_filter($allAdmissions, function($a) { return strtolower($a['status']) === 'pending'; })),
        'approved' => count(array_filter($allAdmissions, function($a) { return strtolower($a['status']) === 'approved'; })),
        'rejected' => count(array_filter($allAdmissions, function($a) { return strtolower($a['status']) === 'rejected'; }))
    ];
    
    echo "<p><strong>Pending:</strong> " . $stats['pending'] . "</p>";
    echo "<p><strong>Approved:</strong> " . $stats['approved'] . "</p>";
    echo "<p><strong>Rejected:</strong> " . $stats['rejected'] . "</p>";
    
    echo "<h2>📋 Recent Applications (First 5):</h2>";
    $recentApplications = array_slice($allAdmissions, 0, 5);
    
    foreach ($recentApplications as $index => $app) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
        echo "<p><strong>#{$index}:</strong> " . $app['applicationID'] . "</p>";
        echo "<p><strong>Child:</strong> " . ($app['child_name'] ?? $app['childFirstName'] . ' ' . $app['childSurname']) . "</p>";
        echo "<p><strong>Parent:</strong> " . $app['parentFirstName'] . ' ' . $app['parentSurname'] . "</p>";
        echo "<p><strong>Status:</strong> " . $app['status'] . "</p>";
        echo "<p><strong>Submitted:</strong> " . $app['submitted_at'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p style='color: red;'>❌ No applications found in system</p>";
}

echo "<hr>";
echo "<p><a href='/admin/dashboard'>Go to Admin Dashboard</a></p>";
echo "<p><a href='/'>Go to Home</a></p>";
?>
