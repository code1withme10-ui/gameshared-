<?php
require_once __DIR__ . '/../../middleware/auth.php';
requireRole('parent');

require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/navbar.php';
require_once __DIR__ . '/../../services/JsonStorage.php';

$parent = $_SESSION['user'];
$parentId = $parent['id'];

$childrenStorage = new JsonStorage(__DIR__ . '/../../../storage/children.json');
$children = $childrenStorage->read();

// Only this parent’s children
$myChildren = array_filter($children, function ($child) use ($parentId) {
    return $child['parent_id'] === $parentId;
});
?>

<div class="container" style="margin-top:50px;">
    <h2>Welcome, <?php echo htmlspecialchars($parent['full_name']); ?>!</h2>

    <div style="margin:20px 0;">
        <a href="/app/views/parent/admit-child.php" class="btn btn-primary">
            <?php echo empty($myChildren) ? 'Admit First Child' : 'Add Another Child'; ?>
        </a>
        <a href="/logout.php" class="btn btn-secondary">Logout</a>
    </div>

    <h3>Your Child(ren) Admission Status</h3>

    <?php if (!empty($myChildren)): ?>
        <table class="table" border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Child Name</th>
                    <th>Date of Birth</th>
                    <th>Grade Applying For</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($myChildren as $child): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($child['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($child['dob']); ?></td>
                        <td><?php echo htmlspecialchars($child['grade']); ?></td>
                        <td>
                            <?php
                                if ($child['status'] === 'pending') {
                                    echo '<span style="color:orange;">Pending</span>';
                                } elseif ($child['status'] === 'accepted') {
                                    echo '<span style="color:green;">Accepted</span>';
                                } else {
                                    echo '<span style="color:red;">Declined</span>';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not admitted any child yet.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
