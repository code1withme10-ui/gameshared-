<?php
// -----------------------------------------------------
// Load Composer (if you use it)
// -----------------------------------------------------
$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require $autoload;
}

// -----------------------------------------------------
// FRONT CONTROLLER – Acts as webroot for your website
// -----------------------------------------------------

// Get requested page, default to 'home'
$page = $_GET['page'] ?? 'home';

// Define all allowed pages with full paths
$pages = [
    'home' => __DIR__ . '/../home.php',
    'about' => __DIR__ . '/../about.php',
    'login' => __DIR__ . '/../login.php',
    'logout' => __DIR__ . '/../logout.php',
    'admission' => __DIR__ . '/../admission.php',
    'gallery' => __DIR__ . '/../gallery.php',
    'add_child' => __DIR__ . '/../add_child.php',
    'contact' => __DIR__ . '/../contact.php',
    'dashboard' => __DIR__ . '/../dashboard.php', // <-- key matches your file
    'headmaster_dashboard' => __DIR__ . '/../headmaster_dashboard.php',
    'progress_report' => __DIR__ . '/../progress_report.php',
];

// Load the page if it exists
if (isset($pages[$page]) && file_exists($pages[$page])) {
    
    // Optional: Include navbar for every page automatically
    require __DIR__ . '/../includes/navbar.php';
    
    // Include requested page
    require $pages[$page];
    
} else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    exit;
}
