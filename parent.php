<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
$admissionFile) = _DIR_ . '/admissions.json';
$admissions = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];

// Find the child's admission record
$childStatus = null;
foreach ($admissions as $admission) {
    // Match by child's unique ID stored in session (or fallback to parentContact)
    if ($admission['parentContact'] === $user['parentContact']) {
        $childStatus = $admission['status']; // Pending, Admitted, Rejected
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parent Dashboard - SubixStar Pre-School</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Same inline backup styling as before */
        .dashboard { max-width: 800px; margin: 50px auto; text-align: center; background: #fff; padding: 20px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .child-info, .progress-section { margin-top: 20px; background: #f9fafc; padding: 15px; border-radius: 10px; border: 1px solid #eee; }
        .child-info h3, .progress-section h3 { color: #333; }
        .button { background: linear-gradient(90deg, #ff6b6b, #feca57, #1dd1a1, #54a0ff, #5f27cd); color: white; padding: 10px 20px; border: none; border-radius: 25px; text-decoration: none; font-weight: bold; transition: all 0.3s ease; }
        .button:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <?php require_once 'menu-bar.php'; ?>

    <main class="dashboard">
        <h2>Welcome, <?= htmlspecialchars($user["parentName"]) ?> 👋</h2>

        <section class="child-info">
            <h3>Child Details</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($user["childName"]) ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($user["childAge"]) ?> years</p>
            <p><strong>Status:</strong> 
                <?php
                    if ($childStatus) {
                        echo htmlspecialchars(ucfirst($childStatus));
                    } else {
                        echo "No record found";
                    }
                ?>
            </p>
        </section>

        <?php if ($childStatus === 'Admitted'): ?>
        <section class="progress-section">
            <h3>Child Progress</h3>
            <p>Track your child's activities, reports, and growth.</p>
            <a href="progress.php?childId=<?= htmlspecialchars($user['"progress.php?childId=<?= htmlspecialchars($user['childId']) ?>"']) ?>" class="button">View Progress</a>
        </section>
        <?php endif; ?>

        <p><a href="logout.php" class="button">Logout</a></p>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
