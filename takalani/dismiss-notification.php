<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'parent') {
    header('Location: login.php');
    exit();
}

$id = $_POST['id'] ?? null;
if (!$id) {
    header('Location: parent.php');
    exit();
}

$admissionFile = __DIR__ . '/admissions.json';
$admissions = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];

foreach ($admissions as &$admission) {
    if (isset($admission['id']) && $admission['id'] === $id && strcasecmp($admission['parentName'], $_SESSION['user']['parentName']) === 0) {
        $admission['lastNotification'] = null;
        $admission['notificationAt'] = null;
        break;
    }
}
unset($admission);

file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT));
header('Location: parent.php');
exit();
