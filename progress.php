<?php
session_start();

// Security check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'parent') {
    header('Location: login.php');
    exit();
}

// Get child ID from query parameter
$childId = $_GET['childId'] ?? null;

if (!$childId) {
    die('Child ID is required.');
}

// Fetch child details and progress data (e.g., from JSON or database)
// Example:
// $childData = getChildData($childId);

// Dummy data for demo
$childData = [
    'name' => 'John Doe',
    'progress' => [
        'attendance' => '95%',
        'behavior' => 'Excellent',
        'notes' => 'Shows great enthusiasm in class.'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Child Progress - SubixStar Pre-School</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once 'menu-bar.php'; ?>

    <main class="dashboard">
        <h2>Progress for <?= htmlspecialchars($childData['name']) ?></h2>
        <p><strong>Attendance:</strong> <?= htmlspecialchars($childData['progress']['attendance']) ?></p>
        <p><strong>Behavior:</strong> <?= htmlspecialchars($childData['progress']['behavior']) ?></p>
        <p><strong>Notes:</strong> <?= htmlspecialchars($childData['progress']['notes']) ?></p>
        <a href="parent.php" class="button">Back to Dashboard</a>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
