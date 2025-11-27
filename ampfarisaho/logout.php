<?php
include __DIR__ . "/includes/auth.php";

logout();
header("Location: index.php?page=login");
exit;

