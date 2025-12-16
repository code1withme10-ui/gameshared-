<?php
// FIXED 1: Use robust check to prevent "session already active" Notice
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Set up error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- 1. Authorization Check ---
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'parent') {
    // CRITICAL FIX: Redirect to the correct file path
    header("Location: login.php");
    exit();
}

// --- 2. Load and Filter Data ---
$admissionFile = __DIR__ . '/../data/admissions.json'; 
$admissions = file_exists($admissionFile)
    ? json_decode(file_get_contents($admissionFile), true)
    : [];

if ($admissions === null) {
    $admissions = [];
}

// Parent identifiers
$parentName = $_SESSION['user']['parentName'] ?? 'Guardian'; // Default to a general name
$parentEmail = $_SESSION['user']['email'] ?? '';
$parentEmailLower = strtolower($parentEmail);

// --- CRITICAL FIX: Introduce a helper function to safely retrieve nested data ---
// This function reliably pulls data using a dot notation (e.g., 'child.firstName')
function getNestedData($data, $keys, $default = 'N/A') {
    $value = $data;
    foreach (explode('.', $keys) as $key) {
        if (isset($value[$key])) {
            $value = $value[$key];
        } else {
            return $default;
        }
    }
    // Return sanitized value
    return htmlspecialchars($value);
}
// --------------------------------------------------------------------------------

// Filter applications for the logged-in parent
$myAdmissions = [];
if (!empty($admissions)) {
    foreach ($admissions as $id => $admission) {
        // Ensure the ID is available for notification dismissal and display
        $admission['applicationID'] = $id;

        // ⭐ FIXED: Strong email matching so parent ONLY sees their own applications
        $appEmail = strtolower(
            getNestedData($admission, 'parent.emailAddress', '')
            ?: getNestedData($admission, 'parentEmail', '') // Fallback to a flat key
        );

        if ($appEmail !== '' && $appEmail === $parentEmailLower) {
            $admission['hasNotification'] = !empty($admission['lastNotification']);
            $myAdmissions[] = $admission;
        }
    }
}

// Check for successful submission redirect
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Notification logic: Find the latest notification
$notificationMessage = null;
$notificationId = null;

usort($myAdmissions, function($a, $b) {
    $timeA = strtotime($a['notificationAt'] ?? '1970-01-01');
    $timeB = strtotime($b['notificationAt'] ?? '1970-01-01');
    return $timeB - $timeA; 
});

if (!empty($myAdmissions) && !empty($myAdmissions[0]['lastNotification'])) {
    $notificationMessage = $myAdmissions[0]['lastNotification'];
    // CRITICAL FIX 2: Ensure we get a valid ID for the form
    $notificationId = $myAdmissions[0]['applicationID'] ?? $myAdmissions[0]['id'] ?? null; 
}

// Re-sort for display (oldest first by timestamp)
usort($myAdmissions, function($a, $b) {
    $timeA = strtotime($a['timestamp'] ?? '1970-01-01');
    $timeB = strtotime($b['timestamp'] ?? '1970-01-01');
    return $timeA - $timeB; 
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardian Portal - SubixStar Pre-School</title>
    <link rel="stylesheet" href="/public/css/styles.css">
    <style>
        .status-Pending { background-color: #ffc107; color: #333; }
        .status-Admitted { background-color: #28a745; color: white; }
        .status-Rejected { background-color: #dc3545; color: white; }
        .status-span {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .notification-banner {
            background-color: #e0f7fa;
            border: 1px solid #00bcd4;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notification-banner p {
            margin: 0;
            font-weight: bold;
        }
        .notification-banner form {
            margin: 0;
        }
        .notification-banner button {
            background-color: #00bcd4;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .notification-banner button:hover {
            background-color: #0097a7;
        }
    </style>
</head>
<body>

<?php require_once "../app/menu-bar.php"; ?>

<main style="max-width: 900px; margin: 40px auto; padding: 20px;">
    <h1 style="text-align: center;">Welcome to the Guardian Portal, <?= htmlspecialchars($parentName) ?></h1>

    <?php if (!empty($success)): ?>
        <p style="color: green; background-color: #e6ffed; padding: 10px; border-radius: 5px; text-align: center;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p style="color: red; background-color: #ffe6e6; padding: 10px; border-radius: 5px; text-align: center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <?php if ($notificationMessage && $notificationId): ?>
        <div class="notification-banner">
            <p>📣 <?= htmlspecialchars($notificationMessage) ?></p>
            <form action="dismiss-notification.php" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($notificationId) ?>">
                <button type="submit">Clear Notification</button>
            </form>
        </div>
    <?php endif; ?>

    <h2>Your Admission Applications</h2>

    <?php if (empty($myAdmissions)): ?>
        <p style="text-align: center; padding: 20px; border: 1px dashed #ccc;">
            You have no submitted admission applications yet.<br>
            Start your application now!
        </p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Child Name</th>
                    <th>Grade</th>
                    <th>Age (Years)</th>
                    <th>Status</th>
                    <th>Date Applied</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($myAdmissions as $a): 
                    $status = getNestedData($a, 'status', 'Pending');
                    $statusClass = 'status-' . str_replace(' ', '', ucfirst($status));
                    $applicationId = $a['applicationID'] ?? $a['id'] ?? null;
                ?>
                    <?php 
                    $children = $a['children'] ?? [];
                    
                    // Display a table row for EACH child in the application
                    foreach ($children as $child): 
                        // Now use $child array for data
                        $childName = htmlspecialchars(($child['firstName'] ?? '') . ' ' . ($child['surname'] ?? ''));
                        $grade = htmlspecialchars($child['gradeApplyingFor'] ?? 'N/A');
                        $age = htmlspecialchars($child['ageInYears'] ?? 'N/A');
                        $displayAge = is_numeric($age) ? round((float)$age, 2) : $age;
                    ?>
                        <tr>
                            <td><?= $childName ?></td>
                            <td><?= $grade ?></td>
                            <td><?= $displayAge ?></td>
                            <td><span class="status-span <?= $statusClass ?>"><?= htmlspecialchars(ucfirst($status)) ?></span></td>
                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($a['timestamp'] ?? 'N/A'))) ?></td>
                            <td>
                                 <?php if (strtolower($status) === 'admitted' && $applicationId): ?>
                                     <a href="#" style="text-decoration: none; color: #4D96FF; font-weight: bold;">Enrollment Details</a>
                                <?php elseif (!empty($a['lastNotification'])): ?>
                                    🔔 New Status!
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <a href="admission" class="button" style="display: block; width: 250px; margin: 20px auto; text-align: center; padding: 10px; background-color: #6BCB77; color: white; text-decoration: none; border-radius: 5px;">Apply for New Admission</a>
</main>

<?php 
if (file_exists('footer.php')) {
    include 'footer.php'; 
}
?>
</body>
</html>