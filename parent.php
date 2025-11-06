<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parent Dashboard - SubixStar Pre-School</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Inline backup styling (kept minimal in case CSS fails) */
        .dashboard {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .child-info, .progress-section {
            margin-top: 20px;
            background: #f9fafc;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #eee;
        }
        .child-info h3, .progress-section h3 {
            color: #333;
        }
        .button {
            background: linear-gradient(90deg, #ff6b6b, #feca57, #1dd1a1, #54a0ff, #5f27cd);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<?php require_once 'menu-bar.php'; ?>

<main class="dashboard">
    <h2>Welcome, <?= htmlspecialchars($user["parentName"]) ?> 👋</h2>

    <section class="child-info">
        <h3>Child Details</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($user["childName"]) ?></p>
        <p><strong>Age:</strong> <?= htmlspecialchars($user["childAge"]) ?> years</p>
    </section>

    <section class="progress-section">
        <h3>Progress Overview</h3>
        <p>🌟 <em><?= htmlspecialchars($user["childName"]) ?></em> is thriving at SubixStar! They show great enthusiasm during group play, curiosity in learning, and positive social interaction.</p>
        <p>✅ Teachers note wonderful improvements in creativity, listening, and confidence daily.</p>
    </section>

    <p><a href="logout.php" class="button">Logout</a></p>
</main>

<footer style="text-align:center; margin-top:30px;">
    <p>© 2025 SubixStar Pre-School</p>
</footer>

</body>
</html>
