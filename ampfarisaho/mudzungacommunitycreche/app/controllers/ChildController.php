<?php
session_start();

require_once __DIR__ . '/../services/JsonStorage.php';

// Security: only headmaster
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'headmaster') {
    header('Location: /login.php');
    exit;
}

// Validate inputs
if (!isset($_GET['id'], $_GET['action'])) {
    header('Location: /admin/dashboard.php');
    exit;
}

$childId = $_GET['id'];
$action  = $_GET['action'];

if (!in_array($action, ['accept', 'decline'])) {
    header('Location: /admin/dashboard.php');
    exit;
}

// Load children JSON
$storage = new JsonStorage(__DIR__ . '/../../storage/children.json');
$children = $storage->read();

$updated = false;

foreach ($children as &$child) {
    if ($child['id'] === $childId) {
        $child['status'] = ($action === 'accept') ? 'accepted' : 'declined';
        $child['reviewed_at'] = date('Y-m-d H:i:s');
        $child['reviewed_by'] = $_SESSION['user']['email'];
        $updated = true;
        break;
    }
}

if ($updated) {
    $storage->write($children);
}

// Redirect back to dashboard
header('Location: /app/views/admin/dashboard.php');
exit;
