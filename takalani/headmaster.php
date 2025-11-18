<?php
session_start();
require_once 'functions.php'; // Ensures access to get_admissions()

// 1. Authorization Check (Must be done before any HTML output)
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'headmaster') {
    // This is the line that throws the error if output starts before it.
    header('Location: login.php'); 
    exit();
}

// 2. Load Data
$admissions = get_admissions();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Headmaster Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Re-including the necessary CSS from your original code */
        main.dashboard { max-width:1000px; margin:30px auto; background:#fff; padding:20px; border-radius:8px; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:8px; border-bottom:1px solid #ddd; text-align:left; }
        th { background:#4a90e2; color:#fff; }
        .btn { padding:6px 10px; border-radius:6px; border:none; cursor:pointer; }
    </style>
</head>
<body>
<?php require_once 'menu-bar.php'; ?>
<main class="dashboard">
    <h2>Pending Admissions</h2>
    <?php
      $hasPending = false;
      foreach ($admissions as $a) { if (($a['status'] ?? '') === 'Pending') { $hasPending = true; break; } }
      if (!$hasPending) {
        echo "<p>No pending admissions at the moment.</p>";
      }
    ?>
    <table>
        <thead>
            <tr>
                <th>Child</th>
                <th>Age</th>
                <th>Parent</th>
                <th>Contact</th>
                <th>Submitted At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admissions as $admission): ?>
                <?php if (($admission['status'] ?? '') === 'Pending'): ?>
                    <tr>
                        <td><?= htmlspecialchars($admission['childFirstName'] . ' ' . $admission['childSurname']) ?></td>
                        <td><?= htmlspecialchars($admission['age']) ?></td>
                        <td><?= htmlspecialchars($admission['parentName']) ?></td>
                        <td><?= htmlspecialchars($admission['parentContact']) ?></td>
                        <td><?= htmlspecialchars($admission['timestamp'] ?? '') ?></td>
                        <td>
                            <form action="update-status.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($admission['id']) ?>">
                                <input type="hidden" name="status" value="Admitted">
                                <button type="submit" class="btn" style="background:#28a745;color:#fff;">✅ Admit</button>
                            </form>
                            <form action="update-status.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($admission['id']) ?>">
                                <input type="hidden" name="status" value="Rejected">
                                <button type="submit" class="btn" style="background:#dc3545;color:#fff;">❌ Reject</button>
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