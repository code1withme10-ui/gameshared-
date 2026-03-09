<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mudzunga Community Crèche</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="main-header">
    <div class="container header-container">
        <div class="logo-area">
            <img src="/assets/images/logo.png" alt="Creche Logo" class="logo">
            <div class="logo-text">
                <h1>Mudzunga Community Crèche</h1>
                <p class="slogan">Nurturing tiny hearts, shaping bright futures</p>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/navbar.php'; ?>
</header>

