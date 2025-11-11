<?php include('header.php'); ?>

<div class="content">
    <h1>Admission Form</h1>
    <form action="confirmation.php" method="POST">
        <label for="child_name">Child's Name:</label>
        <input type="text" name="child_name" id="child_name" required>

        <label for="age">Child's Age:</label>
        <input type="number" name="age" id="age" required>

        <label for="parent_name">Parent's Name:</label>
        <input type="text" name="parent_name" id="parent_name" required>

        <input type="submit" value="Submit">
    </form>
</div>

<?php include('footer.php'); ?>

