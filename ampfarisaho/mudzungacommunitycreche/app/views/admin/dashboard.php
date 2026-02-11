<?php
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/navbar.php';
require_once __DIR__ . '/../../services/JsonStorage.php';

session_start();

// Ensure only headmaster can access
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'headmaster') {
    header('Location: /login.php');
    exit;
}

// Load all children
$childrenStorage = new JsonStorage(__DIR__ . '/../../../storage/children.json');
$children = $childrenStorage->read();

// Optional: filter by status via GET
$statusFilter = $_GET['status'] ?? '';
if ($statusFilter) {
    $children = array_filter($children, fn($c) => $c['status'] === $statusFilter);
}

// Load parents to display parent names
$parentsStorage = new JsonStorage(__DIR__ . '/../../../storage/parents.json');
$parents = $parentsStorage->read();

?>

<div class="container" style="margin-top:50px;">
    <h2>Headmaster Dashboard</h2>

    <div style="margin:20px 0;">
        <a href="?status=pending" class="btn btn-warning">Pending</a>
        <a href="?status=accepted" class="btn btn-success">Accepted</a>
        <a href="?status=declined" class="btn btn-danger">Declined</a>
        <a href="/logout.php" class="btn btn-secondary">Logout</a>
    </div>

    <?php if ($children): ?>
        <table class="table" border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Child Name</th>
                    <th>Parent Name</th>
                    <th>Grade</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($children as $child): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($child['full_name']); ?></td>
                        <td>
                            <?php
                                $parentName = '';
                                foreach ($parents as $p) {
                                    if ($p['id'] === $child['parent_id']) {
                                        $parentName = $p['full_name'];
                                        break;
                                    }
                                }
                                echo htmlspecialchars($parentName);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($child['grade']); ?></td>
                        <td><?php echo htmlspecialchars($child['status']); ?></td>
                        <td>
                            <?php if ($child['status'] === 'pending'): ?>
                                <a href="/action.php?id=<?php echo $child['id']; ?>&action=accept" class="btn btn-success">Accept</a>
                                <a href="/action.php?id=<?php echo $child['id']; ?>&action=decline" class="btn btn-danger">Decline</a>
                            <?php else: ?>
                                <span>No actions available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No applications found.</p>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../../partials/footer.php';
?>
