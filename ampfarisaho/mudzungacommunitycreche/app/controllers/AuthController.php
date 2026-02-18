<?php

require_once __DIR__ . '/../services/JsonStorage.php';

session_start();

/**
 * LOGIN
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        header('Location: /login.php?error=empty');
        exit;
    }

    $usersStorage = new JsonStorage(__DIR__ . '/../../storage/users.json');
    $users = $usersStorage->read();

    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {

            $_SESSION['user'] = $user;

            // Redirect based on role
            if ($user['role'] === 'headmaster') {
                header('Location: /app/views/admin/dashboard.php');
            } else {
                header('Location: /app/views/parent/dashboard.php');
            }
            exit;
        }
    }

    header('Location: /login.php?error=invalid');
    exit;
}
