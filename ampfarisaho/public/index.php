<?php
session_start();

// Autoload app classes
require_once __DIR__ . '/app/autoload.php';

// Include routes
require_once __DIR__ . '/routes/routes.php';

// Default page if none provided
$default_page = 'home';

// Get requested page from query string
$page = $_GET['page'] ?? $default_page;

// Security: fallback if page not in routes
if (!isset($routes[$page])) {
    echo "<h1>404 Not Found</h1><p>The requested page '{$page}' does not exist.</p>";
    exit;
}

// Run controller logic if exists
if (!empty($routes[$page]['controller'])) {
    $controller_class = $routes[$page]['controller'];
    if (class_exists($controller_class)) {
        $controller = new $controller_class();
        // Optional: handle method per page if needed
        if (method_exists($controller, 'handle')) {
            $controller->handle($page);
        }
    }
}

// Include the view
$view_file = __DIR__ . "/views/{$routes[$page]['view']}.php";
if (file_exists($view_file)) {
    include $view_file;
} else {
    echo "<h1>404 Not Found</h1><p>View file not found for page '{$page}'.</p>";
    exit;
}

