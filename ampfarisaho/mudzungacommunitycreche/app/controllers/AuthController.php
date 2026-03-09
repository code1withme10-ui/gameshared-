<?php
require_once __DIR__ . '/../services/JsonStorage.php';
require_once __DIR__ . '/../middleware/auth.php'; // use our updated auth.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /login.php');
    exit;
}

// Clean input
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validate
if ($email === '' || $password === '') {
    header('Location: /login.php?error=empty');
    exit;
}

// Load users and parents
$usersStorage   = new JsonStorage(__DIR__ . '/../../storage/users.json');
$parentsStorage = new JsonStorage(__DIR__ . '/../../storage/parents.json');

$users   = $usersStorage->read();
$parents = $parentsStorage->read();

// Merge all users
$allUsers = array_merge($users, $parents);

$userFound = null;
foreach ($allUsers as $user) {
    if ($user['email'] === $email && password_verify($password, $user['password'])) {
        $userFound = $user;
        break;
    }
}

// Authentication
if (!$userFound) {
    header('Location: /login.php?error=invalid');
    exit;
}

// Start session and store only necessary info
session_start();
$_SESSION['user'] = [
    'id'        => $userFound['id'],
    'full_name' => $userFound['full_name'],
    'email'     => $userFound['email'],
    'role'      => $userFound['role'] ?? 'parent'
];

// Redirect based on role
if ($_SESSION['user']['role'] === 'headmaster') {
    header('Location: /app/views/admin/dashboard.php');
} else {
    header('Location: /app/views/parent/dashboard.php');
}
exit;
