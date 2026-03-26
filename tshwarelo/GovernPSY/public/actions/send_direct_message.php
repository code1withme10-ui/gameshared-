<?php
session_start();

// 1. Security Check - Added session_start() check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    // Path fixed to go back to root login
    header("Location: ../login.php");
    exit();
}

// 2. Folder Path Fix - Added ../ to find the data folder
$jsonFile = '../data/applications.json';
$child_id = $_GET['id'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Path fixed here too
    if (file_exists($jsonFile)) {
        $applications = json_decode(file_get_contents($jsonFile), true);
        
        foreach ($applications as &$app) {
            if ($app['application_id'] === $_POST['child_id']) {
                // Initialize messages array if it doesn't exist
                if (!isset($app['messages'])) $app['messages'] = [];
                
                $app['messages'][] = [
                    'msg_id' => uniqid(),
                    'text' => htmlspecialchars($_POST['message']),
                    'date' => date("d M Y, H:i"),
                    'read' => false
                ];
                break;
            }
        }
        
        file_put_contents($jsonFile, json_encode($applications, JSON_PRETTY_PRINT));
        
        // Path fixed: Go back to the portal in the root folder
        header("Location: ../headmaster_portal.php?msg=sent");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Direct Message | Govern Psy</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="padding: 40px; background: #f4f7f6; font-family: sans-serif;">

    <div style="max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 5px solid #003366;">
        <h2 style="color: #003366; margin-top: 0;">
            <i class="fas fa-paper-plane"></i> Message to Parent
        </h2>
        <p style="font-size: 0.9rem; color: #666;">Student ID: <strong><?php echo htmlspecialchars($child_id); ?></strong></p>
        
        <form method="POST">
            <input type="hidden" name="child_id" value="<?php echo htmlspecialchars($child_id); ?>">
            <textarea name="message" required 
                      style="width: 100%; height: 120px; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;" 
                      placeholder="Type your private message to the parent here..."></textarea>
            
            <button type="submit" style="width: 100%; background: #003366; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 1rem;">
                Send Message
            </button>
            
            <div style="text-align: center; margin-top: 15px;">
                <a href="../headmaster_portal.php" style="color: #666; text-decoration: none; font-size: 0.9rem;">
                    <i class="fas fa-times"></i> Cancel and Go Back
                </a>
            </div>
        </form>
    </div>

</body>
</html>