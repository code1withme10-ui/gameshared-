<?php
session_start();
$data_file = 'users.json'; // The file where all user data is stored

// ---------------- Functions ----------------
function get_users($file) {
    if (!file_exists($file) || filesize($file) == 0) return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function save_users($file, $users) {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

// ---------------- Security Check ----------------
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'headmaster') {
    header('Location: login.php');
    exit;
}

// ---------------- Logout ----------------
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Load users
$users = get_users($data_file);
$message = '';

// Handle Status Action (Admit/Reject)
if (isset($_POST['action'], $_POST['childId'], $_POST['parentId'])) {
    $action = $_POST['action'];
    $childId = $_POST['childId'];
    $parentId = $_POST['parentId'];
    $newStatus = ($action === 'admit') ? 'admitted' : 'rejected';

    foreach ($users as $uIndex => $user) {
        if ($user['id'] === $parentId) {
            foreach ($user['children'] as $cIndex => $child) {
                if ($child['childId'] === $childId) {
                    $users[$uIndex]['children'][$cIndex]['status'] = $newStatus;
                    save_users($data_file, $users);
                    $message = '<p style="color:green;">Child <strong>' . htmlspecialchars($child['name']) . '</strong> status updated to <strong>' . $newStatus . '</strong>.</p>';
                    break 2;
                }
            }
        }
    }
}

// Prepare data for display
$all_children = [];
foreach ($users as $user) {
    if (!empty($user['children'])) {
        foreach ($user['children'] as $child) {
            $child['status'] = strtolower($child['status'] ?? 'pending');
            $child['parentName'] = $user['parentName'];
            $child['parentId'] = $user['id'];
            $child['documents'] = $child['documents'] ?? [];
            $all_children[] = $child;
        }
    }
}

$is_logged_in = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Headmaster Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <span class="navbar-title">Humulani Pre School</span>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="headmaster_dashboard.php?action=logout">Logout</a></li>
        </ul>
    </nav>
</div>

<div class="page-container">
    <h1>Headmaster Dashboard: Student Admissions</h1>
    <?php echo $message; ?>

    <h2>Registered Students</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Child Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Parent Name</th>
                <th>Documents</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($all_children)): ?>
                <tr>
                    <td colspan="7">No children have been registered yet and are awaiting admission.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($all_children as $child): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($child['name']); ?></td>
                        <td><?php echo htmlspecialchars($child['age']); ?></td>
                        <td><?php echo htmlspecialchars($child['gender']); ?></td>
                        <td><?php echo htmlspecialchars($child['parentName']); ?></td>
                        <td>
                            <?php if (!empty($child['documents'])): ?>
                                <?php if (isset($child['documents']['birthCert'])): ?>
                                    <a href="<?php echo htmlspecialchars($child['documents']['birthCert']); ?>" target="_blank">Birth Certificate</a><br>
                                <?php endif; ?>
                                <?php if (isset($child['documents']['parentID'])): ?>
                                    <a href="<?php echo htmlspecialchars($child['documents']['parentID']); ?>" target="_blank">Parent ID</a>
                                <?php endif; ?>
                            <?php else: ?>
                                No documents uploaded
                            <?php endif; ?>
                        </td>
                        <td class="status-<?php echo strtolower($child['status']); ?>">
                            <?php echo htmlspecialchars(ucfirst($child['status'])); ?>
                        </td>
                        <td>
                            <?php if ($child['status'] === 'pending'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="childId" value="<?php echo htmlspecialchars($child['childId']); ?>">
                                    <input type="hidden" name="parentId" value="<?php echo htmlspecialchars($child['parentId']); ?>">
                                    <button type="submit" name="action" value="admit" style="background-color:green;color:white;padding:5px 10px;border:none;border-radius:3px;cursor:pointer;">Admit</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="childId" value="<?php echo htmlspecialchars($child['childId']); ?>">
                                    <input type="hidden" name="parentId" value="<?php echo htmlspecialchars($child['parentId']); ?>">
                                    <button type="submit" name="action" value="reject" style="background-color:red;color:white;padding:5px 10px;border:none;border-radius:3px;cursor:pointer;">Reject</button>
                                </form>
                            <?php else: ?>
                                Status Finalized
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<footer>
    <p>&copy; 2026 Humulani Pre School</p>
</footer>

</body>
</html>
