<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file = '../data/inquiries.json';
    $inquiries = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    $newInquiry = [
        'id'      => uniqid(),
        'name'    => htmlspecialchars($_POST['guest_name'] ?? ''),
        'phone'   => htmlspecialchars($_POST['phone_number'] ?? ''), // Fixed: Proper array key
        'message' => htmlspecialchars($_POST['message'] ?? ''),      // Fixed: Proper array key
        'date'    => date("d M Y, H:i"),
        'status'  => 'new'
    ];

    array_unshift($inquiries, $newInquiry);
    file_put_contents($file, json_encode($inquiries, JSON_PRETTY_PRINT));

    // Redirect must also go up one level to find contact.php
    header("Location: ../contact.php?status=success");
    exit();
}