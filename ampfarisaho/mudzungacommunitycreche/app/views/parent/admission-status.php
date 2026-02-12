<?php
session_start();

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';

/* Security check */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'parent') {
    header("Location: /login.php");
    exit;
}

$parent = $_SESSION['user'];
$childrenFile = __DIR__ . '/../../../storage/children.json';

$children = [];

if (file_exists($childrenFile)) {
    $children = json_decode(file_get_contents($childrenFile), true);
}
?>

<div class="container" style="margin-top:50px;">
    <h2>Admission Status</h2>

    <p>Below is the admission status of your children.</p>

    <?php if (empty($children)): ?>
        <p><strong>No children admitted yet.</strong></p>
    <?php else: ?>

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Child Full Name</th>
                    <th>Age</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($children as $child): ?>
                    <?php if ($child['parent_id'] === $parent['id']): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($child['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($child['age']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($child['status']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($child['submitted_at']); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <br>

    <a href="/parent/dashboard.php" class="btn btn-secondary">
        Back to Dashboard
    </a>
</div>

<?php
require_once __DIR__ . '/../partials/footer.php';
?>
