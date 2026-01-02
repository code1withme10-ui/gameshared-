<?php
session_start();

// ----------------- Paths -----------------
$users_file = __DIR__ . '/storage/users.json';

// ----------------- Load/Save Functions -----------------
function get_users($file) {
    if (!file_exists($file) || filesize($file) == 0) return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

function save_users($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT), LOCK_EX);
}

// ----------------- Security -----------------
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'headmaster') {
    header('Location: login.php');
    exit;
}

// ----------------- Load users -----------------
$users = get_users($users_file);
$message = '';

/* -------------------------------------------------------
   HANDLE ADMIT / REJECT ACTIONS
------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action'], $_POST['childID'], $_POST['parentEmail'])) {

    $action = $_POST['action'];
    $childID = $_POST['childID'];
    $parentEmail = $_POST['parentEmail'];

    $newStatus = ($action === 'admit') ? 'admitted' : 'rejected';
    $updated = false;

    foreach ($users as $uIndex => $user) {

        // locate correct parent
        if (!isset($user['parent']['email']) || $user['parent']['email'] !== $parentEmail) {
            continue;
        }

        // children exist?
        if (!isset($user['children']) || !is_array($user['children'])) {
            continue;
        }

        // find exact child by childId (case-insensitive)
        foreach ($user['children'] as $cIndex => $child) {

            if (strcasecmp($child['childId'] ?? "", $childID) === 0) {
                $users[$uIndex]['children'][$cIndex]['status'] = $newStatus;
                $updated = true;
                break 2;
            }
        }
    }

    if ($updated) {
        save_users($users_file, $users);
        $message = "Child status updated to '{$newStatus}'.";
    } else {
        $message = "❌ Could not update — child not found.";
    }
}

/* -------------------------------------------------------
   BUILD CHILD LIST FOR TABLE
------------------------------------------------------- */
$all_children = [];

foreach ($users as $user) {

    if (($user['role'] ?? '') === 'headmaster') continue;

    $parentName = $user['parent']['name'] ?? $user['parentName'] ?? "Parent";
    $parentEmail = $user['parent']['email'] ?? $user['email'] ?? "";

    if (!isset($user['children']) || !is_array($user['children'])) continue;

    foreach ($user['children'] as $child) {

        // Some children added from dashboard don't have childId → FIX
        if (!isset($child['childId'])) {
            $child['childId'] = "child-".uniqid();
        }

        $all_children[] = [
            "childId" => $child["childId"],
            "name" => $child["name"] ?? "N/A",
            "dob" => $child["dob"] ?? null,
            "gender" => $child["gender"] ?? "N/A",
            "grade" => $child["grade"] ?? "N/A",
            "status" => strtolower($child["status"] ?? "pending"),
            "documents" => $child["documents"] ?? [],
            "birthCertificate" => $child["birthCertificate"] ?? null,
            "parentName" => $parentName,
            "parentEmail" => $parentEmail
        ];
    }
}

/* -------------------------------------------------------
   AGE FORMATTER
------------------------------------------------------- */
function formatAge($dob) {
    if (!$dob) return 'N/A';
    try {
        $birth = new DateTime($dob);
        $today = new DateTime();
        $diff = $today->diff($birth);
        $out = '';
        if ($diff->y > 0) $out .= $diff->y . ' years ';
        if ($diff->m > 0) $out .= $diff->m . ' months';
        return trim($out);
    } catch (Exception $e) {
        return 'N/A';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Headmaster Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Headmaster Dashboard</h1>

<?php if($message): ?>
<p style="color: green; font-weight:bold;">
    <?= htmlspecialchars($message) ?>
</p>
<?php endif; ?>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Child Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Grade</th>
            <th>Parent</th>
            <th>Status</th>
            <th>Documents</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

<?php foreach ($all_children as $child): ?>
<tr>
    <td><?= htmlspecialchars($child['name']) ?></td>
    <td><?= formatAge($child['dob']) ?></td>
    <td><?= htmlspecialchars($child['gender']) ?></td>
    <td><?= htmlspecialchars($child['grade']) ?></td>
    <td><?= htmlspecialchars($child['parentName']) ?></td>
    <td><?= ucfirst($child['status']) ?></td>

    <td>
        <?php
        if (!empty($child['documents'])) {
            foreach ($child['documents'] as $doc => $file) {
                echo "<a href='uploads/".htmlspecialchars($file)."' target='_blank'>$doc</a><br>";
            }
        } elseif ($child['birthCertificate']) {
            echo "<a href='uploads/".htmlspecialchars($child['birthCertificate'])."' target='_blank'>Birth Certificate</a>";
        } else {
            echo "N/A";
        }
        ?>
    </td>

    <td>
        <?php if ($child['status'] === "pending"): ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="childID" value="<?= htmlspecialchars($child['childId']) ?>">
                <input type="hidden" name="parentEmail" value="<?= htmlspecialchars($child['parentEmail']) ?>">
                <button name="action" value="admit" style="background:green;color:white;">Admit</button>
            </form>

            <form method="POST" style="display:inline;">
                <input type="hidden" name="childID" value="<?= htmlspecialchars($child['childId']) ?>">
                <input type="hidden" name="parentEmail" value="<?= htmlspecialchars($child['parentEmail']) ?>">
                <button name="action" value="reject" style="background:red;color:white;">Reject</button>
            </form>

        <?php else: ?>
            Status Finalized
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

    </tbody>
</table>

</body>
</html>
