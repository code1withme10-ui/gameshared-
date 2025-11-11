<?php 
// confirmation.php
include('header.php');

// Check if the form data is passed via GET or POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $child_name = htmlspecialchars($_POST['child_name']);  // Sanitize the input
    $parent_name = htmlspecialchars($_POST['parent_name']);
} else {
    // Redirect to the admission page if the user tries to access directly
    header('Location: admission.php');
    exit();
}
?>

<div class="content">
    <h1>Welcome to Happy Kids Creche!</h1>
    <p>Thank you, <?php echo $parent_name; ?>, for registering your child, <?php echo $child_name; ?>!</p>
    <p>Your admission request has been received, and our team will review it shortly. We look forward to having <?php echo $child_name; ?> join us at Happy Kids Creche!</p>
    <p>If you have any questions or concerns, feel free to <a href="contact-us.php">contact us</a>.</p>
</div>

<?php include('footer.php'); ?>
