<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'parent') {
    header("Location: login.php");
    exit();
}

$jsonFile = 'data/applications.json';
$newsFile = 'data/announcements.json';
$my_children = [];
$all_news = [];
$is_approved_parent = false; 

// 2. Load and Filter Children Logic
if (file_exists($jsonFile)) {
    $all_applications = json_decode(file_get_contents($jsonFile), true);
    
    if (is_array($all_applications)) {
        $parent_email = isset($_SESSION['parent_email']) ? trim(strtolower($_SESSION['parent_email'])) : ''; 
        
        foreach ($all_applications as $app) {
            if (isset($app['email']) && trim(strtolower($app['email'])) === $parent_email) {
                $my_children[] = $app;
                
                if (isset($app['status']) && strtolower($app['status']) === 'approved') {
                    $is_approved_parent = true;
                }
            }
        }

        usort($my_children, function($a, $b) {
            $dateA = isset($a['submission_date']) ? strtotime($a['submission_date']) : 0;
            $dateB = isset($b['submission_date']) ? strtotime($b['submission_date']) : 0;
            return $dateB - $dateA;
        });
    }
}

// 3. Load Announcements
if ($is_approved_parent && file_exists($newsFile)) {
    $all_news = json_decode(file_get_contents($newsFile), true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parent Dashboard | Govern Psy</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-body">

    <?php include 'includes/navbar.php'; ?>

    <header class="page-banner">
        <h1>Portal Dashboard</h1>
        <p>Managing enrollment for: <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></p>
    </header>

    <main class="container">

        <?php if ($is_approved_parent): ?>
            <?php if (!empty($all_news)): ?>
                <section class="announcements" style="margin-top: 30px;">
                    <h2 style="color: #003366;"><i class="fas fa-bullhorn"></i> Official School News</h2>
                    <?php foreach (array_slice($all_news, 0, 3) as $news): ?>
                        <div class="news-item" style="background: #fff9e6; border-left: 5px solid #ffc107; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                            <small style="color: #888; float: right;"><?php echo $news['date']; ?></small>
                            <h4 style="margin: 0; color: #856404;"><?php echo htmlspecialchars($news['title']); ?></h4>
                            <p style="margin: 5px 0 0 0; font-size: 0.9rem;"><?php echo htmlspecialchars($news['message']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        <?php else: ?>
            <div style="background: #f0f4f8; padding: 15px; border-radius: 8px; margin-top: 30px; color: #5a6b7d; font-size: 0.9rem; border: 1px solid #d1d9e0;">
                <i class="fas fa-info-circle"></i> Once your application is <strong>Approved</strong>, school announcements and news will appear here.
            </div>
        <?php endif; ?>
        
        <section class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; margin-bottom: 30px;">
            <h2>My Registered Children</h2>
            <a href="add_child.php" class="btn btn-primary" style="background-color: #c62828; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                <i class="fas fa-plus"></i> Add Another Child
            </a>
        </section>

        <div class="applications-list">
            <?php foreach($my_children as $child): 
                $status = isset($child['status']) ? strtolower($child['status']) : 'pending';
                $app_id = $child['application_id'];
                $has_messages = isset($child['messages']) && !empty($child['messages']);
            ?>
                <div class="child-card" style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 20px; margin-bottom: 20px; border-left: 6px solid #003366; display: block;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="child-info">
                            <h3 style="margin: 0; color: #003366;">
                                <?php echo htmlspecialchars($child['child_name']); ?>
                                <?php if($has_messages): ?>
                                    <span style="background: #dc3545; color: white; border-radius: 50%; padding: 2px 8px; font-size: 0.7rem; margin-left: 10px; vertical-align: middle;" title="New Message">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                <?php endif; ?>
                            </h3>
                            <p style="margin: 5px 0;"><strong>Status:</strong> <span class="status-badge <?php echo $status; ?>"><?php echo strtoupper($status); ?></span></p>
                        </div>

                        <?php if($has_messages): ?>
                            <button onclick="toggleMessages('<?php echo $app_id; ?>')" style="background: #003366; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                                <i class="fas fa-comments"></i> Messages
                            </button>
                        <?php endif; ?>
                    </div>

                    <?php if($has_messages): ?>
                        <div id="msg-box-<?php echo $app_id; ?>" style="display: none; margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px; border: 1px solid #eee;">
                            <h4 style="margin: 0 0 10px 0; font-size: 0.9rem; color: #333;">Communication Log:</h4>
                            <?php foreach(array_reverse($child['messages']) as $msg): ?>
                                <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e0e0e0;">
                                    <small style="color: #999;"><?php echo $msg['date']; ?></small>
                                    <p style="margin: 5px 0; font-size: 0.95rem; color: #444; line-height: 1.4;">
                                        <?php echo nl2br(htmlspecialchars($msg['text'])); ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script>
    function toggleMessages(id) {
        var box = document.getElementById('msg-box-' + id);
        if (box.style.display === "none") {
            box.style.display = "block";
        } else {
            box.style.display = "none";
        }
    }
    </script>
</body>
</html>