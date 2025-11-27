<?php

/**
 * Read JSON from a file safely.
 *
 * @param string $file
 * @return array
 */
function readJSON($file) {
    if (!file_exists($file)) {
        return [];
    }

    $json = file_get_contents($file);
    if ($json === false) {
        return [];
    }

    $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING) ?? [];
    return $data;
}

/**
 * Write an array to a JSON file safely.
 *
 * @param string $file
 * @param array $data
 * @return bool
 */
function writeJSON($file, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    return file_put_contents($file, $json, LOCK_EX) !== false;
}
