<?php
session_start();

function requireParentLogin() {
    if (!isset($_SESSION['parent'])) {
        header("Location: parent_login.php");
        exit();
    }
}

function requireHeadmasterLogin() {
    if (!isset($_SESSION['headmaster'])) {
        header("Location: headmaster_login.php");
        exit();
    }
}

function logout() {
    session_unset();
    session_destroy();
}
?>
