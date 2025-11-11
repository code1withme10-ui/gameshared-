<?php
$hash = '$2y$10$eW5MZ8nJqJ9p3D5Tb1R0tOUyOVwP6yHh0aFzJlVf9BfC9J/3k4Wn2';
$password = 'Head1234!';

$password = 'Head1234!'; // your desired headmaster password
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
echo "<br>";
if (password_verify($password, $hash)) {
    echo "Password matches!";
} else {
    echo "Password does NOT match!";
}
?>

