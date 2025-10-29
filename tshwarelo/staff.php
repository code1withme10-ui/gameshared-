<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>MyCrahceName - Home</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        /* --- Internal CSS for the Staff List Layout --- */
        
        /* 1. Styling for the outer box and the blue line */
        .staffinfo {
            border: 1px solid blue;
            padding: 15px; /* Add some space inside the box */
            margin-bottom: 10px; /* Space between staff blocks */
        }

        /* 2. Flexbox container for side-by-side layout */
        .staff-container {
            /* Enables the side-by-side layout */
            display: flex; 
            /* Aligns the initial and text to the top */
            align-items: flex-start; 
            /* The .staffinfo class already handles the border, so remove these */
            border: none; 
            padding: 0; 
        }

        /* 3. Styling for the Initial (The 'Picture'/'Image' part) */
        .staff-initial {
            /* Make the letter large and prominent */
            font-size: 3.5em; 
            font-weight: bold;
            color: white; /* Letter color */
            
            /* Create the circle background (your requested 'picture' look) */
            background-color: #007bff; /* Blue background */
            border-radius: 50%; /* Makes it a circle */
            width: 60px; /* Fixed width */
            min-width: 60px; /* Ensures it maintains width in flexbox */
            height: 60px; /* Fixed height */
            
            /* Center the letter within the circle */
            display: flex;
            justify-content: center;
            align-items: center;
            
            margin-right: 20px; /* Space between the circle and the text */
        }

        /* 4. Styling for the Details (The text on the right) */
        .staff-details {
            /* Allows the text to take up the rest of the space */
            flex-grow: 1; 
            /* Little adjustment to align text better with the circle */
            padding-top: 5px; 
        }

        /* Optional: Basic styling for the details text */
        .staff-details p {
            margin: 0 0 5px 0; /* Tighten up the spacing between detail lines */
        }
    </style>
</head>

<body>
    <?php
    require_once 'menu-bar.php';
    ?>

    <?php
    // Create an empty array called $aTeachers to store teacher data
    $aTeachers = [];

    // Add multiple teachers to the array
    $aTeachers[] = ['name' => 'Mkhazimula', 'picture' => 'xxxxxx', 'role' => 'School Principal','experience' => 15];
    $aTeachers[] = ['name' => 'Zakumi', 'picture' => 'xxxxx','role' => 'Pre-School Teacher','experience' => 8];
    $aTeachers[] = ['name' => 'Sipho', 'picture' => 'xxxxx','role'=>'Baby Class Teacher','experience' => 9];
    $aTeachers[] = ['name' => 'Thembi', 'picture' => 'xxxxx','role'=>'Care Taker','experience' => 25];
    // Note: I changed the fourth Mashudu to Thembi to demonstrate a different initial
    ?>

    <main>
        Our staff list:

        <ol>
        <?php 
        // Loop through each element (teacher) in the $aTeachers array for the list
        foreach ($aTeachers as $teacher) {
            $name = $teacher['name'];
            echo "<li> Staff " . $name;
        }
        ?>    
        </ol>    
    </main>

    Our creche has <?=count($aTeachers)?> fulltime staff.

    <?php 
    // Loop again to display the detailed information with the new layout
    foreach ($aTeachers as $teacher) {
        
        // 1. PHP Logic: Get the first letter of the name and convert to uppercase
        $initial = strtoupper(substr($teacher['name'], 0, 1));
        
        // 2. Output the HTML structure with the new classes
        echo "
        <div class='staffinfo staff-container'>
            
            <div class='staff-initial'>
                " . $initial . "
            </div>

            <div class='staff-details'>
                <p><strong>Name:</strong> " . $teacher['name'] . "</p>
                <p><strong>Role:</strong> " . $teacher['role'] . "</p>
                <p><strong>Experience:</strong> " . $teacher['experience'] . "</p>
            </div>
            
        </div>";
    }    
    ?>
    <footer>
        <p>© 2025 Ndlovu's Creche</p>
    </footer>
</body>
</html>