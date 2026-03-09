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

// Filter only this parent's children
$myChildren = array_filter($children, fn($c) => $c['parent_id'] === $parent['id']);

// Function to compute age from DOB
function calculateAge($dob) {
    try {
        $dobDate = new DateTime($dob);
        $today = new DateTime();
        $diff = $today->diff($dobDate);
        return $diff->y . ' yrs, ' . $diff->m . ' mos';
    } catch (Exception $e) {
        return '-';
    }
}
?>

<div class="container">
    <h2>Admission Status</h2>
    <p>Below is the admission status of your child(ren).</p>

    <?php if (empty($myChildren)): ?>
        <p class="error"><strong>No children admitted yet.</strong></p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Child Full Name</th>
                    <th>Age</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($myChildren as $child): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($child['full_name']); ?></td>
                        <td><?php echo calculateAge($child['dob']); ?></td>
                        <td>
                            <?php
                                $status = strtolower($child['status']);
                                if ($status === 'pending') {
                                    echo '<span style="color:orange;">Pending</span>';
                                } elseif ($status === 'accepted') {
                                    echo '<span style="color:green;">Accepted</span>';
                                } elseif ($status === 'declined') {
                                    echo '<span style="color:red;">Declined</span>';
                                } else {
                                    echo htmlspecialchars($child['status']);
                                }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($child['created_at'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div style="margin-top:20px;">
        <a href="/parent/dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php
require_once __DIR__ . '/../partials/footer.php';
?>
