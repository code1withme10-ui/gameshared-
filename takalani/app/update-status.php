<?php
// CRITICAL FIX: Robust session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Set up error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- 1. Authorization Check ---
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'headmaster') {
    // FIX: Redirect to file-based login
    header('Location: headmaster-login.php'); 
    exit();
}

// --- 2. Input Validation ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // FIX: Redirect to file-based dashboard
    header('Location: headmaster.php?error=' . urlencode('Invalid request method.'));
    exit();
}

$id     = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$id || !in_array($status, ['Admitted', 'Rejected'])) {
    // FIX: Redirect to file-based dashboard
    header('Location: headmaster.php?error=' . urlencode('Missing or invalid application ID or status received.'));
    exit();
}

// --- 3. Data Processing ---
// FIX: Simplified data path (Fixes redundant '../data/../data/')
$admissionFile = __DIR__ . '/../data/admissions.json';
$admissions = file_exists($admissionFile) 
    ? json_decode(file_get_contents($admissionFile), true) 
    : [];

$found = false;
$childName = 'N/A';

// Iterate through the array by reference (&) to modify the original array item
foreach ($admissions as &$admission) {
    // CRITICAL FIX: Check applicationID first, then 'id', for backward compatibility
    $currentId = $admission['applicationID'] ?? $admission['id'] ?? null;
    
    if ($currentId === $id) {
        
        // Update the status
        $admission['status'] = $status;
        $admission['reviewedBy'] = $_SESSION['user']['username'] ?? 'Headmaster';
        $admission['reviewedAt'] = date('Y-m-d H:i:s');

        // Prepare the notification message for the parent
        $childFirstName = $admission['childFirstName'] ?? $admission['child']['firstName'] ?? '';
        $childSurname = $admission['childSurname'] ?? $admission['child']['surname'] ?? '';
        $childName = trim($childFirstName . ' ' . $childSurname);
        
        // Set the notification message
        $admission['lastNotification'] = "Status update for **{$childName}**: Your application has been **{$status}**.";
        $admission['notificationAt'] = date('Y-m-d H:i:s');

        $found = true;
        break; // Stop loop once found
    }
}
unset($admission); // Break the reference for safety

// --- 4. Save Changes and Redirect ---\n
if ($found) {
    if (file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT))) {
        // FIX: Redirect to file-based dashboard
        header('Location: headmaster.php?success=' . urlencode("Application for '{$childName}' successfully set to {$status}."));
    } else {
        // FIX: Redirect to file-based dashboard and corrected the error message path for clarity
        header('Location: headmaster.php?error=' . urlencode("Failed to save data to ../data/admissions.json. Check file permissions."));
    }
} else {
    // FIX: Redirect to file-based dashboard
    header('Location: headmaster.php?error=' . urlencode('Application ID not found or data corrupted.'));
}
exit();