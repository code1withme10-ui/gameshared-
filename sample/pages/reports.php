<?php
session_start();
if (!isset($_SESSION['parent'])) {
  header("Location: ?page=login");
  exit;
}

include "includes/functions.php";

$childId = $_GET['child'] ?? '';
$children = readData("data/children.json");
$reports = readData("data/reports.json");

$child = null;
foreach ($children as $c) {
  if ($c['id'] === $childId) {
    $child = $c;
    break;
  }
}

if (!$child) {
  echo "<div class='w3-container w3-padding'><p>Child not found.</p></div>";
  exit;
}

$childReports = array_filter($reports, function($r) use ($childId) {
  return $r['child_id'] === $childId;
});
?>

<div class="w3-container w3-padding">
  <h2><?php echo htmlspecialchars($child['name']); ?>'s Reports</h2>

  <?php if (count($childReports) === 0): ?>
    <p>No reports yet.</p>
  <?php else: ?>
    <?php foreach ($childReports as $r): ?>
      <div class="w3-panel w3-card w3-light-grey">
        <h4><?php echo htmlspecialchars($r['title']); ?></h4>
        <p><strong>Date:</strong> <?php echo $r['date']; ?></p>
        <p><?php echo htmlspecialchars($r['comments']); ?></p>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <a href="?page=parent_dashboard" class="w3-button w3-light-grey w3-margin-top">Back to Dashboard</a>
</div>

