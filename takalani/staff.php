
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SubixStar Pre-School - Our Staff</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
        }
        .teacher-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .teacher-card img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }
        .teacher-info {
            flex: 1;
        }
        .teacher-info h3 {
            margin: 0;
            color: #34495e;
        }
        .teacher-info p {
            color: #7f8c8d;
        }
        .teacher-count {
            font-size: 18px;
            margin: 20px 0;
            font-weight: bold;
        }
        footer {
            text-align: center;
            color: #7f8c8d;
            margin-top: 30px;
        }
    </style>
</head>
<body>

 <?php
  require_once 'menu-bar.php';
 ?>
    <?php
// Array to store teacher records
$aTeachers = [
    ['name' => 'Mathagu', 'picture' => 'Mathagu.jpg'],
    ['name' => 'Takalani', 'picture' => 'Takalani.jpg'],
    ['name' => 'Thato', 'picture' => 'Thato.jpg'],
    ['name' => 'Ndlovu', 'picture' => 'Ndlovu.jpg'],
    // You can add more teachers here.
];

// Function to add a new teacher to the list
function addTeacher($name, $picture) {
    global $aTeachers;
    $aTeachers[] = ['name' => $name, 'picture' => $picture];
}

// Example of adding a new teacher
addTeacher('Ampfarisaho', 'Ampfarisaho.jpg');
?>

    <!-- Display the total number of teachers -->
    <div class="teacher-count">
        Total Staff: <?php echo count($aTeachers); ?>
    </div>

    <h2>Our Staff List:</h2>

    <?php
    // Loop through each teacher and display their information
    foreach ($aTeachers as $teacher) {
        echo '<div class="teacher-card">';
        echo '<img src="images/' . $teacher['picture'] . '" alt="' . $teacher['name'] . '">';
        echo '<div class="teacher-info">';
        echo '<h3>' . $teacher['name'] . '</h3>';
        echo '<p><strong>Picture:</strong> ' . $teacher['picture'] . '</p>';
        echo '</div>';
        echo '</div>';
    }
    ?>

    <footer>
        <p>&copy; 2025 SubixStar Pre-School</p>
    </footer>

</body>
</html>

<!DOCTYPE html> 
<!-- Declares this as an HTML5 document -->

<html lang="en">
<!-- The main HTML element, language set to English -->

<head>
    <meta charset="UTF-8" />
    <!-- Ensures correct character encoding for all text -->

    <title>SubixStar Pre-School - Our Staff</title>
    <!-- Browser tab title -->

    <!-- Internal CSS for quick design -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header {
            background: #ff7f50;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        main {
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        ol {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .staffinfo {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .staffinfo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ff7f50;
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php
    // Include the navigation bar (menu) once
    require_once 'menu-bar.php';
    ?>

    <header>
        <h1>Meet Our Caring Staff</h1>
        <p>Dedicated professionals helping your children grow and learn every day.</p>
    </header>

    <?php
    // Array to hold staff information
    $aTeachers = [
        ['name' => 'Mashudu', 'picture' => 'images/mashudu.jpg', 'role' => 'Head Teacher'],
        ['name' => 'Mpho', 'picture' => 'images/mpho.jpg', 'role' => 'Assistant Teacher'],
        ['name' => 'Sipho', 'picture' => 'images/sipho.jpg', 'role' => 'Math Tutor'],
        ['name' => 'Thandi', 'picture' => 'images/thandi.jpg', 'role' => 'Arts & Crafts Teacher'],
        ['name' => 'Kabelo', 'picture' => 'images/kabelo.jpg', 'role' => 'Physical Education Coach'],
        ['name' => 'Nandi', 'picture' => 'images/nandi.jpg', 'role' => 'Language Teacher'],
        ['name' => 'Tebogo', 'picture' => 'images/tebogo.jpg', 'role' => 'Classroom Assistant'],
        ['name' => 'Zanele', 'picture' => 'images/zanele.jpg', 'role' => 'Music Teacher'],
    ];
    ?>

    <main>
        <h2>Our Staff List</h2>

        <ol>
            <?php 
            // Display a simple ordered list of staff names
            foreach ($aTeachers as $teacher) {
                echo "<li>" . htmlspecialchars($teacher['name']) . "</li>";
            }
            ?>
        </ol>

        <p><strong>Our creche has <?=count($aTeachers)?> full-time staff members.</strong></p>

        <h3>Staff Profiles</h3>

        <?php 
        // Display detailed information for each staff member
        foreach ($aTeachers as $teacher) {
            echo "
            <div class='staffinfo'>
                <img src='" . htmlspecialchars($teacher['picture']) . "' alt='" . htmlspecialchars($teacher['name']) . "'>
                <div>
                    <h4>" . htmlspecialchars($teacher['name']) . "</h4>
                    <p>Role: " . htmlspecialchars($teacher['role']) . "</p>
                </div>
            </div>";
        }    
        ?>
    </main>

    <footer>
        <p>© 2025 SubixStar Pre-School | Built with ❤️ by our Development Team</p>
    </footer>
</body>
</html>
