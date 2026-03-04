<?php
require_once 'config/config.php';
echo "Config loaded successfully<br>";

// Check if AdmissionController exists
if (class_exists('AdmissionController')) {
    echo "AdmissionController class exists<br>";
    
    // Test if dashboard method exists
    if (method_exists('AdmissionController', 'dashboard')) {
        echo "Dashboard method exists<br>";
        
        // Try to create instance
        try {
            $controller = new AdmissionController();
            echo "Controller created successfully<br>";
        } catch (Exception $e) {
            echo "Error creating controller: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "Dashboard method does not exist<br>";
    }
} else {
    echo "AdmissionController class does not exist<br>";
}

// Check if functions.php was included
if (function_exists('requireRole')) {
    echo "requireRole function exists<br>";
} else {
    echo "requireRole function does not exist<br>";
}

// Check routes by reading the file directly
$indexContent = file_get_contents('index.php');
echo "Index file read successfully<br>";

// Extract routes array
if (preg_match('/\$routes = \[(.*?)\];/s', $indexContent, $matches)) {
    echo "Routes array found in file<br>";
    // Check if admin dashboard route exists
    if (strpos($matches[0], "'/admin/dashboard'") !== false) {
        echo "Admin dashboard route found in routes array<br>";
    } else {
        echo "Admin dashboard route NOT found in routes array<br>";
    }
} else {
    echo "Routes array not found in file<br>";
}

// Test the actual routing
$request = '/admin/dashboard';
echo "Testing route for: $request<br>";

// Simple router
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
    '/admin/delete-user' => ['AdminController', 'deleteUser'],
    '/admin/settings' => ['AdminController', 'settings'],
    
    // Parent routes
    '/parent/portal' => ['ParentController', 'portal'],
    '/parent/profile' => ['ParentController', 'profile'],
    '/parent/application-status' => ['ParentController', 'applicationStatus'],
];

if (isset($routes[$request])) {
    echo "Route found: " . $routes[$request][0] . "::" . $routes[$request][1] . "<br>";
} else {
    echo "Route NOT found for: $request<br>";
}
?>
