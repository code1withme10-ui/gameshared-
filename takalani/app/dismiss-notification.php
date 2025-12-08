<?php
 
// Set up error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'parent') {
    header('Location: login.php');
    exit();
}

$id = $_POST['id'] ?? null;
if (!$id) {
    header('Location: parent.php');
    exit();
}

$admissionFile = __DIR__ . '/../data/admissions.json';
$admissions = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];

$parentEmail = strtolower($_SESSION['user']['email'] ?? '');
$found = false;

foreach ($admissions as &$admission) {
    // CRITICAL FIX: Check applicationID first, then 'id'
    $currentId = $admission['applicationID'] ?? $admission['id'] ?? null;
    $appEmail = strtolower($admission['parent']['emailAddress'] ?? $admission['parentEmail'] ?? '');

    // Check application ID and ensure it belongs to the logged-in parent
    if ($currentId === $id && $appEmail === $parentEmail) {
        // Clear the notification fields
        $admission['lastNotification'] = null;
        $admission['notificationAt'] = null;
        $found = true;
        break;
    }
}
unset($admission);

if ($found) {
    file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT));
}

header('Location: parent.php');
exit();
?>