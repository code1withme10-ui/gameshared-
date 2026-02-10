
<?php
$developers = [
    [
        "name" => "ampfarisaho",
        "role" => "Frontend Developer",
        "photo" => "https://via.placeholder.com/150",
        "portfolio" => "ampfarisaho/" // Folder containing portfolio (index.html, etc.)
    ],
    [
        "name" => "takalani",
        "role" => "Backend Developer",
        "photo" => "https://via.placeholder.com/150",
        "portfolio" => "takalani/"
    ],
    [
        "name" => "tshwarelo",
        "role" => "Full Stack Engineer",
        "photo" => "https://via.placeholder.com/150",
        "portfolio" => "tshwarelo/"
    ],
    [
        "name" => "thato",
        "role" => "Full Stack Engineer",
        "photo" => "https://via.placeholder.com/150",
        "portfolio" => "thato/"
    ]
    
];
function safe_get_remote_html($url) {
    // Allow only local trusted URLs
    $allowedHosts = ['localhost', '127.0.0.1'];

    $parsed = parse_url($url);

    if (!in_array($parsed['host'] ?? '', $allowedHosts)) {
        return "<p style='color:red;'>Security error: Host not allowed.</p>";
    }

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT => 3,          // prevents hanging
        CURLOPT_CONNECTTIMEOUT => 2,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response = curl_exec($ch);
    $err      = curl_error($ch);
    $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($err || $status !== 200) {
        return "<p style='color:red;'>Couldn't load content (HTTP $status)</p>";
    }

    return $response;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Developer Team</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <style>
        body { font-family: "Roboto", sans-serif; background-color: #f5f5f5; }
        .team-container { max-width: 1200px; margin: auto; padding: 40px 16px; }
        .team-member { transition: 0.3s; }
        .team-member:hover { transform: scale(1.03); }
        .photo { border-radius: 50%; width: 100px; height: 100px; object-fit: cover; }
        iframe { width: 100%; height: 250px; border: none; border-radius: 8px; }
        
        .portfolio-frame { background-color: #fff; padding: 8px; border-radius: 8px; }
        .portfolio-btn { margin-top: 12px; }
    </style>
</head>
<body>

<!-- Header -->
<header class="w3-center w3-padding-64 w3-blue">
    <h1 class="w3-xxxlarge">Meet Our Developer Team</h1>
    <p class="w3-large">Building creative and scalable solutions</p>
</header>

<!-- Team Section -->
<div class="team-container w3-row-padding w3-margin-top">
    <div class="w3-third w3-margin-bottom">
                    <div  stule="height: 250px;" class="w3-card w3-white w3-center team-member w3-padding-16">
        <?php
        echo safe_get_remote_html('http://localhost:8041/');
        echo safe_get_remote_html('http://localhost:8041/');
        ?>
    </div>                    
    </div>
     
    <?php foreach ($developers as $dev): ?>
        <div class="w3-third w3-margin-bottom">
            <div class="w3-card w3-white w3-center team-member w3-padding-16">
     h           <img src="<?= htmlspecialchars($dev['photo']) ?>" alt="<?= htmlspecialchars($dev['name']) ?>" class="photo w3-margin-bottom">
                <h3><?= htmlspecialchars($dev['name']) ?></h3>
                <h4 class="w3-text-grey"><?= htmlspecialchars($dev['role']) ?></h4>

                <div class="portfolio-frame w3-margin-top">
                    <iframe src="<?= htmlspecialchars($dev['portfolio']) ?>/public/"></iframe>
                </div>

                <div class="portfolio-btn">
                    <a href="<?= htmlspecialchars($dev['portfolio']) ?>/public/" target="_blank" class="w3-button w3-blue w3-round-large w3-margin-top">
                        View Full Portfolio
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
</div>
<?php
require_once './public/sites_list.html';


?>
<!-- Footer -->
<footer class="w3-center w3-padding-32 w3-blue">
    <p>&copy; <?= date('Y') ?> DevTeam Inc. /p>
</footer>


