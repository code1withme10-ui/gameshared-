<?php
include __DIR__ . '/../includes/menu-bar.php';
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Headmaster Dashboard</h2>
    <p>Review and manage applications submitted by parents.</p>

    <?php
    // Load children and parents
    $children = readJSON(__DIR__ . '/../../data/children.json');
    $parents = readJSON(__DIR__ . '/../../data/parents.json');

    // Handle approve/decline actions
    if(isset($_GET['approve']) || isset($_GET['decline'])) {
        $index = $_GET['approve'] ?? $_GET['decline'];
        $index = intval($index);

        if(isset($children[$index])) {
            $children[$index]['status'] = isset($_GET['approve']) ? 'Approved' : 'Declined';
            writeJSON(__DIR__ . '/../../data/children.json', $children);
            echo '<p style="color:green; font-weight:bold;">Application status updated successfully!</p>';
        }
    }
    ?>

    <?php if(empty($children)): ?>
        <p>No applications found.</p>
    <?php else: ?>
        <?php foreach ($children as $i => $child): ?>
            <?php
                // Get parent info
                $parent_info = null;
                foreach ($parents as $p) {
                    if(isset($p['username']) && $p['username'] === $child['parent_username']) {
                        $parent_info = $p;
                        break;
                    }
                }

                // Highlight new applications (Awaiting approval)
                $card_style = $child['status'] === 'Awaiting approval' ? "border:2px solid orange; padding:10px; margin-bottom:15px;" :
                              ($child['status'] === 'Approved' ? "border:2px solid green; padding:10px; margin-bottom:15px;" :
                              "border:2px solid red; padding:10px; margin-bottom:15px;");
            ?>
            <div class="card" style="<?= $card_style ?>">
                <h3><?= htmlspecialchars($child['child_name']) ?></h3>
                <?php if($parent_info): ?>
                    <b>Parent Name:</b> <?= htmlspecialchars($parent_info['full_name']) ?><br>
                    <b>Relationship:</b> <?= htmlspecialchars($parent_info['relationship']) ?><br>
                    <b>Email:</b> <?= htmlspecialchars($parent_info['email']) ?><br>
                    <b>Phone:</b> <?= htmlspecialchars($parent_info['phone']) ?><br>
                    <b>Address:</b> <?= htmlspecialchars($parent_info['address']) ?><br>
                <?php else: ?>
                    <b>Parent Username:</b> <?= htmlspecialchars($child['parent_username']) ?><br>
                <?php endif; ?>
                <b>Date of Birth:</b> <?= htmlspecialchars($child['dob']) ?><br>
                <b>Gender:</b> <?= htmlspecialchars($child['gender']) ?><br>
                <b>Grade Category:</b> <?= htmlspecialchars($child['grade_category']) ?><br>
                <b>Status:</b> <span style="color:<?= $child['status']=='Approved'?'green':($child['status']=='Declined'?'red':'orange') ?>"><?= htmlspecialchars($child['status']) ?></span><br><br>

                <b>Documents:</b><br>
                <?php if(isset($child['birth_certificate']) && file_exists($child['birth_certificate'])): ?>
                    <a href="<?= htmlspecialchars($child['birth_certificate']) ?>" target="_blank">Birth Certificate</a><br>
                <?php endif; ?>
                <?php if(isset($child['parent_id']) && file_exists($child['parent_id'])): ?>
                    <a href="<?= htmlspecialchars($child['parent_id']) ?>" target="_blank">Parent/Guardian ID</a><br>
                <?php endif; ?>
                <br>
                <?php if($child['status'] === 'Awaiting approval'): ?>
                    <a class="button" href="index.php?page=headmaster_dashboard&approve=<?= $i ?>">Approve</a>
                    <a class="button" style="background:red;" href="index.php?page=headmaster_dashboard&decline=<?= $i ?>">Decline</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>



