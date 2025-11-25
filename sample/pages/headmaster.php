<?php


// Only allow headmaster
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'headmaster') {
    header("Location: ?page=login");
    exit;
}

// Include header


// Load JSON data
$parents = json_decode(file_get_contents(__DIR__."/../data/parents.json"), true);
$admissions = json_decode(file_get_contents(__DIR__."/../data/admissions.json"), true);
?>

<div class="w3-container w3-padding">
    <h2>Headmaster Portal</h2>
    <p>Welcome, <?php echo $_SESSION['user']['name']; ?></p>

    <h3>Registered Parents</h3>
    <table class="w3-table-all w3-card-4 w3-hoverable w3-small">
        <thead>
            <tr class="w3-pale-red">
                <th>Parent Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Children</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($parents as $parent): ?>
            <tr>
                <td><?php echo htmlspecialchars($parent['name']); ?></td>
                <td><?php echo htmlspecialchars($parent['email']); ?></td>
                <td><?php echo isset($parent['contact']) ? htmlspecialchars($parent['contact']) : '-'; ?></td>
                <td>
                    <?php
                    $children = array_filter($admissions, function($c) use ($parent) {
                        return $c['parent_email'] === $parent['email'];
                    });
                    if ($children) {
                        echo "<ul>";
                        foreach ($children as $child) {
                            $dob = new DateTime($child['dob']);
                            $age = $dob->diff(new DateTime())->y;
                            echo "<li>" . htmlspecialchars($child['name']) . " (DOB: " . $child['dob'] . ", Age: $age, Admission: " . $child['admission_date'] . ")</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "-";
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

 

