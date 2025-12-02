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
// FIXED: Simplified the data path (Removed redundant '../data/../data/')
$admissionFile = __DIR__ . '/../data/admissions.json'; 
$admissions = file_exists($admissionFile)
    ? json_decode(file_get_contents($admissionFile), true)
    : [];

if ($admissions === null) {
    $admissions = [];
}

// Parent identifiers
$parentName = $_SESSION['user']['parentName'] ?? '';
$parentEmail = $_SESSION['user']['email'] ?? '';
$parentEmailLower = strtolower($parentEmail);
$childAge = $_SESSION['user']['childAge'] ?? 'N/A';
$childName = $_SESSION['user']['childName'] ?? 'N/A'; // For welcome message

// Filter applications for the logged-in parent
$myAdmissions = [];
if (!empty($admissions)) {
    foreach ($admissions as $admission) {
        // Match using the unique parent email and ensure all possible keys are checked
        $appEmail = $admission['parentEmail'] ?? ($admission['parent']['emailAddress'] ?? null);
        
        if ($appEmail !== null && strtolower($appEmail) === $parentEmailLower) {
            // Check for new notification (for display purposes)
            $admission['hasNotification'] = !empty($admission['lastNotification']);
            $myAdmissions[] = $admission; // Add the application to the list
        }
    }
}

// Check for successful submission redirect
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Check if a notification needs to be displayed and provide a dismiss button
$notificationMessage = null;
$notificationId = null;

// Find the *most recent* notification to display
// Sort applications by notification time in descending order (most recent first)
usort($myAdmissions, function($a, $b) {
    $timeA = strtotime($a['notificationAt'] ?? '1970-01-01');
    $timeB = strtotime($b['notificationAt'] ?? '1970-01-01');
    return $timeB - $timeA; 
});

if (!empty($myAdmissions) && !empty($myAdmissions[0]['lastNotification'])) {
    $notificationMessage = $myAdmissions[0]['lastNotification'];
    // Use applicationID or 'id' for dismissal
    $notificationId = $myAdmissions[0]['applicationID'] ?? $myAdmissions[0]['id'] ?? null; 
}

// Re-sort applications chronologically by submission timestamp (oldest first for display)
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
    <link rel="stylesheet" href="public/css/styles.css">
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

<?php 
// FIX: Changed mismatched quote to double quote: "../app/menu-bar.php"
require_once "../app/menu-bar.php"; 
?>

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
                    <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Child Name</th>
                    <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Grade</th>
                    <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Age (Years)</th>
                    <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Status</th>
                    <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Date Applied</th>
                    <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($myAdmissions as $a): 
                    $status = $a['status'] ?? 'Pending';
                    $statusClass = 'status-' . str_replace(' ', '', ucfirst($status));
                    $applicationId = $a['applicationID'] ?? $a['id'] ?? null;
                    
                    // CRITICAL FIX: Safely retrieve child name from flat keys used in admission.php
                    // Check top level first (for single child form), then check nested 'child', then nested 'children'[0] (for multi-child form)
                    $firstName = $a['childFirstName'] ?? ($a['child']['firstName'] ?? ($a['children'][0]['firstName'] ?? ''));
                    $surname = $a['childSurname'] ?? ($a['child']['surname'] ?? ($a['children'][0]['surname'] ?? ''));
                    
                    $childName = trim($firstName . ' ' . $surname) ?: 'N/A';
                    
                    // Safely get grade
                    $grade = $a['gradeApplyingFor'] ?? ($a['child']['gradeApplyingFor'] ?? ($a['children'][0]['gradeApplyingFor'] ?? 'N/A'));
                    
                    // Safely get age
                    $age = round($a['age'] ?? ($a['child']['ageInYears'] ?? ($a['children'][0]['ageInYears'] ?? 0)), 2);
                ?>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 10px;"><?= htmlspecialchars($childName) ?></td>
                        <td style="border: 1px solid #ddd; padding: 10px;"><?= htmlspecialchars($grade) ?></td>
                        <td style="border: 1px solid #ddd; padding: 10px;"><?= htmlspecialchars($age) ?></td>
                        <td style="border: 1px solid #ddd; padding: 10px;"><span class="status-span <?= $statusClass ?>"><?= htmlspecialchars(ucfirst($status)) ?></span></td>
                        <td style="border: 1px solid #ddd; padding: 10px;"><?= htmlspecialchars(date('Y-m-d', strtotime($a['timestamp'] ?? 'N/A'))) ?></td>
                        <td style="border: 1px solid #ddd; padding: 10px;">
                             <?php if (strtolower($status) === 'admitted' && $applicationId): ?>
                                 <a href="#" style="text-decoration: none; color: #4D96FF; font-weight: bold;">Enrollment Details</a>
                            <?php elseif (!empty($a['lastNotification'])): ?>
                                🔔 **New Status!**
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <a href="admission.php" class="button" style="display: block; width: 250px; margin: 20px auto; text-align: center; padding: 10px; background-color: #6BCB77; color: white; text-decoration: none; border-radius: 5px;">Apply for New Admission</a>
</main>

<?php 
    // Include footer.php only if it exists
    if (file_exists('footer.php')) {
        include 'footer.php'; 
    }
?>
</body>
</html>