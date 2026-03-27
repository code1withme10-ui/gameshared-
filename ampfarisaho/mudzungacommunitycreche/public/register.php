<?php
session_start();

require_once __DIR__ . '/../app/services/JsonStorage.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($fullName === '') {
        $errors[] = 'Full name is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email address is required.';
    }

    if ($phone === '' || !preg_match('/^[0-9]{10,15}$/', $phone)) {
        $errors[] = 'Valid phone number is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    // STRONG PASSWORD CHECK
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W]).{8,}$/', $password)) {
        $errors[] = 'Password must contain uppercase, lowercase, number and special character (8+ characters).';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    $parentsStorage = new JsonStorage(__DIR__ . '/../storage/parents.json');
    $parents = $parentsStorage->read();

    foreach ($parents as $parent) {
        if ($parent['email'] === $email) {
            $errors[] = 'Email is already registered.';
            break;
        }
    }

    if (empty($errors)) {

        $parents[] = [
            'id' => uniqid('parent_'),
            'full_name' => htmlspecialchars($fullName),
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'parent',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $parentsStorage->write($parents);

        $success = 'Registration successful! You can now login.';
    }
}

require_once __DIR__ . '/../app/views/partials/header.php';
require_once __DIR__ . '/../app/views/partials/navbar.php';
?>

<div class="container">
<div class="login-card">

<h2>Parent Registration</h2>

<?php if (!empty($errors)): ?>
<?php foreach ($errors as $error): ?>
<p class="error"><?php echo htmlspecialchars($error); ?></p>
<?php endforeach; ?>
<?php endif; ?>

<?php if ($success): ?>

<p class="success"><?php echo htmlspecialchars($success); ?></p>

<a href="/login.php" class="btn btn-primary">Go to Login</a>

<?php else: ?>

<form method="POST">

<div class="form-group">
<label>Full Name <span style="color:red;">*</span></label>
<input type="text" name="full_name" required>
</div>

<div class="form-group">
<label>Email Address <span style="color:red;">*</span></label>
<input type="email" name="email" required>
</div>

<div class="form-group">
<label>Phone Number <span style="color:red;">*</span></label>
<input type="text" name="phone" placeholder="Example: 0712345678" required>
</div>

<div class="form-group">
<label>Password <span style="color:red;">*</span></label>

<div style="position:relative;">
<input type="password" name="password" id="password" required onkeyup="checkStrength()">

<span onclick="togglePassword()" style="position:absolute;right:10px;top:8px;cursor:pointer;">👁</span>
</div>

<div style="width:100%;background:#ddd;height:8px;margin-top:5px;border-radius:5px;">
<div id="strengthBar" style="height:8px;width:0%;background:red;border-radius:5px;"></div>
</div>

<p id="strengthText" style="font-size:13px;margin-top:5px;"></p>

</div>

<div class="form-group">
<label>Confirm Password <span style="color:red;">*</span></label>
<input type="password" name="confirm_password" required>
</div>

<button type="submit" class="btn btn-primary">Register</button>

</form>

<p style="margin-top:15px; text-align:center;">
Already have an account?
<a href="/login.php" style="color:#09223b; font-weight:bold;">Login here</a>
</p>

<?php endif; ?>

</div>
</div>

<script>

function togglePassword(){

let password = document.getElementById("password");

if(password.type === "password"){
password.type = "text";
}else{
password.type = "password";
}

}

function checkStrength(){

let password = document.getElementById("password").value;
let bar = document.getElementById("strengthBar");
let text = document.getElementById("strengthText");

let strength = 0;

if(password.length >= 8){ strength++; }
if(password.match(/[a-z]/)){ strength++; }
if(password.match(/[A-Z]/)){ strength++; }
if(password.match(/[0-9]/)){ strength++; }
if(password.match(/[^a-zA-Z0-9]/)){ strength++; }

if(strength <= 2){
bar.style.width = "33%";
bar.style.background = "red";
text.innerHTML = "Weak password";
}

else if(strength <=4){
bar.style.width = "66%";
bar.style.background = "orange";
text.innerHTML = "Medium password";
}

else{
bar.style.width = "100%";
bar.style.background = "green";
text.innerHTML = "Strong password";
}

}

</script>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
