<?php
// public/index.php

// 1. Load dependencies
require __DIR__ . '/../app/functions.php';

// 2. Load the menu/header (required first to display the page content)
require __DIR__ . '/../app/menu-bar.php'; 

// 3. Determine the requested page from the URL
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define the base path that should be ignored (e.g., '/takalani/').
// This handles the case where your project is not in the server root.
// We will calculate this dynamically based on the current script name.
$base_script_path = dirname($_SERVER['SCRIPT_NAME']);
// Add a trailing slash for consistent removal, unless it's just '/'
$base_path = ($base_script_path === '/') ? '/' : $base_script_path . '/';

// Get the requested page, removing the base path
$page = str_replace($base_path, '', $request_uri);
$page = trim($page, '/'); // Remove any remaining slashes

// Use 'home' as the default page if no specific page is requested
if (empty($page)) {
    $page = 'home';
}

// If the path contains the .php extension (which it shouldn't if .htaccess is working), remove it
$page = basename($page, '.php'); 

// 4. Define the path to the file in the SECURE 'app' directory
$file_path = __DIR__ . "/../app/{$page}.php";

// 5. Load the page if it exists
if (file_exists($file_path)) {
    require $file_path;
} else {
    // Show a custom 404 error if the file is not found in the app folder
    http_response_code(404);
    echo "<h1>Page Not Found (404)</h1>";
    echo "<p>The requested page: **{$page}.php** could not be found in the **app** directory.</p>";
    echo "<p>Please ensure you ran the Git Bash commands to fix your internal links (a href tags).</p>";
}
?>