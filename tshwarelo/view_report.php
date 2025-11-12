<?php
require_once 'functions.php';
require_login();

$child_id = $_GET['child_id'] ?? '';
$user = current_user();
$child = null;
foreach ($user['children'] as $c) {
    if ($c['id'] === $child_id) { $child = $c; break; }
}
if (!$child) {
    echo "Child not found."; exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Report - <?=htmlspecialchars($child['name'])?></title></head>
<body>
<?php include 'menu-bar.php'; ?>
<main>
  <h2>Report for <?=htmlspecialchars($child['name'])?></h2>
  <?php if (empty($child['reports'])): ?>
    <p>No reports yet for this child.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($child['reports'] as $r) : ?>
        <li><?=htmlspecialchars($r['date'])?>: <?=htmlspecialchars($r['note'])?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
  <p><a href="dashboard.php">Back to Dashboard</a></p>
</main>
</body>
</html>
