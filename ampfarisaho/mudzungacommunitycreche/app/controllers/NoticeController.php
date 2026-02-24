<?php
session_start();

require_once __DIR__ . '/../services/JsonStorage.php';
require_once __DIR__ . '/../middleware/auth.php';

// Ensure user is logged in (any role can view)
requireAuth();

// Load notices from JSON safely
$storage = new JsonStorage(__DIR__ . '/../../storage/notices.json');
$notices = $storage->read();

// Sort notices: newest first
usort($notices, function($a, $b) {
    return strtotime($b['date'] ?? 0) - strtotime($a['date'] ?? 0);
});

// Optional: limit number of notices displayed
$limit = 20;
$notices = array_slice($notices, 0, $limit);
