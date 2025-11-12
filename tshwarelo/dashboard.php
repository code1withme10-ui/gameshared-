<?php
session_start();
$data_file = 'users.json'; // The file where all user data is stored

// ----------------------------------------------------
// Functions to read/write JSON data (Since helpers were removed)
// ----------------------------------------------------
function get_users($file) {
    if (!file_exists($file) || filesize($file) == 0) {
        return [];
    }
    $data = json_decode(file_get_contents($file), true);
    if (!is_array($data)) {
        return [];
    }
    return $data;
}

function save_users($file, $users) {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

function find_current_user_data($users, $email) {
    foreach ($users as $index => $user) {
        if ($user['email'] === $email) {
            return [
                'user' => $user,
                'index' => $index
            ];
        }
    }
    return null;
}
// ----------------------------------------------------

// --- Security Check: Ensure user is logged in AND is not the Headmaster ---
if (!isset($_SESSION['user_email']) || (isset($_SESSION['role']) && $_SESSION['role'] === 'headmaster')) {
    header('Location: login.php');
    exit;
}

$users = get_users($data_file);
$current_user_data = find_current_user_data($users, $_SESSION['user_email']);

if (!$current_user_data) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$current_user_index = $current_user_data['index'];
$current_user = $current_user_data['user'];

$message = '';

// --- Handle Add Child Form Submission ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addChild'])) {
    $newChildName = $_POST['newChildName'] ?? '';
    $newAge = $_POST['newAge'] ?? 0;
    $newGender = $_POST['newGender'] ?? '';

    if ($current_user) {
        $new_child = [
            'childId' => uniqid('child-'),
            'name' => $newChildName,
            'age' => (int)$newAge,
            'gender' => $newGender,
            'reports' => [],
            'status' => 'pending' // New child also starts as pending
        ];

        // Add the new child to the current user's array in the main $users structure
        $users[$current_user_index]['children'][] = $new_child;
        save_users($data_file, $users); // Save the entire array back
        
        $message = '<p style="color: green;">' . htmlspecialchars($newChildName) . ' has been successfully added! Admission status is Pending.</p>';
        // IMPORTANT: Re-fetch the updated user object for display
        $current_user = $users[$current_user_index]; 
    }
}

// --- Handle Logout ---
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}

// PHP logic to check login status for the navbar
$is_logged_in = isset($_SESSION['user_email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    
    <div class="navbar">
        <span class="navbar-title">Humulani Pre School</span>
        <div class="navbar-links">
            <a href="index.php">Home</a> 
            <a href="about.php">About Us</a>
            <a href="gallery.php">Gallery</a>
            <a href="admission.php">Admission</a>
            <a href="contact.php">Contact</a>
            
            <?php if ($is_logged_in): ?>
                <a href="dashboard.php?action=logout">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="page-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['parent_name'] ?? 'Parent'); ?>!</h1>

        <hr>
        <?php echo $message; ?>
        <h2>Your Children</h2>
        
        <div id="childrenList">
            <?php if ($current_user && !empty($current_user['children'])): ?>
                <?php foreach ($current_user['children'] as $child): ?>
                    <div>
                        <h3>Child: <?php echo htmlspecialchars($child['name']); ?></h3>
                        <p>Age: <?php echo htmlspecialchars($child['age']); ?></p>
                        <p>Gender: <?php echo htmlspecialchars($child['gender']); ?></p>
                        
                        <p>
                            <strong>Admission Status:</strong> 
                            <span class="status-<?php echo strtolower($child['status'] ?? 'pending'); ?>">
                                <?php echo htmlspecialchars(ucfirst($child['status'] ?? 'Pending')); ?>
                            </span>
                        </p>

                        <h4>Reports:</h4>
                        <?php if (!empty($child['reports'])): ?>
                            <ul>
                                <?php foreach ($child['reports'] as $report): ?>
                                    <li>
                                        <strong>Date: <?php echo htmlspecialchars($report['date'] ?? 'N/A'); ?></strong> | 
                                        Grade: <?php echo htmlspecialchars($report['grade'] ?? 'N/A'); ?> | 
                                        Notes: <?php echo htmlspecialchars($report['notes'] ?? 'No notes.'); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No reports available yet.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You have not registered any children yet. Use the form below to add your first child.</p>
            <?php endif; ?>
        </div>

        <hr>

        <h2>Add Another Child</h2>
        <form method="POST" action="dashboard.php">
            <input type="hidden" name="addChild" value="1">
            <label for="newChildName">Child's Name:</label>
            <input type="text" id="newChildName" name="newChildName" required><br><br>

            <label for="newAge">Age:</label>
            <input type="number" id="newAge" name="newAge" min="1" max="6" required><br><br>

            <label for="newGender">Gender:</label>
            <select id="newGender" name="newGender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select><br><br>

            <button type="submit">Add Child</button>
        </form>
            </div>

        <footer>
            <p>&copy; 2026 Humulani Pre School</p>
        </footer>
    </div>
</body>
</html>