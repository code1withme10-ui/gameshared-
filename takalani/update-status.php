<?php
session_start();

// ✅ Security: Only the headmaster can update admissions
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'headmaster') {
    die('Unauthorized');
}

// ✅ Must be a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

$id     = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

// ✅ Validate inputs
if (!$id || !in_array($status, ['Admitted', 'Rejected'])) {
    die('Missing or invalid data');
}

// ✅ Corrected file path
$admissionFile = _DIR_ . '/admissions.json';
$admissions = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];

foreach ($admissions as &$admission) {
    if ($admission['id'] == $id) {
        $admission['status'] = $status;
        $admission['reviewedBy'] = $_SESSION['user']['username'];
        $admission['reviewedAt'] = date('Y-m-d H:i:s');

        // ✅ Include helper for email notification
        require_once 'send-email.php';

        // ✅ Call the email sending function
        if (!empty($admission['parentEmail'])) {
            $emailSent = sendAdmissionStatusEmail(
                $admission['parentEmail'],
                $admission['childFirstName'] ?? '',
                $status
            );

            if ($emailSent) {
                echo "Email notification sent successfully.";
            } else {
                echo "Failed to send email notification.";
            }
        }
        break;
    }
}

// ✅ Save updates back to file
file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT));

// ✅ Redirect back to dashboard after processing
header('Location: headmaster.php');
exit();
?>