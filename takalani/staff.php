<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Staff - SubixStar Pre-School</title>
    <style>
        /* General layout */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff7f2; /* same warm tone as home */
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #ff7f50; /* coral like home page */
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        main {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
        }

        h1, h2, h3 {
            color: #333;
            text-align: center;
        }

        /* Staff cards */
        .staffinfo {
            background-color: #ffffff;
            border: 1px solid #f2c3b3;
            border-radius: 12px;
            padding: 15px;
            margin: 15px 0;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 10px rgba(255, 127, 80, 0.1);
            transition: transform 0.2s ease;
        }

        .staffinfo:hover {
            transform: translateY(-3px);
        }

        .staffinfo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ff7f50;
        }

        .staffinfo h4 {
            margin: 0;
            color: #2c3e50;
        }

        .staffinfo p {
            margin: 5px 0 0;
            color: #7f8c8d;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 40px;
        }

        .count {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #444;
        }
    </style>
</head>

<body>
    <?php require_once 'menu-bar.php'; ?>

    <header>
        <h1>Meet Our Caring Staff</h1>
        <p>Dedicated educators who nurture your child's early learning journey.</p>
    </header>

    <?php
    // Staff array
    $aTeachers = [
        ['name' => 'Mashudu', 'picture' => 'images/mashudu.jpg', 'role' => 'Head Teacher'],
        ['name' => 'Mpho', 'picture' => 'images/mpho.jpg', 'role' => 'Assistant Teacher'],
        ['name' => 'Sipho', 'picture' => 'images/sipho.jpg', 'role' => 'Math Tutor'],
        ['name' => 'Thandi', 'picture' => 'images/thandi.jpg', 'role' => 'Arts & Crafts Teacher'],
        ['name' => 'Kabelo', 'picture' => 'images/kabelo.jpg', 'role' => 'PE Coach'],
        ['name' => 'Nandi', 'picture' => 'images/nandi.jpg', 'role' => 'Language Teacher'],
        ['name' => 'Tebogo', 'picture' => 'images/tebogo.jpg', 'role' => 'Classroom Assistant'],
        ['name' => 'Zanele', 'picture' => 'images/zanele.jpg', 'role' => 'Music Teacher'],
    ];
    ?>

    <main>
        <h2>Our Staff Team</h2>
        <p class="count">Total Staff Members: <?= count($aTeachers) ?></p>

        <?php 
        foreach ($aTeachers as $teacher) {
            echo "
            <div class='staffinfo'>
                <img src='" . htmlspecialchars($teacher['picture']) . "' alt='" . htmlspecialchars($teacher['name']) . "'>
                <div>
                    <h4>" . htmlspecialchars($teacher['name']) . "</h4>
                    <p>" . htmlspecialchars($teacher['role']) . "</p>
                </div>
            </div>";
        }
        ?>
    </main>

    <footer>
        <p>© 2025 SubixStar Pre-School | Built with ❤️ by the SubixStar Team</p>
    </footer>
</body>
</html>
