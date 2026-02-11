<?php
require_once __DIR__ . '/../app/services/JsonStorage.php';
session_start();

// Ensure only headmaster can access
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'headmaster') {
    header('Location: login.php');
    exit;
}

// Get child ID and action from query string
$childId = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';

if (!$childId || !in_array($action, ['accept', 'decline'])) {
    header('Location: index.php'); // fallback
    exit;
}

// Load children from JSON
$childrenStorage = new JsonStorage(__DIR__ . '/../storage/children.json');
$children = $childrenStorage->read();

$found = false;
foreach ($children as &$child) {
    if ($child['id'] === $childId && $child['status'] === 'pending') {
        $child['status'] = $action === 'accept' ? 'accepted' : 'declined';

        // Add audit trail entry
        if (!isset($child['audit'])) {
            $child['audit'] = [];
        }
        $child['audit'][] = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $child['status'],
            'user' => $_SESSION['user']['full_name'] ?? 'Headmaster'
        ];

        $found = true;
        break;
    }
}

// Save updated children JSON
if ($found) {
    $childrenStorage->write($children);
}

// Redirect back to headmaster dashboard
header('Location: dashboard.php');
exit;
