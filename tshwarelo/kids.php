<?php
// Include your menu/header file
include 'menu-bar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kids Names</title>
</head>
<body>
    <h1>Welcome to Ndlovu's Kids Creche</h1>
    <h2>Names of Kids</h2>
    
    <?php
    // Define an array of names (your list)
    $kids_names = array("Khumo", "Ziyanda", "Thato", "Thlarihani", "Samu", "Asanda", "Langu", "Rio");

    // Display the names in an unordered list
    echo "<ul>";
    foreach ($kids_names as $name) {
        echo "<li>" . $name . "</li>";
    }
    echo "</ul>";

    // Count the total number of kids using the count() function
    $total_kids = count($kids_names);

    // Display the result immediately after the list
    echo "<h3>Total Number of Kids: " . $total_kids . "</h3>"; 
    ?>

    <p>This page displays the number of kids and thier names.</p> 
</body>
</html>