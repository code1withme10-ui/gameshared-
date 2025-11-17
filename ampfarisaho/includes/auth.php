<?php
session_start();

function requireParentLogin() {
    if (!isset($_SESSION['parent'])) {
        header("Location: login.php");
        exit();
    }
}

function requireHeadmasterLogin() {
    if (!isset($_SESSION['headmaster'])) {
        header("Location: login.php");
        exit();
    }
}

function logout() {
    session_unset();
    session_destroy();
}
?>
