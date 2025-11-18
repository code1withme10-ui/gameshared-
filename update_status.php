<?php
$id = $_GET['id'];
$status = $_GET['status'];

$admissions = json_decode(file_get_contents('admissions.json'), true);

$admissions[$id]['status'] = $status;

file_put_contents('admissions.json', json_encode($admissions, JSON_PRETTY_PRINT));

header("Location: dashboard.php");
exit;
?>
