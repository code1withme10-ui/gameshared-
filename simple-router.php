<?php
// Working router
$request = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request, PHP_URL_PATH);
$path = rtrim($path, '/');
$path = $path === '' ? '/' : $path;

switch ($path) {
    case '/': echo "Home"; break;
    case '/about': echo "About"; break;
    case '/admission': echo "Admission"; break;
    case '/contact': echo "Contact"; break;
    case '/gallery': echo "Gallery"; break;
    case '/login': echo "Login"; break;
    case '/register': echo "Register"; break;
    case '/admin/dashboard': echo "Admin Dashboard"; break;
    case '/parent/portal': echo "Parent Portal"; break;
    default: echo "404 - $path not found";
}
?>
