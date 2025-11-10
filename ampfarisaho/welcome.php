<?php 
// welcome.php
session_start();

// Check if the parent is logged in
if (!isset($_SESSION['parent_name'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Retrieve parent and child's information (from session or database)
$parent_name = $_SESSION['parent_name'];
$child_name = $_SESSION['child_name']; // You can store this data during the admission process

// Simulated progress data for the child (you can replace this with real data from a database)
$progress = [
    'Math' => 'Excellent',
    'Reading' => 'Good',
    'Behavior' => 'Very Good',
    'Art' => 'Satisfactory'
];
?>

<?php include('header.php'); ?>

<div class="content">
    <h1>Welcome, <?php echo $parent_name; ?>!</h1>
    <p>Here’s the progress report for your child, <strong><?php echo $child_name; ?></strong>:</p>

    <h2>Child's Progress</h2>
    <ul>
        <?php foreach ($progress as $subject => $performance): ?>
            <li><strong><?php echo $subject; ?>:</strong> <?php echo $performance; ?></li>
        <?php endforeach; ?>
    </ul>

    <p>If you have any questions about the progress, feel free to contact us!</p>
    <a href="logout.php">Log out</a>
</div>

<?php include('footer.php'); ?>
