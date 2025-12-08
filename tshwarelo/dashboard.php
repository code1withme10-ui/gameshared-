<?php
session_start();

$data_file = __DIR__ . '/storage/users.json';

// --------------------- Functions ---------------------
function get_users($file) {
    if (!file_exists($file) || filesize($file) == 0) return [];
    return json_decode(file_get_contents($file), true);
}

function save_users($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Generate unique child ID
function generate_child_id() {
    return "child-" . uniqid();
}

$users = get_users($data_file);

// Not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$current_email = $_SESSION['email'];
$isHeadmaster = isset($_SESSION["role"]) && $_SESSION["role"] === "headmaster";

$current_user_key = null;
$current_user = null;

// Parent login lookup (supports old + new formats)
if (!$isHeadmaster) {
    foreach ($users as $k => $u) {
        
        // Old structure (parentName, email)
        if (isset($u["email"]) && $u["email"] === $current_email) {
            $current_user = $u;
            $current_user_key = $k;
            break;
        }

        // New admission structure (parent -> email)
        if (isset($u["parent"]["email"]) && $u["parent"]["email"] === $current_email) {
            $current_user = $u;
            $current_user_key = $k;
            break;
        }
    }

    // Convert single child into "children" array (for backward support)
    if ($current_user) {
        if (isset($current_user['child']) && !isset($current_user['children'])) {
            $current_user['children'] = [$current_user['child']];
        } elseif (isset($current_user['child'])) {
            array_unshift($current_user['children'], $current_user['child']);
        }
    }
}

// --------------------- Add Child ---------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addChild']) && !$isHeadmaster) {

    $childName = $_POST['newChildName'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $grade = $_POST['grade'];
    $address = $current_user["parent"]["address"] ?? ($current_user["address"] ?? "N/A");

    if (!isset($_FILES['birthCert']) || $_FILES['birthCert']['error'] !== 0) {
        die("Birth certificate is required.");
    }

    $ext = pathinfo($_FILES['birthCert']['name'], PATHINFO_EXTENSION);
    $fileName = "birth_" . uniqid() . "." . $ext;
    $uploadPath = "uploads/" . $fileName;
    move_uploaded_file($_FILES['birthCert']['tmp_name'], $uploadPath);

    // Age calculation
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $ageInterval = $today->diff($birthDate);
    $ageMonths = ($ageInterval->y * 12) + $ageInterval->m;

    // Ensure children array exists
    if (!isset($users[$current_user_key]['children'])) {
        $users[$current_user_key]['children'] = [];
    }

    // --------------------- ADD CHILD WITH ID ---------------------
    $users[$current_user_key]['children'][] = [
        "childId" => generate_child_id(),
        "name" => $childName,
        "dob" => $dob,
        "gender" => $gender,
        "grade" => $grade,
        "address" => $address,
        "ageMonths" => $ageMonths,
        "birthCertificate" => $fileName,
        "status" => "pending"
    ];

    save_users($data_file, $users);

    header("Location: index.php?page=dashboard&child_added=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Humulani Pre School</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="container">
    <h1>
        Welcome,
        <?php 
            if ($isHeadmaster) {
                echo "Headmaster";
            } else {
                echo htmlspecialchars(
                    $current_user["parentName"] 
                    ?? ($current_user["parent"]["name"] ?? "Parent")
                );
            }
        ?>
    </h1>

    <hr>

<?php if (!$isHeadmaster): ?>

    <h2>Your Registered Children</h2>

    <?php 
        $children = $current_user["children"] ?? [];
        if (!empty($children)): 
    ?>
        <ul>
            <?php foreach ($children as $child): ?>
                <?php 
                    $cname = htmlspecialchars($child['name'] ?? 'Unknown');
                    $cgender = htmlspecialchars($child['gender'] ?? 'N/A');
                    $cgrade = htmlspecialchars($child['grade'] ?? 'N/A');
                    $cdob = htmlspecialchars($child['dob'] ?? 'N/A');

                    // Status color logic
                    $rawStatus = strtolower($child['status'] ?? 'pending');
                    $color = 'yellow';

                    if ($rawStatus === 'admitted' || $rawStatus === 'approved') $color = 'green';
                    if ($rawStatus === 'rejected') $color = 'red';
                ?>
                <li>
                    <strong><?php echo $cname; ?></strong><br>
                    Gender: <?php echo $cgender; ?><br>
                    Grade: <?php echo $cgrade; ?><br>
                    DOB: <?php echo $cdob; ?><br>

                    <!-- Colored Status -->
                    Status: 
                    <strong style="color: <?php echo $color; ?>;">
                        <?php echo ucfirst($rawStatus); ?>
                    </strong>
                    <br>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No registered children yet.</p>
    <?php endif; ?>

    <hr>

    <h2>Add Another Child</h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="addChild" value="1">

        <label>Full Name:</label>
        <input type="text" name="newChildName" required><br><br>

        <label>Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br><br>

        <label>Gender:</label><br>
        <label><input type="radio" name="gender" value="Male" required> Male</label>
        <label><input type="radio" name="gender" value="Female" required> Female</label>
        <br><br>

        <label>Grade Applying For:</label>
        <select id="grade" name="grade" required>
            <option value="">Select</option>
            <option value="Infants">Infants (6–12 months)</option>
            <option value="Toddlers">Toddlers (1–3 years)</option>
            <option value="Playgroup">Playgroup (3–4 years)</option>
            <option value="Pre-School">Pre-School (4–5 years)</option>
        </select>
        <br><br>

        <label>Birth Certificate:</label>
        <input type="file" name="birthCert" accept=".pdf,.jpg,.jpeg,.png" required><br><br>

        <button type="submit">Add Child</button>
    </form>

<?php endif; ?>

</div>

<footer>
    <p>© 2026 Humulani Pre School</p>
</footer>

<script>
function calculateAge(dob) {
    const today = new Date();
    const birth = new Date(dob);
    let years = today.getFullYear() - birth.getFullYear();
    let months = today.getMonth() - birth.getMonth();
    if (months < 0) { years--; months += 12; }
    return { years, months };
}

document.getElementById("dob").addEventListener("change", function() {
    const age = calculateAge(this.value);
    const grade = document.getElementById("grade");
    const options = grade.options;

    for (let i = 0; i < options.length; i++) options[i].disabled = false;

    if (!(age.years === 0 && age.months >= 6 && age.months <= 12)) options[1].disabled = true;
    if (!(age.years >= 1 && age.years <= 3)) options[2].disabled = true;
    if (!(age.years >= 3 && age.years <= 4)) options[3].disabled = true;
    if (!(age.years >= 4 && age.years <= 5)) options[4].disabled = true;

    grade.value = "";
});
</script>

</body>
</html>
