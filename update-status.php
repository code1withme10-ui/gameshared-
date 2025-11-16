<?php
session_start();

// Only headmaster
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'headmaster') {
    die('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

$id     = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$id || !in_array($status, ['Admitted', 'Rejected'])) {
    die('Missing or invalid data');
}

$admissionFile = __DIR__ . '/admissions.json';
$admissions = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];

$found = false;
foreach ($admissions as &$admission) {
    if (isset($admission['id']) && $admission['id'] === $id) {
        $admission['status'] = $status;
        $admission['reviewedBy'] = $_SESSION['user']['username'];
        $admission['reviewedAt'] = date('Y-m-d H:i:s');

        // In-app notification: set lastNotification and time
        $childName = ($admission['childFirstName'] ?? '') . ' ' . ($admission['childSurname'] ?? '');
        $admission['lastNotification'] = "Your child's application ({$childName}) has been {$status}.";
        $admission['notificationAt'] = date('Y-m-d H:i:s');

        $found = true;
        break;
    }
}
unset($admission);

if ($found) {
    file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT));
}

// Redirect back
header('Location: headmaster.php');
exit();
