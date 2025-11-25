<link rel="stylesheet" href="style.css">
<?php include 'navbar.php'; ?>

<h1>Admission Form</h1>

<form method="POST" action="submit_admission.php">
    <label>Parent Name:</label><br>
    <input type="text" name="parent_name" required><br><br>

    <label>Child Name:</label><br>
    <input type="text" name="child_name" required><br><br>

    <label>Child Age:</label><br>
    <input type="number" name="age" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <button type="submit">Apply</button>
</form>
