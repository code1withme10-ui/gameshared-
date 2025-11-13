<?php session_start();
// ✅ Only allow parents to access
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}

// ✅ Get child ID from the query string
$childId = $_GET['id'] ?? null;
if (!$childId) {
  die('Child ID is required.');
}

// ✅ Load admission data
$admissionFile = __DIR__ . '/admissions.json';
$admissions = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];

// ✅ Find the specific child record
$childData = null;
foreach ($admissions as $admission) {
  if ($admission['id'] === $childId && strcasecmp($admission['parentName'], $_SESSION['user']['parentName']) === 0) {
    $childData = $admission;
    break;
  }
}

if (!$childData) {
  die('Child not found or you do not have permission to view this record.');
}

// ✅ Example progress (for demo — later can be stored in a progress.json file)
$progressData = [
  'attendance'   => '95%',
  'behavior'     => 'Excellent',
  'learning'     => 'Recognizes colors, shapes, and can count to 20.',
  'teacherNotes' => 'Shows great enthusiasm during group activities and storytelling.'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Child Progress - SubixStar Pre-School</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    main { max-width: 700px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #2c3e50; text-align: center; }
    .info { background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    .info p { margin: 8px 0; }
    a.button { display: inline-block; margin-top: 20px; background: #4a90e2; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; }
    a.button:hover { background: #357abd; }
  </style>
</head>
<body>
<?php require_once 'menu-bar.php'; ?>

<main>
  <h2>Progress Report</h2>
  <div class="info">
    <p><strong>Child Name:</strong> <?= htmlspecialchars($childData['childFirstName'] . ' ' . $childData['childSurname']) ?></p>
    <p><strong>Age:</strong> <?= htmlspecialchars($childData['age']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($childData['status']) ?></p>
  </div>

  <h3>Learning Progress</h3>
  <p><strong>Attendance:</strong> <?= htmlspecialchars($progressData['attendance']) ?></p>
  <p><strong>Behavior:</strong> <?= htmlspecialchars($progressData['behavior']) ?></p>
  <p><strong>Learning:</strong> <?= htmlspecialchars($progressData['learning']) ?></p>
  <p><strong>Teacher Notes:</strong> <?= htmlspecialchars($progressData['teacherNotes']) ?></p>

  <a href="parent.php" class="button">← Back to My Children</a>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
