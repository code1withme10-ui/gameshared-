<?php
// src/includes/json_db.php

define('JSON_FILE', '../users.json'); // Path to the storage file

/**
 * Reads all user data from the JSON file.
 * @return array The array of all registered parents/users.
 */
function read_users() {
    if (!file_exists(JSON_FILE)) {
        // Create the file if it doesn't exist
        file_put_contents(JSON_FILE, json_encode([]));
        return [];
    }
    $data = file_get_contents(JSON_FILE);
    return json_decode($data, true) ?: []; // Return empty array if file is empty/invalid
}

/**
 * Saves the entire user array back to the JSON file.
 * @param array $users The complete array of user data.
 * @return bool True on success, false on failure.
 */
function save_users(array $users) {
    $json_data = json_encode($users, JSON_PRETTY_PRINT);
    // Use LOCK_EX to prevent file corruption if multiple people access it at once
    return file_put_contents(JSON_FILE, $json_data, LOCK_EX) !== false;
}

/**
 * Finds the next available unique ID.
 * @param array $users The array of all registered parents/users.
 * @return int The next ID.
 */
function get_next_id(array $users) {
    $max_id = 0;
    foreach ($users as $user) {
        if ($user['parent_id'] > $max_id) {
            $max_id = $user['parent_id'];
        }
    }
    return $max_id + 1;
}
?>