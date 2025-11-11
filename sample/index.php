<?php
// index.php
session_start();  // <-- MUST start session first

// Load site configuration
$config = json_decode(file_get_contents("configs/client2.json"), true);

// Determine which page to load
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$pageFile = "pages/{$page}.php";

// If file doesn’t exist, show a simple 404 message
if (!file_exists($pageFile)) {
    $pageFile = "pages/home.php";
}

include "includes/header.php";
include "includes/menu.php";
include $pageFile;
include "includes/footer.php";
?>

