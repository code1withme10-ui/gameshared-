<?php
session_start();
$data_file = 'users.json'; // The file where all user data is stored

// ----------------------------------------------------
// Functions to read/write JSON data
// ----------------------------------------------------
function get_users($file) {
    if (!file_exists($file) || filesize($file) == 0) {
        return [];
    }
    
    $data = json_decode(file_get_contents($file), true);
    if (!is_array($data)) {
        return [];
    }
    return $data;
}

function save_users($file, $users) {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}
// ----------------------------------------------------

// --- Security Check: Ensure user is logged in AND is the Headmaster ---
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'headmaster') {
    header('Location: login.php');
    exit;
}

// --- HANDLE LOGOUT LOGIC ---
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}
// ----------------------------------------------------

$users = get_users($data_file);
$message = '';

// --- Handle Status Action (Admitting/Rejecting) ---
if (isset($_POST['action'], $_POST['childId'], $_POST['parentId'])) {
    $action = $_POST['action']; // 'admit' or 'reject'
    $childId = $_POST['childId'];
    $parentId = $_POST['parentId'];
    $newStatus = ($action === 'admit') ? 'admitted' : 'rejected';

    // Find and update the user/child status
    foreach ($users as $userIndex => $user) {
        if ($user['id'] === $parentId) {
            foreach ($user['children'] as $childIndex => $child) {
                if ($child['childId'] === $childId) {
                    $users[$userIndex]['children'][$childIndex]['status'] = $newStatus;
                    save_users($data_file, $users);
                    $message = '<p style="color: green;">Child **' . htmlspecialchars($child['name']) . '** status updated to **' . $newStatus . '**. Parent notified.</p>';
                    break 2; // Break out of both loops
                }
            }
        }
    }
}

// --- Prepare Data for Display ---
$all_children = [];
foreach ($users as $user) {
    // Only process users who have children registered
    if (isset($user['children']) && is_array($user['children'])) {
        foreach ($user['children'] as $child) {
            // Only show children that have a status (i.e., they were registered via the form)
            if (isset($child['status'])) { 
                $child['parentName'] = $user['parentName'];
                $child['parentId'] = $user['id'];
                $all_children[] = $child;
            }
        }
    }
}

// PHP logic to check login status for the navbar
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
    <!-- Navigation -->
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
            
            <?php if ($is_logged_in): ?>
                <a href="headmaster_dashboard.php?action=logout">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="page-container">
        <h1>Headmaster Dashboard: Student Admissions</h1>
        
        <?php echo $message; ?>

        <h2>Registered Students</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Child Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Parent Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($all_children)): ?>
                    <tr>
                        <td colspan="6">No children have been registered yet and are awaiting admission.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($all_children as $child): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($child['name']); ?></td>
                        <td><?php echo htmlspecialchars($child['age']); ?></td>
                        <td><?php echo htmlspecialchars($child['gender']); ?></td>
                        <td><?php echo htmlspecialchars($child['parentName']); ?></td>
                        <td class="status-<?php echo strtolower($child['status'] ?? 'pending'); ?>">
                            <?php echo htmlspecialchars(ucfirst($child['status'] ?? 'Pending')); ?>
                        </td>
                        <td>
                            <?php if (($child['status'] ?? 'pending') === 'pending'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="childId" value="<?php echo htmlspecialchars($child['childId']); ?>">
                                    <input type="hidden" name="parentId" value="<?php echo htmlspecialchars($child['parentId']); ?>">
                                    <button type="submit" name="action" value="admit" style="background-color: green; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">Admit</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="childId" value="<?php echo htmlspecialchars($child['childId']); ?>">
                                    <input type="hidden" name="parentId" value="<?php echo htmlspecialchars($child['parentId']); ?>">
                                    <button type="submit" name="action" value="reject" style="background-color: red; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">Reject</button>
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
    </div>
</body>
</html>