<?php
// ----------------------------------------------------
//  GLOBAL JSON FILE PATHS
// ----------------------------------------------------
define('USERS_FILE', _DIR_ . '/users.json');
define('ADMISSIONS_FILE', _DIR_ . '/admissions.json');
define('HEADMASTER_FILE', _DIR_ . '/headmaster-login.json');

// ----------------------------------------------------
//  JSON READ / WRITE HELPERS
// ----------------------------------------------------
function read_json($file) {
    return file_exists($file)
        ? json_decode(file_get_contents($file), true)
        : [];
}

function write_json($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// ----------------------------------------------------
//  USER HELPERS
// ----------------------------------------------------
function find_user_by_username($username) {
    $users = read_json(USERS_FILE);
    foreach ($users as $u) {
        if (strcasecmp($u['username'], $username) === 0) {
            return $u;
        }
    }
    return null;
}

function find_headmaster($username) {
    $admins = read_json(HEADMASTER_FILE);
    foreach ($admins as $a) {
        if (strcasecmp($a['username'], $username) === 0) {
            return $a;
        }
    }
    return null;
}

// ----------------------------------------------------
//  ADMISSIONS HELPERS
// ----------------------------------------------------
function get_admissions() {
    return read_json(ADMISSIONS_FILE);
}

function save_admissions($admissions) {
    write_json(ADMISSIONS_FILE, $admissions);
}
?>
