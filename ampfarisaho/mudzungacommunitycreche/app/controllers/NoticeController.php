<?php
session_start();

require_once __DIR__ . '/../services/JsonStorage.php';

// Load notices
$storage = new JsonStorage(__DIR__ . '/../../storage/notices.json');
$notices = $storage->read() ?? [];

// Detect user roles safely
$userRole = $_SESSION['user']['role'] ?? null;
$isHeadmaster = $userRole === 'headmaster';
$isParent = $userRole === 'parent';

/*
---------------------------------------
FILTER NOTICES BY VISIBILITY
---------------------------------------
Public → everyone
Private → parents + headmaster
*/

$filteredNotices = [];

foreach ($notices as $notice) {

    $visibility = $notice['visibility'] ?? 'public';

    if ($visibility === 'public') {
        $filteredNotices[] = $notice;
    }

    elseif ($visibility === 'private' && ($isParent || $isHeadmaster)) {
        $filteredNotices[] = $notice;
    }
}

// Sort notices newest first
usort($filteredNotices, function ($a, $b) {
    return strtotime($b['date'] ?? 0) - strtotime($a['date'] ?? 0);
});

// Limit results
$limit = 20;
$notices = array_slice($filteredNotices, 0, $limit);
