<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'parent') {
    header('Location: login.php');
    exit();
}

$admissionFile = __DIR__ . '/admissions.json';
$admissions = file_exists($admissionFile) ? json_decode(file_get_contents($admissionFile), true) : [];

$parentName = $_SESSION['user']['parentName'];
$parentEmail = $_SESSION['user']['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Children - SubixStar Pre-School</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    main { max-width: 900px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { text-align: center; color: #2c3e50; }
    table { width: 100%; border-collapse: collapse; margin-top: 25px; }
    th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
    th { background: #4a90e2; color: white; }
    .status { font-weight: bold; text-transform: capitalize; }
    .Pending  { color: orange; }
    .Admitted { color: green; }
    .Rejected { color: red; }
    .notification { background:#fff3cd; border:1px solid #ffeeba; padding:10px; margin-bottom:12px; border-radius:6px; }
    a.track { color: #4a90e2; text-decoration: none; }
  </style>
</head>
<body>
<?php require_once 'menu-bar.php'; ?>
<main>
  <h2>Welcome, <?= htmlspecialchars($parentName) ?> 👋</h2>
  <p style="text-align:center;">Below are your submitted admission applications.</p>

  <?php
    $foundAny = false;
    foreach ($admissions as $admission) {
        if (strcasecmp($admission['parentName'], $parentName) === 0) {
            $foundAny = true;
            // show notification if exists
            if (!empty($admission['lastNotification'])) {
                echo "<div class='notification'>
                        <strong>Notification:</strong> " . htmlspecialchars($admission['lastNotification']) . "
                        <form method='POST' action='dismiss-notification.php' style='display:inline;margin-left:10px;'>
                          <input type='hidden' name='id' value='" . htmlspecialchars($admission['id']) . "' />
                          <button type='submit' style='background:#6c757d;color:#fff;border:none;padding:6px 8px;border-radius:4px;cursor:pointer;'>Dismiss</button>
                        </form>
                      </div>";
            }
        }
    }

    if (!$foundAny) {
        echo "<p style='text-align:center;'>No admissions found yet. <a href='admission.php'>Submit an application</a></p>";
    }
  ?>

  <table>
    <thead>
      <tr>
        <th>Child Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $found = false;
      foreach ($admissions as $admission):
        if (strcasecmp($admission['parentName'], $parentName) === 0):
          $found = true;
          ?>
          <tr>
            <td><?= htmlspecialchars($admission['childFirstName'] . ' ' . $admission['childSurname']) ?></td>
            <td><?= htmlspecialchars($admission['age']) ?></td>
            <td><?= htmlspecialchars($admission['gender']) ?></td>
            <td class="status <?= htmlspecialchars($admission['status']) ?>">
              <?= htmlspecialchars($admission['status']) ?>
            </td>
            <td>
              <?php if ($admission['status'] === 'Admitted'): ?>
                <a class="track" href="progress.php?id=<?= urlencode($admission['id']) ?>">Track Progress</a>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
          </tr>
          <?php
        endif;
      endforeach;

      if (!$found): ?>
        <tr><td colspan="5" style="text-align:center;">No admissions found yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
