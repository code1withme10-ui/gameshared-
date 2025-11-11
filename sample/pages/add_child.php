<?php
session_start();
if (!isset($_SESSION['parent'])) {
  header("Location: ?page=login");
  exit;
}

include "includes/functions.php";

$filename = "data/children.json";
$children = readData($filename);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newChild = [
        "id" => uniqid(),
        "parent_email" => $_SESSION['parent']['email'],
        "name" => trim($_POST['child_name']),
        "age" => trim($_POST['child_age']),
        "gender" => trim($_POST['gender'])
    ];

    $children[] = $newChild;
    writeData($filename, $children);
    $message = "Child added successfully!";
}
?>

<div class="w3-container w3-padding">
  <h2>Add Child</h2>

  <?php if ($message): ?>
    <div class="w3-panel w3-pale-green w3-border">
      <p><?php echo $message; ?></p>
    </div>
  <?php endif; ?>

  <form class="w3-container w3-card w3-padding" method="post">
    <label>Child Name</label>
    <input class="w3-input w3-border" type="text" name="child_name" required>

    <label>Age</label>
    <input class="w3-input w3-border" type="number" name="child_age" required>

    <label>Gender</label>
    <select class="w3-select w3-border" name="gender" required>
      <option value="" disabled selected>Choose gender</option>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
    </select>

    <button class="w3-button w3-pink w3-margin-top" type="submit">Add Child</button>
  </form>

  <p><a href="?page=parent_dashboard" class="w3-button w3-light-grey w3-margin-top">Back to Dashboard</a></p>
</div>

