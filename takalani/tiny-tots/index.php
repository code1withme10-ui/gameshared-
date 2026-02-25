<?php
require_once 'config/config.php';

// Simple router
$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);

// Remove query string and trim slashes
$request = rtrim($request, '/');
$request = $request === '' ? '/' : $request;

// Route definitions
$routes = [
    // Home routes
    '/' => ['HomeController', 'index'],
    '/home' => ['HomeController', 'index'],
    '/about' => ['HomeController', 'about'],
    '/contact' => ['HomeController', 'contact'],
    '/gallery' => ['HomeController', 'gallery'],
    
    // Auth routes
    '/login' => ['AuthController', 'login'],
    '/logout' => ['AuthController', 'logout'],
    '/register' => ['AuthController', 'register'],
    '/profile' => ['AuthController', 'profile'],
    
    // Admission routes
    '/admission' => ['AdmissionController', 'index'],
    '/admission/submit' => ['AdmissionController', 'submit'],
    '/admission/success' => ['AdmissionController', 'success'],
    '/admission/list' => ['AdmissionController', 'list'],
    '/admission/view' => ['AdmissionController', 'view'],
    '/admission/update-status' => ['AdmissionController', 'updateStatus'],
    '/admission/delete' => ['AdmissionController', 'delete'],
    
    // Admin routes
    '/admin/dashboard' => ['AdminController', 'dashboard'],
    '/admin/users' => ['AdminController', 'users'],
    '/admin/create-user' => ['AdminController', 'createUser'],
    '/admin/delete-user' => ['AdminController', 'deleteUser'],
    '/admin/settings' => ['AdminController', 'settings'],
    
    // Parent routes (placeholder)
    '/parent/portal' => ['ParentController', 'portal'],
];

// Handle 404
if (!isset($routes[$request])) {
    http_response_code(404);
    require VIEWS_PATH . '/errors/404.php';
    exit;
}

// Route to controller
[$controllerName, $method] = $routes[$request];

// Instantiate controller
$controller = new $controllerName();

// Call method
try {
    $controller->$method();
} catch (Exception $e) {
    http_response_code(500);
    require VIEWS_PATH . '/errors/500.php';
}
?>
