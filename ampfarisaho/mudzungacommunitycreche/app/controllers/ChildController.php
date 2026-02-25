<?php
session_start();

require_once __DIR__ . '/../services/JsonStorage.php';
require_once __DIR__ . '/../middleware/auth.php';

// Ensure user is logged in and is headmaster
requireRole('headmaster');

// Validate GET parameters
$childId = $_GET['id'] ?? null;
$action  = $_GET['action'] ?? null;

if (!$childId || !in_array($action, ['accept', 'decline'])) {
    header('Location: /app/views/admin/dashboard.php');
    exit;
}

// Load children JSON storage
$childrenStorage = new JsonStorage(__DIR__ . '/../../storage/children.json');
$children = $childrenStorage->read();

// Find and update the child
$updated = false;
foreach ($children as &$child) {
    if ($child['id'] === $childId) {
        $child['status']       = ($action === 'accept') ? 'accepted' : 'declined';
        $child['reviewed_at']  = date('Y-m-d H:i:s');
        $child['reviewed_by']  = $_SESSION['user']['email'] ?? 'headmaster';
        $updated = true;
        break;
    }
}
unset($child); // break reference

// Save changes if updated
if ($updated) {
    $childrenStorage->write($children);
}

// Redirect back to headmaster dashboard
header('Location: /app/views/admin/dashboard.php');
exit;
