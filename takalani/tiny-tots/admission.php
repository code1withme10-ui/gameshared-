<?php
require_once 'config/config.php';

// MVC Pattern: Controller Logic
$admissionController = new AdmissionController();
$admissionController->index();
?>
