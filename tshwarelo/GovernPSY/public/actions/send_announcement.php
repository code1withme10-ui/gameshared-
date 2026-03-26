<?php
// Since we are in 'actions/', go up to find the data folder
$jsonFile = '../data/announcements.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? 'No Title';
    $message = $_POST['message'] ?? '';
    $date = date('Y-m-d H:i:s');

    // FIX: Initialize the variable as an empty array first
    $announcements = [];

    // Load existing data if the file exists
    if (file_exists($jsonFile)) {
        $fileContent = file_get_contents($jsonFile);
        $decoded = json_decode($fileContent, true);
        if (is_array($decoded)) {
            $announcements = $decoded;
        }
    }

    // Now PHP knows $announcements exists for sure
    $newAnnouncement = [
        'id' => uniqid(),
        'title' => $title,
        'message' => $message,
        'date' => $date
    ];

    $announcements[] = $newAnnouncement;

    // Save back to JSON
    if (file_put_contents($jsonFile, json_encode($announcements, JSON_PRETTY_PRINT))) {
        // Redirect back up to the root portal
        header("Location: ../headmaster_portal.php?msg=sent");
        exit();
    } else {
        header("Location: ../headmaster_portal.php?msg=error");
        exit();
    }
}
?>