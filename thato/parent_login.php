<link rel="stylesheet" href="style.css">
<?php
$email = $_POST['email'];

$admissions = json_decode(file_get_contents('admissions.json'), true);

foreach ($admissions as $entry) {
    if ($entry['email'] === $email) {
        echo "Parent Login Successful<br>";
        echo "Status: " . $entry['status'];
        exit;
    }
}

echo "No admission found for this email.";
?>
