<?php
// functions.php - Shared functions for the creche system

// -------------------- JSON FILE PATHS --------------------
define('USERS_FILE', __DIR__ . '/users.json');
define('CHILDREN_FILE', __DIR__ . '/children.json');
define('ADMISSIONS_FILE', __DIR__ . '/admissions.json');
define('HEADMASTER_FILE', __DIR__ . '/headmaster.json');

// -------------------- HELPER FUNCTIONS --------------------

// Generate unique ID for users or children
function generate_id($prefix = 'U') {
    return $prefix . uniqid();
}

// Read all users
function read_users() {
    if (!file_exists(USERS_FILE)) return [];
    $json = file_get_contents(USERS_FILE);
    return $json ? json_decode($json, true) : [];
}

// Write all users
function write_users($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

// Read all children
function read_children() {
    if (!file_exists(CHILDREN_FILE)) return [];
    $json = file_get_contents(CHILDREN_FILE);
    return $json ? json_decode($json, true) : [];
}

// Write all children
function write_children($children) {
    file_put_contents(CHILDREN_FILE, json_encode($children, JSON_PRETTY_PRINT));
}

// Read all admissions
function read_admissions() {
    if (!file_exists(ADMISSIONS_FILE)) return [];
    $json = file_get_contents(ADMISSIONS_FILE);
    return $json ? json_decode($json, true) : [];
}

// Write all admissions
function write_admissions($admissions) {
    file_put_contents(ADMISSIONS_FILE, json_encode($admissions, JSON_PRETTY_PRINT));
}

// Find a user by email
function find_user_by_email($email) {
    $users = read_users();
    foreach ($users as $u) {
        if (strcasecmp($u['email'], $email) === 0) return $u;
    }
    return null;
}

// Find children for a specific parent ID
function find_children_by_parent($parent_id) {
    $children = read_children();
    $result = [];
    foreach ($children as $child) {
        if ($child['parent_id'] === $parent_id) {
            $result[] = $child;
        }
    }
    return $result;
}

// Find user by ID
function find_user_by_id($user_id) {
    $users = read_users();
    foreach ($users as $u) {
        if ($u['id'] === $user_id) return $u;
    }
    return null;
}

// Authenticate user (email + password)
function authenticate_user($email, $password) {
    $user = find_user_by_email($email);
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return null;
}

// Register a new parent
function register_parent($name, $email, $password) {
    $users = read_users();

    // Check duplicate email
    foreach ($users as $u) {
        if (strcasecmp($u['email'], $email) === 0) {
            return ['error' => 'Email already registered'];
        }
    }

    $newUser = [
        'id' => generate_id('P'),
        'name' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => 'parent'
    ];

    $users[] = $newUser;
    write_users($users);

    return ['success' => true, 'user' => $newUser];
}
?>
