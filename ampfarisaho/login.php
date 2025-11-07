<?php
// login.php
session_start();

// Check if the parent is already logged in
if (isset($_SESSION['parent_name'])) {
    header('Location: welcome.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sample hardcoded login validation
    $valid_parents = [
        'john_doe' => ['password' => '123456', 'child_name' => 'Alice'],
        'jane_smith' => ['password' => 'password', 'child_name' => 'Bob']
    ];

    // Retrieve the submitted login information
    $parent_name = $_POST['parent_name'];
    $password = $_POST['password'];

    // Check if the provided credentials match
    if (isset($valid_parents[$parent_name]) && $valid_parents[$parent_name]['password'] == $password) {
        // Store parent and child info in session
        $_SESSION['parent_name'] = $parent_name;
        $_SESSION['child_name'] = $valid_parents[$parent_name]['child_name'];

        // Redirect to the welcome page
        header('Location: welcome.php');
        exit();
    } else {
        $error_message = "Invalid login credentials.";
    }
}
?>

<?php include('header.php'); ?>

<div class="content">
    <h1>Parent Login</h1>

    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="parent_name">Parent's Username:</label>
        <input type="text" name="parent_name" id="parent_name" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Login">
    </form>
</div>

<?php include('footer.php'); ?>



