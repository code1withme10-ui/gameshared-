<?php
session_start();

// Security: Only allow headmaster role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'headmaster') {
    header('Location: login.php');
    exit();
}

$admissionFile = _DIR_ . '/admissions.json';
$admissions = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Headmaster Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once 'menu-bar.php'; ?>

    <main class="dashboard">
        <h2>Pending Admissions</h2>
        <table>
            <thead>
                <tr>
                    <th>Child</th>
                    <th>Age</th>
                    <th>Parent</th>
                    <th>Contact</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admissions as $admission): ?>
                    <?php if ($admission['status'] === 'Pending'): ?>
                        <tr>
                            <td><?= htmlspecialchars($admission['childFirstName'] . ' ' . $admission['childSurname']) ?></td>
                            <td><?= htmlspecialchars($admission['age']) ?></td>
                            <td><?= htmlspecialchars($admission['parentName']) ?></td>
                            <td><?= htmlspecialchars($admission['parentContact']) ?></td>
                            <td>
                                <form action="update-status.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $admission['id'] ?>">
                                    <input type="hidden" name="status" value="Admitted">
                                    <button type="submit">Admit</button>
                                </form>
                                <form action="update-status.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $admission['id'] ?>">
                                    <input type="hidden" name="status" value="Rejected">
                                    <button type="submit">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
