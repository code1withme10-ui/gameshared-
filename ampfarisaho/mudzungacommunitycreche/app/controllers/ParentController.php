<?php
session_start();

require_once __DIR__ . '/../services/JsonStorage.php';
require_once __DIR__ . '/../middleware/auth.php';

// Only parents allowed
requireRole('parent');

$action = $_GET['action'] ?? '';

$childrenStorage = new JsonStorage(__DIR__ . '/../../storage/children.json');

if ($action === 'admit' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName       = trim($_POST['full_name'] ?? '');
    $dob            = $_POST['dob'] ?? '';
    $gender         = $_POST['gender'] ?? '';
    $grade          = $_POST['grade'] ?? '';
    $address        = trim($_POST['address'] ?? '');
    $allergies      = trim($_POST['allergies'] ?? '');
    $previousSchool = trim($_POST['previous_school'] ?? '');

    $errors = [];

    // Basic validation
    if ($fullName === '') $errors[] = 'Child full name is required.';
    if ($dob === '') $errors[] = 'Date of birth is required.';
    if ($gender === '') $errors[] = 'Gender is required.';
    if ($grade === '') $errors[] = 'Grade selection is required.';
    if ($address === '') $errors[] = 'Residential address is required.';

    // Validate DOB
    try {
        $dobDate = new DateTime($dob);
        $today = new DateTime();
        if ($dobDate > $today) {
            $errors[] = 'Date of birth cannot be in the future.';
        }
    } catch (Exception $e) {
        $errors[] = 'Invalid date of birth.';
    }

    // Optional: age vs grade validation
    if (empty($errors)) {
        $diff = $today->diff($dobDate);
        $ageMonths = ($diff->y * 12) + $diff->m;

        $gradeValid = match ($grade) {
            'Infants'     => ($ageMonths >= 0  && $ageMonths <= 12),
            'Toddlers'    => ($ageMonths > 12  && $ageMonths <= 36),
            'Playgroup'   => ($ageMonths > 36  && $ageMonths <= 48),
            'Pre-School'  => ($ageMonths > 48  && $ageMonths <= 60),
            default       => false,
        };

        if (!$gradeValid) {
            $errors[] = "Selected category does not match child's age.";
        }
    }

    if (empty($errors)) {
        $children = $childrenStorage->read();

        $children[] = [
            'id'            => uniqid('child_'),
            'parent_id'     => $_SESSION['user']['id'],
            'full_name'     => $fullName,
            'dob'           => $dob,
            'gender'        => $gender,
            'grade'         => $grade,
            'address'       => $address,
            'allergies'     => $allergies,
            'previous_school'=> $previousSchool,
            'status'        => 'pending',
            'created_at'    => date('Y-m-d H:i:s')
        ];

        $childrenStorage->write($children);

        header('Location: /app/views/parent/dashboard.php?success=1');
        exit;
    } else {
        // Store errors in session to display on dashboard or form page
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: /app/views/parent/admit-child.php');
        exit;
    }
}
