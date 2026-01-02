<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'headmaster') {
    header('Location: /headmaster-login'); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /takalani/app/headmaster?error=' . urlencode('Invalid request method.'));
    exit();
}

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;
$childIndex = $_POST['child_index'] ?? null;

if (!$id || !in_array($status, ['Admitted', 'Rejected']) || $childIndex === null) {
    header('Location: /takalani/app/headmaster?error=' . urlencode('Missing or invalid data received.'));
    exit();
}

$admissionFile = __DIR__ . '/../data/admissions.json';
$admissions = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];
$found = false;
$childName = 'N/A';

foreach ($admissions as &$admission) {
    $currentId = $admission['applicationID'] ?? $admission['id'] ?? null;
    if ((string)$currentId === (string)$id) {
        if (isset($admission['children'][$childIndex])) {
            // Update specific child status
            $admission['children'][$childIndex]['status'] = $status;
            $admission['children'][$childIndex]['reviewedAt'] = date('Y-m-d H:i:s');
            
            $childName = trim(($admission['children'][$childIndex]['firstName'] ?? '') . ' ' . ($admission['children'][$childIndex]['surname'] ?? ''));
            
            // Set notification for parent portal
            $admission['lastNotification'] = "Update: Student '{$childName}' has been '{$status}'.";
            $admission['notificationAt'] = date('Y-m-d H:i:s');
            $found = true;
        }
        break;
    }
}
unset($admission);

if ($found) {
    file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT));
    header('Location: /takalani/app/headmaster?success=' . urlencode("Student '{$childName}' successfully set to {$status}."));
} else {
    header('Location: /takalani/app/headmaster?error=' . urlencode('Record not found.'));
}
exit();