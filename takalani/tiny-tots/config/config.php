<?php
// Configuration
define('ROOT_PATH', dirname(__DIR__));
define('DATA_PATH', ROOT_PATH . '/data');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('MODELS_PATH', ROOT_PATH . '/models');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');

// Database files
define('USERS_FILE', DATA_PATH . '/users.json');
define('ADMISSIONS_FILE', DATA_PATH . '/admissions.json');
define('HEADMASTER_FILE', DATA_PATH . '/headmaster.json');

// Session
session_start();

// Autoloader
spl_autoload_register(function ($class) {
    $paths = [
        MODELS_PATH . '/' . $class . '.php',
        CONTROLLERS_PATH . '/' . $class . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Helper functions
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function requireLogin() {
    if (!isset($_SESSION['user'])) {
        redirect('/login');
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['user']['role'] !== $role) {
        redirect('/unauthorized');
    }
}
?>
