<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'parent') {
    header("Location: login.php");
    exit();
}

$jsonFile = 'data/applications.json';
$my_children = [];
$parent_email = isset($_SESSION['parent_email']) ? trim(strtolower($_SESSION['parent_email'])) : ''; 

if (file_exists($jsonFile)) {
    $all_applications = json_decode(file_get_contents($jsonFile), true);
    if (is_array($all_applications)) {
        foreach ($all_applications as $app) {
            if (isset($app['email']) && trim(strtolower($app['email'])) === $parent_email) {
                $my_children[] = $app;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parent Dashboard | Govern Psy</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #f4f7f6; margin: 0; }
        .page-banner { background: #003366; color: white; padding: 40px 20px; text-align: center; border-bottom: 5px solid #C41E3A; }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        
        /* Empty State Styling */
        .empty-state {
            background: white;
            padding: 60px 40px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 2px dashed #cbd5e0;
        }
        .empty-state i { color: #cbd5e0; font-size: 4rem; margin-bottom: 20px; }
        
        .child-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 6px solid #003366;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .btn-primary {
            background: #C41E3A;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            display: inline-block;
            transition: 0.3s;
        }
        .btn-primary:hover { background: #a31830; transform: translateY(-2px); }
        .status-badge { background: #dcfce7; color: #166534; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <header class="page-banner">
        <h1 style="margin:0; font-weight:900;">WELCOME, <?php echo strtoupper($_SESSION['user_name']); ?></h1>
        <p style="opacity:0.8;">Manage your children's educational journey</p>
    </header>

    <main class="container">
        
        <?php if (empty($my_children)): ?>
            <div class="empty-state">
                <i class="fas fa-child"></i>
                <h2 style="color: #003366; margin-bottom: 10px;">No Children Registered Yet</h2>
                <p style="color: #666; margin-bottom: 30px; max-width: 500px; margin-left: auto; margin-right: auto;">
                    To begin the enrollment process, please click the button below to add your child's details to our system.
                </p>
                <a href="add_child.php" class="btn-primary">
                    <i class="fas fa-plus"></i> REGISTER YOUR FIRST CHILD
                </a>
            </div>

        <?php else: ?>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h2 style="color: #003366; margin: 0;">My Registered Children</h2>
                <a href="add_child.php" style="color: #C41E3A; font-weight: 700; text-decoration: none;">
                    <i class="fas fa-plus"></i> Add Another
                </a>
            </div>

            <?php foreach ($my_children as $child): ?>
                <div class="child-card">
                    <div>
                        <h3 style="margin: 0; color: #003366;"><?php echo htmlspecialchars($child['child_name']); ?></h3>
                        <p style="margin: 5px 0; font-size: 0.9rem; color: #666;">ID: <?php echo $child['application_id']; ?></p>
                    </div>
                    <div>
                        <span class="status-badge"><?php echo strtoupper($child['status']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </main>

    <footer style="text-align: center; margin-top: 50px; padding: 30px; color: #888; font-size: 0.8rem; border-top: 1px solid #eee;">
        &copy; <?php echo date("Y"); ?> <strong>GOVERN PSY EDUCATIONAL CENTER</strong>. All Rights Reserved.
    </footer>

</body>
</html>