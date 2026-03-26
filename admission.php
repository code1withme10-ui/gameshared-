<?php
require_once 'config/config.php';

// Check if this is a form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $admissionController = new AdmissionController();
    $admissionController->submit();
} else {
    // Display the admission form
    $admissionController = new AdmissionController();
    $admissionController->index();
}
?>
