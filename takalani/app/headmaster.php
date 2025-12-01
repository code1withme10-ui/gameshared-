<?php
// CRITICAL FIX: Robust session start to prevent PHP Notice
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Set up error reporting for debugging (helpful during development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- 1. Authorization Check ---
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'headmaster') {
    // FIX: Redirect to the file-based login page
    header('Location: headmaster-login.php'); 
    exit();
}

// --- 2. Load Data ---
// FIX: Simplified data path (Fixes redundant '../data/../data/')
$admissionFile = __DIR__ . '/../data/admissions.json';
$admissions = file_exists($admissionFile)
    ? json_decode(file_get_contents($admissionFile), true)
    : [];

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

if ($admissions === null && file_exists($admissionFile)) {
    $error = "Error decoding admissions data. JSON file may be corrupted.";
}

// Function to safely get data, checking nested/flat keys
function getAdmissionData($admission, $keys, $default = 'N/A') {
    foreach ($keys as $key) {
        // Check for nested keys (e.g., child.firstName)
        if (strpos($key, '.') !== false) {
            list($parentKey, $childKey) = explode('.', $key);
            if (isset($admission[$parentKey][$childKey]) && $admission[$parentKey][$childKey] !== '') {
                return $admission[$parentKey][$childKey];
            }
        } 
        // Check for flat keys (e.g., applicationID)
        else {
            if (isset($admission[$key]) && $admission[$key] !== '') {
                return $admission[$key];
            }
        }
    }
    return $default;
}

// Display applications in reverse chronological order
if (!empty($admissions)) {
    usort($admissions, function($a, $b) {
        $timeA = strtotime($a['timestamp'] ?? '1970-01-01');
        $timeB = strtotime($b['timestamp'] ?? '1970-01-01');
        return $timeB - $timeA;
    });
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Headmaster Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php 
// FIX: Corrected mismatched quote
require_once "../app/menu-bar.php"; 
?>

<main>
    <h2 style="text-align:center;">Headmaster Dashboard: Admissions Overview</h2>

    <?php if ($error): ?>
        <p style="color:red; text-align:center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green; text-align:center;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if (empty($admissions)): ?>
        <p style="text-align:center;">There are no admission applications yet.</p>
    <?php else: ?>
        <table class="application-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Child Name</th>
                    <th>Grade</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admissions as $admission): 
                    $id = getAdmissionData($admission, ['applicationID', 'id']);
                    $childName = getAdmissionData($admission, ['childFirstName', 'child.firstName']) . ' ' . getAdmissionData($admission, ['childSurname', 'child.surname']);
                    $grade = getAdmissionData($admission, ['gradeApplyingFor']);
                    $date = date('Y-m-d', strtotime(getAdmissionData($admission, ['timestamp'])));
                    $status = getAdmissionData($admission, ['status'], 'Pending');

                    $statusClass = strtolower($status) === 'admitted' ? 'status-admitted' : 
                                   (strtolower($status) === 'rejected' ? 'status-rejected' : 'status-pending');
                ?>
                    <tr>
                        <td><?= htmlspecialchars($id) ?></td>
                        <td><?= htmlspecialchars($childName) ?></td>
                        <td><?= htmlspecialchars($grade) ?></td>
                        <td><?= htmlspecialchars($date) ?></td>
                        <td><span class="<?= $statusClass ?>"><?= htmlspecialchars(ucfirst($status)) ?></span></td>
                        <td>
                            <?php if (strtolower($status) === 'pending'): ?>
                                <form action="update-status.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                                    <input type="hidden" name="status" value="Admitted">
                                    <button type="submit" class="btn btn-admit">✅ Admit</button>
                                </form>
                                <form action="update-status.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                                    <input type="hidden" name="status" value="Rejected">
                                    <button type="submit" class="btn btn-reject">❌ Reject</button>
                                </form>
                            <?php else: ?>
                                <span style="color:#555;"><?= htmlspecialchars(ucfirst($status)) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
<?php 
    // Include footer.php only if it exists
    if (file_exists('footer.php')) {
        include 'footer.php'; 
    }
?>
</body>
</html>