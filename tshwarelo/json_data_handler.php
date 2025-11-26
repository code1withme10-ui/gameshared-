<?php
// JSON DATA HANDLER: json_data_handler.php

// --- ADMISSIONS FILE CONSTANTS & FUNCTIONS ---
define('ADMISSIONS_FILE', 'admissions.json');

/**
 * Reads data from the admissions.json file.
 * @return array The decoded array of admission records.
 */
function read_admissions_data() {
    if (!file_exists(ADMISSIONS_FILE)) {
        return [];
    }
    
    $content = file_get_contents(ADMISSIONS_FILE);
    $data = json_decode($content, true);
    
    return is_array($data) ? $data : [];
}

/**
 * Writes data to the admissions.json file using a safe, atomic write approach (to mitigate permission issues).
 * @param array $data The array of admission records to save.
 * @return bool True on success, false on failure.
 */
function write_admissions_data(array $data) {
    $json_content = json_encode($data, JSON_PRETTY_PRINT);
    
    // Failsafe: Use a temporary file and rename it
    $temp_file = tempnam(sys_get_temp_dir(), 'admissions_temp');
    if ($temp_file === false) {
        return false;
    }
    
    // Write to the temp file
    if (file_put_contents($temp_file, $json_content, LOCK_EX) === false) {
        unlink($temp_file);
        return false;
    }
    
    // Atomically rename the temp file to the final file
    if (rename($temp_file, ADMISSIONS_FILE)) {
        // Attempt to loosen permissions to fix Docker/Windows issues
        @chmod(ADMISSIONS_FILE, 0666);
        return true;
    } else {
        unlink($temp_file);
        return false;
    }
}


// --- CHILDREN FILE CONSTANTS & FUNCTIONS ---
define('CHILDREN_FILE', 'children.json');

/**
 * Reads data from the children.json file.
 * @return array The decoded array of children records.
 */
function read_children_data() {
    if (!file_exists(CHILDREN_FILE)) {
        return [];
    }
    
    $content = file_get_contents(CHILDREN_FILE);
    $data = json_decode($content, true);
    
    return is_array($data) ? $data : [];
}

/**
 * Writes data to the children.json file using a safe, atomic write approach.
 * @param array $data The array of children records to save.
 * @return bool True on success, false on failure.
 */
function write_children_data(array $data) {
    $json_content = json_encode($data, JSON_PRETTY_PRINT);
    
    // Failsafe: Use a temporary file and rename it
    $temp_file = tempnam(sys_get_temp_dir(), 'children_temp');
    if ($temp_file === false) {
        return false;
    }
    
    // Write to the temp file
    if (file_put_contents($temp_file, $json_content, LOCK_EX) === false) {
        unlink($temp_file);
        return false;
    }
    
    // Atomically rename the temp file to the final file
    if (rename($temp_file, CHILDREN_FILE)) {
        // Attempt to loosen permissions to fix Docker/Windows issues
        @chmod(CHILDREN_FILE, 0666);
        return true;
    } else {
        unlink($temp_file);
        return false;
    }
}