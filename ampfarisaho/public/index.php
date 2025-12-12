<?php
session_start();
require __DIR__ . '/../routes/routes.php';

$page = $_GET['page'] ?? 'home';

if (isset($routes[$page])) {
    $route = $routes[$page];

    $controller = null;
    if (isset($route['controller']) && $route['controller']) {
        $controllerName = $route['controller'];
        require_once __DIR__ . '/../app/Http/' . $controllerName . '.php';
        $controller = new $controllerName();
        $controller->handle();
    }

    $viewFile = __DIR__ . '/../views/' . $route['view'] . '.php';
    if (file_exists($viewFile)) {
        // Pass controller variables to view
        // Example for ParentController:
        if ($controller) {
            $parent_info = $controller->parent_info ?? null;
            $my_children = $controller->my_children ?? [];
            $errors = $controller->errors ?? [];
            $success_message = $controller->success_message ?? '';
        }
        include $viewFile;
    } else {
        die("View '{$route['view']}.php' not found!");
    }
} else {
    http_response_code(404);
    echo "<h1>404 Page Not Found</h1>";
}


