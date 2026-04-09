<?php
require_once 'config/config.php';

// Load controllers
require_once CONTROLLERS_PATH . '/BaseController.php';
require_once CONTROLLERS_PATH . '/AuthController.php';
require_once CONTROLLERS_PATH . '/HomeController.php';
require_once CONTROLLERS_PATH . '/AdmissionController.php';
require_once CONTROLLERS_PATH . '/AdminController.php';
require_once CONTROLLERS_PATH . '/ParentController.php';
require_once CONTROLLERS_PATH . '/AdminRegistrationController.php';

// Load models
require_once MODELS_PATH . '/BaseModel.php';
require_once MODELS_PATH . '/UserModel.php';
require_once MODELS_PATH . '/AdmissionModel.php';

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
    '/forgot-password' => ['AuthController', 'forgotPassword'],
    '/reset-password' => ['AuthController', 'resetPassword'],
    
    // Admission routes
    '/admission' => ['AdmissionController', 'index'],
    '/admission/submit' => ['AdmissionController', 'submit'],
    '/admission/success' => ['AdmissionController', 'success'],
    '/admission/list' => ['AdmissionController', 'list'],
    '/admission/view' => ['AdmissionController', 'view'],
    '/admission/update-status' => ['AdmissionController', 'updateStatus'],
    '/admission/delete' => ['AdmissionController', 'delete'],
    '/admission/export' => ['AdmissionController', 'export'],
    
    // Admin routes
    '/admin/dashboard' => ['AdmissionController', 'dashboard'],
    '/admin/admissions' => ['AdmissionController', 'list'],
    '/admin/admissions/view' => ['AdmissionController', 'view'],
    '/admin/admissions/updateStatus' => ['AdmissionController', 'updateStatus'],
    '/admin/users' => ['AdminController', 'users'],
    '/admin/create-user' => ['AdminController', 'createUser'],
    '/admin/delete-user' => ['AdminRegistrationController', 'deleteUser'],
    '/admin/settings' => ['AdminController', 'settings'],
    '/admin/register-parent' => ['AdminRegistrationController', 'registerParent'],
    '/admin/add-application' => ['AdminRegistrationController', 'addApplication'],
    '/admin/search-parents' => ['AdminRegistrationController', 'searchParents'],
    '/admin/parents-list' => ['AdminRegistrationController', 'listParents'],
    '/admin/parent-documents' => ['AdminRegistrationController', 'viewParentDocuments'],
    
    // Parent routes
    '/parent/portal' => ['ParentController', 'portal'],
    '/parent/profile' => ['ParentController', 'profile'],
    '/parent/application-status' => ['ParentController', 'applicationStatus'],
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
