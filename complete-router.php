<?php
// Complete working router for Tiny Tots
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the request URI and parse it
$request = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request, PHP_URL_PATH);

// Remove query string and trim slashes
$path = rtrim($path, '/');
$path = $path === '' ? '/' : $path;

// Load configuration
require_once 'config/config.php';

// Simple routing with actual page content
switch ($path) {
    case '/':
        // Home page
        include 'views/home/index.php';
        break;
        
    case '/about':
        // About page
        include 'views/about/index.php';
        break;
        
    case '/contact':
        // Contact page
        include 'views/contact/index.php';
        break;
        
    case '/gallery':
        // Gallery page
        include 'views/gallery/index.php';
        break;
        
    case '/login':
        // Login page
        require_once 'controllers/AuthController.php';
        $auth = new AuthController();
        $auth->login();
        break;
        
    case '/register':
        // Register page
        require_once 'controllers/AuthController.php';
        $auth = new AuthController();
        $auth->register();
        break;
        
    case '/admission':
        // Admission page
        require_once 'controllers/AdmissionController.php';
        $admission = new AdmissionController();
        $admission->index();
        break;
        
    case '/admin/dashboard':
        // Admin dashboard
        require_once 'controllers/AdminController.php';
        $admin = new AdminController();
        $admin->dashboard();
        break;
        
    case '/parent/portal':
        // Parent portal
        require_once 'controllers/ParentController.php';
        $parent = new ParentController();
        $parent->portal();
        break;
        
    case '/logout':
        // Logout
        require_once 'controllers/AuthController.php';
        $auth = new AuthController();
        $auth->logout();
        break;
        
    default:
        // 404 page
        http_response_code(404);
        echo "<!DOCTYPE html>
<html>
<head>
    <title>404 - Page Not Found | Tiny Tots Creche</title>
    <link rel='stylesheet' href='assets/css/styles.css'>
</head>
<body>
    <div class='container'>
        <h1>404 - Page Not Found</h1>
        <p>The page you're looking for doesn't exist.</p>
        <a href='/' class='btn'>Go Home</a>
    </div>
</body>
</html>";
        break;
}
?>
