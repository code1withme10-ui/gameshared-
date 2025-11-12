<?php
session_start();

// Security check
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

$admissionFile = _DIR_ . '/admissions.json';
$admissions = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];

foreach ($admissions as &$admission) {
    if ($admission['id'] === $id) {
        $admission['status'] = $status;

        // Include helper function
        require_once 'send-email.php';

        // Call function
        $emailSent = sendAdmissionStatusEmail($admission['parentEmail'], $admission['childFirstName'], $status);

        if ($emailSent) {
            echo "Email notification sent successfully.";
        } else {
            echo "Failed to send email notification.";
        }

        break;
    }
}

file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT));

header('Location: headmaster.php');
exit();
?>
