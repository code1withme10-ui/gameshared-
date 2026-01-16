<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'headmaster') {
    header('Location: /headmaster-login'); 
    exit();
}

$admissionFile = __DIR__ . '/../data/admissions.json';
$admissionsRaw = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

function getAdmissionData($admission, $keys, $default = 'N/A') {
    foreach ($keys as $key) {
        if (strpos($key, '.') !== false) {
            list($parentKey, $childKey) = explode('.', $key);
            if (isset($admission[$parentKey][$childKey])) return htmlspecialchars($admission[$parentKey][$childKey]);
        } elseif (isset($admission[$key])) {
            return htmlspecialchars($admission[$key]);
        }
    }
    return $default;
}

$admissions = [];
if (is_array($admissionsRaw)) {
    foreach ($admissionsRaw as $id => $data) {
        if (is_array($data)) {
            $data['applicationID'] = $data['applicationID'] ?? $id; 
            $admissions[] = $data;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Headmaster Dashboard</title>
    <link rel="stylesheet" href="/public/css/styles.css">
    <style>
        .dashboard-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .dashboard-table th, .dashboard-table td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 0.9em; }
        .dashboard-table th { background-color: #f2f2f2; font-weight: bold; }
        .status-admitted { color: green; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        .btn-admit { background-color: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px; }
        .btn-reject { background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>
<?php require_once "../app/menu-bar.php"; ?>
<main style="max-width: 1250px; margin: 40px auto; padding: 20px; background: white; border-radius: 10px;">
    <h1 style="text-align: center; color: #007bff;">Headmaster Dashboard</h1>
    <?php if ($error): ?><p style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color: green; text-align: center;"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <table class="dashboard-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Child Name</th>
                <th>Age/Grade</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admissions as $admission): 
                $id = $admission['applicationID'];
                $timestamp = getAdmissionData($admission, ['timestamp']);

                foreach ($admission['children'] ?? [] as $index => $child): // ADDED $index
                    $childName = htmlspecialchars(($child['firstName'] ?? '') . ' ' . ($child['surname'] ?? ''));
                    // FIX: Check status at the individual child level
                    $childStatus = $child['status'] ?? 'Pending';
            ?>
                <tr>
                    <td><?= htmlspecialchars(substr((string)$id, 0, 8)) ?></td>
                    <td><strong><?= $childName ?></strong></td>
                    <td><?= htmlspecialchars($child['ageInYears'] ?? 'N/A') ?> yrs (<?= htmlspecialchars($child['gradeApplyingFor'] ?? 'N/A') ?>)</td>
                    <td><span class="status-<?= strtolower($childStatus) ?>"><?= ucfirst($childStatus) ?></span></td>
                    <td>
                        <?php if (strtolower($childStatus) === 'pending'): ?>
                            <form action="update-status" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="hidden" name="child_index" value="<?= $index ?>"> <input type="hidden" name="status" value="Admitted">
                                <button type="submit" class="btn btn-admit">✅ Admit</button>
                            </form>
                            <form action="update-status" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="hidden" name="child_index" value="<?= $index ?>"> <input type="hidden" name="status" value="Rejected">
                                <button type="submit" class="btn btn-reject">❌ Reject</button>
                            </form>
                        <?php else: ?>
                            <small>Processed</small>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>