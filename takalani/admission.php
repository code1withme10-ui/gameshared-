<?php
session_start();

$admissionFile = _DIR_ . '/admissions.json';
$admissions = file_exists($admissionFile)
    ? json_decode(file_get_contents($admissionFile), true)
    : [];

$success = "";
$error = "";

// Identify parent (from session or manual form entry)
$loggedInParent = $_SESSION['user']['parentName'] ?? null;
$loggedInEmail  = $_SESSION['user']['email'] ?? null;
$loggedInContact = $_SESSION['user']['phone'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $childFirstName = trim($_POST["childFirstName"]);
    $childSurname = trim($_POST["childSurname"]);
    $dob = trim($_POST["dob"]);
    $gender = trim($_POST["gender"]);
    $age = trim($_POST["age"]);
    $parentName = $loggedInParent ?: trim($_POST["parentName"]);
    $parentContact = $loggedInContact ?: trim($_POST["parentContact"]);
    $parentEmail = $loggedInEmail ?: trim($_POST["parentEmail"]);
    $address = trim($_POST["address"]);
    $medicalInfo = trim($_POST["medicalInfo"]);
    $emergencyContact = trim($_POST["emergencyContact"]);

    if ($childFirstName && $childSurname && $dob && $gender && $age && $parentName && $parentContact) {

        $newAdmission = [
            "id" => uniqid("child_"),
            "childFirstName" => $childFirstName,
            "childSurname" => $childSurname,
            "dob" => $dob,
            "gender" => $gender,
            "age" => $age,
            "parentName" => $parentName,
            "parentContact" => $parentContact,
            "parentEmail" => $parentEmail,
            "address" => $address,
            "medicalInfo" => $medicalInfo,
            "emergencyContact" => $emergencyContact,
            "status" => "Pending",
            "timestamp" => date("Y-m-d H:i:s")
        ];

        $admissions[] = $newAdmission;

        if (file_put_contents($admissionFile, json_encode($admissions, JSON_PRETTY_PRINT))) {
            $success = "✅ Admission for $childFirstName $childSurname submitted successfully!";
        } else {
            $error = "⚠ Unable to save admission data. Please try again.";
        }
    } else {
        $error = "⚠ Please fill in all required fields marked with *.";
    }
}
?>