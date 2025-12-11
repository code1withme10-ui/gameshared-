<?php
// Autoload controllers (optional if you use Composer)
spl_autoload_register(function($class) {
    $file = __DIR__ . '/../app/Http/' . $class . '.php';
    if(file_exists($file)) require $file;
});

// Load routes
require __DIR__ . '/../routes/routes.php';

// Determine requested page
$page = $_GET['page'] ?? 'home';

if(isset($routes[$page])) {
    $route = $routes[$page];

    $viewData = null; // default for static pages

    // If controller is set, instantiate and handle
    if(isset($route['controller']) && $route['controller']) {
        $controllerName = $route['controller'];
        if(class_exists($controllerName)) {
            $controller = new $controllerName();
            $controller->handle();
            $viewData = $controller; // pass controller data to view
        } else {
            die("Controller '$controllerName' not found!");
        }
    }

    // Make controller accessible as $this in views
    $thisPage = $viewData;

    // Include view
    $viewFile = __DIR__ . '/../views/' . $route['view'] . '.php';
    if(file_exists($viewFile)) {
        include $viewFile;
    } else {
        die("View '{$route['view']}.php' not found!");
    }

} else {
    // Page not found
    http_response_code(404);
    echo "<h1>404 Page Not Found</h1>";
    echo "<p>The page '{$page}' does not exist.</p>";
}


