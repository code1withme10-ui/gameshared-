<?php
// -----------------------------------------------------
// Load Composer (if you use it)
// -----------------------------------------------------
$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require $autoload;
}

// -----------------------------------------------------
// Start session (ONE place only)
// -----------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
}

// -----------------------------------------------------
// FRONT CONTROLLER – Acts as webroot for your website
// -----------------------------------------------------

// Get requested page, default to 'home'
$page = $_GET['page'] ?? 'home';

// Define all allowed pages with full paths
$pages = [
    'home' => __DIR__ . '/../pages/home.php',
    'about' => __DIR__ . '/../pages/about.php',
    'login' => __DIR__ . '/../pages/login.php',
    'logout' => __DIR__ . '/../pages/logout.php',
    'info' => __DIR__ . '/../pages/info.php',
    'admission' => __DIR__ . '/../pages/admission.php',
    'gallery' => __DIR__ . '/../pages/gallery.php',
    'add_child' => __DIR__ . '/../pages/add_child.php',
    'contact' => __DIR__ . '/../pages/contact.php',
    'dashboard' => __DIR__ . '/../pages/dashboard.php',
    'dresscode' => __DIR__ . '/../pages/dresscode.php',
    'sports_culture' => __DIR__ . '/../pages/sports_culture.php',
    'meals' => __DIR__ . '/../pages/meals.php',
    'headmaster_dashboard' => __DIR__ . '/../pages/headmaster_dashboard.php',
    'progress_report' => __DIR__ . '/../pages/progress_report.php',
];

// Load the page if it exists
if (isset($pages[$page]) && file_exists($pages[$page])) {

    // Include navbar for every page
    require __DIR__ . '/../includes/navbar.php';

    // Include requested page
    require $pages[$page];

} else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    exit;
}
