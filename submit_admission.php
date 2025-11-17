<?php

$data = [
    'parent_name' => $_POST['parent_name'],
    'child_name' => $_POST['child_name'],
    'age' => $_POST['age'],
    'email' => $_POST['email'],
    'status' => 'pending'
];

$existing = [];

if (file_exists('admissions.json')) {
    $existing = json_decode(file_get_contents('admissions.json'), true);
}

$existing[] = $data;

file_put_contents('admissions.json', json_encode($existing, JSON_PRETTY_PRINT));

echo "Application Submitted!";
?>
