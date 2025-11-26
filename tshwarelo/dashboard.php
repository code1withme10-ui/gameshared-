<?php
session_start();

$data_file = "users.json";

// Load all users
function get_users($file) {
    if (!file_exists($file) || filesize($file) == 0) return [];
    return json_decode(file_get_contents($file), true);
}

function save_users($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
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

if (!$isHeadmaster) {
    foreach ($users as $k => $u) {
        if ($u["parent"]["email"] === $current_email) {
            $current_user = $u;
            $current_user_key = $k;
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addChild']) && !$isHeadmaster) {

    $childName = $_POST['newChildName'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $grade = $_POST['grade'];
    $address = $current_user["parent"]["address"];

    // Upload birth certificate
    if (!isset($_FILES['birthCert']) || $_FILES['birthCert']['error'] !== 0) {
        die("Birth certificate is required.");
    }

    $ext = pathinfo($_FILES['birthCert']['name'], PATHINFO_EXTENSION);
    $fileName = "birth_" . uniqid() . "." . $ext;
    $uploadPath = "uploads/" . $fileName;
    move_uploaded_file($_FILES['birthCert']['tmp_name'], $uploadPath);

    // Calculate age in months
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $ageInterval = $today->diff($birthDate);
    $ageMonths = ($ageInterval->y * 12) + $ageInterval->m;

    // Save child
    $users[$current_user_key]["children"][] = [
        "name" => $childName,
        "dob" => $dob,
        "gender" => $gender,
        "grade" => $grade,
        "address" => $address,
        "ageMonths" => $ageMonths,
        "birthCertificate" => $fileName
    ];

    save_users($data_file, $users);

    header("Location: dashboard.php?child_added=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Humulani Pre School</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVIGATION BAR -->
<nav class="navbar">
    <div class="container">
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1>
        Welcome,
        <?php echo $isHeadmaster ? "Headmaster" : htmlspecialchars($current_user["parent"]["name"]); ?>
    </h1>

    <hr>

<?php if (!$isHeadmaster): ?>

    <h2>Your Registered Children</h2>

    <?php if (!empty($current_user["children"])): ?>
        <ul>
            <?php foreach ($current_user["children"] as $child): ?>
                <li>
                    <strong><?php echo htmlspecialchars($child["name"]); ?></strong><br>
                    Gender: <?php echo $child["gender"]; ?><br>
                    Grade: <?php echo $child["grade"]; ?><br>
                    DOB: <?php echo $child["dob"]; ?><br>
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
        <label class="radio"><input type="radio" name="gender" value="Male" required> Male</label>
        <label class="radio"><input type="radio" name="gender" value="Female" required> Female</label>
        <br><br>

        <label>Grade Applying For:</label>
        <select id="grade" name="grade" required>
            <option value="">Select Category</option>
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

<!-- FOOTER -->
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
