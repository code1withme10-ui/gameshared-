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
    // CRITICAL FIX: Redirect to the router-friendly clean URL /headmaster-login
    header('Location: /headmaster-login'); 
    exit();
}
// --- 2. Load Data ---
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
    // Allows getting data like 'child.firstName' or just 'status'
    foreach ($keys as $key) {
        if (strpos($key, '.') !== false) {
            list($parentKey, $childKey) = explode('.', $key);
            if (isset($admission[$parentKey][$childKey])) {
                return htmlspecialchars($admission[$parentKey][$childKey]);
            }
        } elseif (isset($admission[$key])) {
            return htmlspecialchars($admission[$key]);
        }
    }
    return $default;
}

// Ensure $admissions is an array for iteration
if (!is_array($admissions)) {
    $admissions = [];
    $error = "Error: Admissions data is not in a valid format.";
}

// Re-index the admissions to include the ID as a value, if it's currently the key
$admissionsList = [];
foreach ($admissions as $id => $data) {
    // Only proceed if the data is an array
    if (is_array($data)) {
        // Ensure the ID is stored inside the array for easy access
        $data['applicationID'] = $id; 
        $admissionsList[] = $data;
    }
}
// Use $admissionsList for the display loop
$admissions = $admissionsList;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Headmaster Dashboard</title>
    <link rel="stylesheet" href="/public/css/styles.css">
    <style>
        /* Table Styles (Added for better presentation) */
        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .dashboard-table th, .dashboard-table td {
            border: 1px solid #ddd;
            padding: 10px; /* Increased padding */
            text-align: left;
            font-size: 0.9em;
            vertical-align: top;
        }
        .dashboard-table th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
        }
        .status-admitted { color: green; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        .btn-admit, .btn-reject {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 0.85em;
            margin: 2px;
            transition: background-color 0.2s;
        }
        .btn-admit { background-color: #28a745; color: white; }
        .btn-admit:hover { background-color: #1e7e34; }
        .btn-reject { background-color: #dc3545; color: white; }
        .btn-reject:hover { background-color: #bd2130; }
        .document-link {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
        }
    </style>
</head>
<body>

<?php 
// Include the menu bar
require_once "../app/menu-bar.php"; 
?>

<main style="max-width: 1250px; margin: 40px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1);">
    <h1 style="text-align: center; color: #007bff;">Headmaster Dashboard</h1>
    <p style="text-align: center;">Welcome, <?= htmlspecialchars($_SESSION['user']['username'] ?? 'Headmaster') ?>. Manage school admissions.</p>

    <?php if ($error): ?>
        <p style="color: red; text-align: center; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color: green; text-align: center; font-weight: bold;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <h2 style="border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-top: 30px;">Admission Applications (<?= count($admissions) ?> Total)</h2>

    <?php if (empty($admissions)): ?>
        <p style="text-align: center; padding: 20px; border: 1px dashed #ccc;">No admission applications have been submitted yet.</p>
    <?php else: ?>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Child Name</th>
                    <th>Age/Grade</th>
                    <th>Guardian Name (Contact)</th>
                    <th>Application Date</th>
                    <th>Status</th>
                    <th>Child Doc.</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admissions as $admission): 
                    $id = getAdmissionData($admission, ['applicationID']);
                    $status = getAdmissionData($admission, ['status'], 'Pending');
                    
                    // --- FIX: Ensure full names are displayed ---
                    $childFirstName = getAdmissionData($admission, ['child.firstName']);
                    $childSurname = getAdmissionData($admission, ['child.surname']);
                    $childName = "$childFirstName $childSurname";
                    
                    $parentName = getAdmissionData($admission, ['parent.parentName']);
                    $parentSurname = getAdmissionData($admission, ['parent.parentSurname']);
                    $parentContact = getAdmissionData($admission, ['parent.contact']);
                    $guardianDisplay = "$parentName $parentSurname ($parentContact)";
                    
                    $grade = getAdmissionData($admission, ['child.gradeLabel']);
                    $age = getAdmissionData($admission, ['child.age']);
                    $timestamp = getAdmissionData($admission, ['timestamp']);
                    $childDocument = getAdmissionData($admission, ['child.idDocument']);
                    
                    $statusClass = 'status-' . strtolower($status);
                ?>
                    <tr>
                        <td><?= htmlspecialchars(substr($id, 0, 6)) ?>...</td>
                        <td><strong><?= $childName ?></strong></td>
                        <td><?= "$age yrs ($grade)" ?></td>
                        <td><?= $guardianDisplay ?></td>
                        <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($timestamp))) ?></td>
                        <td><span class="<?= $statusClass ?>"><?= $status ?></span></td>
                        
                        <td>
                            <?php if ($childDocument !== 'N/A' && $childDocument !== ''): ?>
                                <a href="/data/uploads/<?= urlencode($childDocument) ?>" target="_blank" class="document-link" title="View Document: <?= $childDocument ?>">
                                    View Document
                                </a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if (strtolower($status) === 'pending'): ?>
                                <form action="/update-status" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="hidden" name="status" value="Admitted">
                                    <button type="submit" class="btn btn-admit">✅ Admit</button>
                                </form>
                                <form action="/update-status" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $id ?>">
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
    if (file_exists('../app/footer.php')) {
        include '../app/footer.php'; 
    }
?>
</body>
</html>