<?php
session_start();

require_once __DIR__ . '/../../middleware/auth.php';
requireRole('headmaster');

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../../services/JsonStorage.php';

// Load children applications
$childrenStorage = new JsonStorage(__DIR__ . '/../../../storage/children.json');
$children = $childrenStorage->read();

// Optional filter by status
$statusFilter = $_GET['status'] ?? '';
if ($statusFilter) {
    $children = array_filter($children, fn($c) => $c['status'] === $statusFilter);
}

// Load parents to resolve parent names
$parentsStorage = new JsonStorage(__DIR__ . '/../../../storage/parents.json');
$parents = $parentsStorage->read();
?>

<div class="container">
    <h2>Headmaster Dashboard</h2>

    <div style="margin: 20px 0; display:flex; flex-wrap:wrap; gap:10px;">
        <a href="?status=pending" class="btn btn-warning">Pending</a>
        <a href="?status=accepted" class="btn btn-success">Accepted</a>
        <a href="?status=declined" class="btn btn-danger">Declined</a>
        <a href="/logout.php" class="btn btn-secondary">Logout</a>
    </div>

    <?php if (!empty($children)): ?>
        <table class="table">
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
                                $parentName = 'Unknown';
                                foreach ($parents as $parent) {
                                    if ($parent['id'] === $child['parent_id']) {
                                        $parentName = $parent['full_name'];
                                        break;
                                    }
                                }
                                echo htmlspecialchars($parentName);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($child['grade']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($child['status'])); ?></td>
                        <td>
                            <?php if ($child['status'] === 'pending'): ?>
                                <a href="/app/controllers/ChildController.php?id=<?php echo $child['id']; ?>&action=accept" class="btn btn-success btn-sm">Accept</a>
                                <a href="/app/controllers/ChildController.php?id=<?php echo $child['id']; ?>&action=decline" class="btn btn-danger btn-sm">Decline</a>
                            <?php else: ?>
                                <span style="color:#777;">No actions</span>
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
require_once __DIR__ . '/../partials/footer.php';
?>


