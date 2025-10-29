<!DOCTYPE html> 
<!-- Declares this as an HTML5 document -->

<html lang="en">
<!-- The main HTML element, language set to English -->

<head>
    <meta charset="UTF-8" />
    <!-- Ensures correct character encoding for all text -->

    <title>Happy Kids Creche - Our Staff</title>
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
        <p>© 2025 Happy Kids Creche | Built with ❤️ by our Development Team</p>
    </footer>
</body>
</html>
