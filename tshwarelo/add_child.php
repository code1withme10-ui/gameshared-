<?php
require_once 'functions.php';
require_parent();

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_child = [
        'parent_id' => $_SESSION['user_id'],
        'name' => $_POST['child_name'],
        'age' => $_POST['child_age'],
        'class' => $_POST['child_class']
    ];
    save_child($new_child);
    $success = "Child registered successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Child</title>
<style>
body { font-family: 'Poppins', sans-serif; background-color: #fcfcfc; margin: 0; }
.container { max-width: 600px; margin: 50px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
h1 { color: #ff9900; text-align: center; }
label { display: block; margin-top: 15px; font-weight: bold; }
input { width: 100%; padding: 10px; margin-top: 5px; border-radius: 6px; border: 1px solid #ccc; }
button { background: #4b0082; color: white; border: none; padding: 12px 25px; border-radius: 30px; margin-top: 20px; cursor: pointer; font-weight: bold; transition: background 0.3s; }
button:hover { background-color: #6a0dad; }
.message { background: #d4edda; color: #155724; padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center; }
a.back-link { display: inline-block; margin-top: 20px; color: #4b0082; text-decoration: none; font-weight: bold; }
a.back-link:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="container">
<h1>Register a Child 🌈</h1>

<?php if ($success): ?>
<div class="message"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST">
<label>Child's Name</label>
<input type="text" name="child_name" required>

<label>Age</label>
<input type="number" name="child_age" min="1" required>

<label>Class</label>
<input type="text" name="child_class" required>

<button type="submit">Register Child</button>
</form>

<a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>
