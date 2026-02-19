<?php

require_once __DIR__ . '/../services/JsonStorage.php';

session_start();

/**
 * Only parents allowed
 */
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'parent') {
    header('Location: /login.php');
    exit;
}

$action = $_GET['action'] ?? '';

$childrenStorage = new JsonStorage(__DIR__ . '/../../storage/children.json');

if ($action === 'admit' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $children = $childrenStorage->read();

    $children[] = [
        'id'        => uniqid('child_'),
        'parent_id'=> $_SESSION['user']['id'],
        'full_name'=> trim($_POST['full_name']),
        'grade'    => trim($_POST['grade']),
        'status'   => 'pending',
        'created_at' => date('Y-m-d H:i:s')
    ];

    $childrenStorage->write($children);

    header('Location: /app/views/parent/dashboard.php?success=1');
    exit;
}
