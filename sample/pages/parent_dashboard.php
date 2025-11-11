<?php
session_start();
if (!isset($_SESSION['parent'])) {
  header("Location: ?page=login");
  exit;
}

$parent = $_SESSION['parent'];
include "includes/functions.php";

$children = readData("data/children.json");
$parentChildren = array_filter($children, function($child) use ($parent) {
  return $child['parent_email'] === $parent['email'];
});
?>

<div class="w3-container w3-padding">
  <h2>Welcome, <?php echo htmlspecialchars($parent['name']); ?> 👋</h2>

  <div class="w3-panel w3-card w3-light-grey">
    <h4>Your Profile</h4>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($parent['email']); ?></p>
    <a href="?page=add_child" class="w3-button w3-pink w3-margin-top">Add Child</a>
  </div>

  <h3>Your Children</h3>

  <?php if (count($parentChildren) === 0): ?>
    <p>No children added yet.</p>
  <?php else: ?>
    <ul class="w3-ul w3-card-4">
      <?php foreach ($parentChildren as $child): ?>
        <li>
          <strong><?php echo htmlspecialchars($child['name']); ?></strong> (Age: <?php echo $child['age']; ?>)
          - <a href="?page=reports&child=<?php echo urlencode($child['id']); ?>">View Report</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

