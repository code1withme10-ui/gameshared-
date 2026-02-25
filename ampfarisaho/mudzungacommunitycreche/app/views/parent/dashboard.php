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

// Filter only this parent's children
$myChildren = array_filter($children, fn($child) => $child['parent_id'] === $parentId);
?>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($parent['full_name']); ?>!</h2>

    <div style="margin: 20px 0; display:flex; flex-wrap:wrap; gap:10px;">
        <a href="/app/views/parent/admit-child.php" class="btn btn-primary">
            <?php echo empty($myChildren) ? 'Admit First Child' : 'Add Another Child'; ?>
        </a>
        <a href="/logout.php" class="btn btn-secondary">Logout</a>
    </div>

    <h3>Your Child(ren) Admission Status</h3>

    <?php if (!empty($myChildren)): ?>
        <table class="table">
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
                                switch ($child['status']) {
                                    case 'pending':
                                        echo '<span style="color:orange; font-weight:bold;">Pending</span>';
                                        break;
                                    case 'accepted':
                                        echo '<span style="color:green; font-weight:bold;">Accepted</span>';
                                        break;
                                    case 'declined':
                                        echo '<span style="color:red; font-weight:bold;">Declined</span>';
                                        break;
                                    default:
                                        echo '<span>' . htmlspecialchars($child['status']) . '</span>';
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
