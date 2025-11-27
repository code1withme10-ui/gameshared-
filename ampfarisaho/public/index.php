<?php
// public/index.php
$page = $_GET['page'] ?? 'home';

$pages = [
    'home' => __DIR__ . '/../home.php',
    'login' => __DIR__ . '/../login.php',
    'logout' => __DIR__ . '/../logout.php',
    'admission' => __DIR__ . '/../admission.php',
    'about' => __DIR__ . '/../about.php',
    'progress_report' => __DIR__ . '/../progress_report.php',
    'parent_dashboard' => __DIR__ . '/../parent_dashboard.php',
    'headmaster_dashboard' => __DIR__ . '/../headmaster_dashboard.php',
    'gallery' => __DIR__ . '/../gallery.php',
    'code_of_conduct' => __DIR__ . '/../code_of_conduct.php',
    'help' => __DIR__ . '/../help.php'
];

if (isset($pages[$page]) && file_exists($pages[$page])) {
    include $pages[$page];
} else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
}

