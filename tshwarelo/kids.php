<?php
// Include your menu/header file if you have one
include 'menu-bar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kids Names</title>
</head>
<body>
    <h1>Names of Kids</h1>

    <?php
    // Define an array of names
    $kids_names = array("Khumo", "Ziyanda", "Thato", "Thlarihani");

    // Display the names in an unordered list
    echo "<ul>";
    foreach ($kids_names as $name) {
        echo "<li>" . $name . "</li>";
    }
    echo "</ul>";
    ?>

    <p>This page successfully displays the names.</p>
</body>
</html>