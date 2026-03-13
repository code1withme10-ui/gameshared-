<?php

require_once __DIR__ . '/../../middleware/auth.php';
requireRole('headmaster');

require_once __DIR__ . '/../../services/JsonStorage.php';

/* LOAD CHILDREN */

$childrenStorage = new JsonStorage(__DIR__ . '/../../../storage/children.json');
$children = $childrenStorage->read();

/* ACCEPT / DECLINE */

if (isset($_GET['action']) && isset($_GET['id'])) {

    foreach ($children as &$child) {

        if ($child['id'] === $_GET['id']) {

            if ($_GET['action'] === 'accept') {
                $child['status'] = 'accepted';
            }

            if ($_GET['action'] === 'decline') {
                $child['status'] = 'declined';
            }

        }

    }

    $childrenStorage->write($children);

    header("Location: dashboard.php");
    exit;
}

/* LOAD PARENTS */

$parentsStorage = new JsonStorage(__DIR__ . '/../../../storage/parents.json');
$parents = $parentsStorage->read();

/* DASHBOARD STATS */

$total = count($children);
$pending = count(array_filter($children, fn($c) => $c['status'] === 'pending'));
$accepted = count(array_filter($children, fn($c) => $c['status'] === 'accepted'));
$declined = count(array_filter($children, fn($c) => $c['status'] === 'declined'));

/* FILTER */

$statusFilter = $_GET['status'] ?? '';

if ($statusFilter) {
    $children = array_filter($children, fn($c) => $c['status'] === $statusFilter);
}

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
?>

<div class="container">

<h2 style="margin-bottom:30px;">Headmaster Dashboard</h2>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:30px;">

<div class="card" style="padding:20px;text-align:center;">
<h3>Total Applications</h3>
<p style="font-size:28px;font-weight:bold;"><?php echo $total; ?></p>
</div>

<div class="card" style="padding:20px;text-align:center;background:#fff3cd;">
<h3>Pending</h3>
<p style="font-size:28px;font-weight:bold;"><?php echo $pending; ?></p>
</div>

<div class="card" style="padding:20px;text-align:center;background:#d4edda;">
<h3>Accepted</h3>
<p style="font-size:28px;font-weight:bold;"><?php echo $accepted; ?></p>
</div>

<div class="card" style="padding:20px;text-align:center;background:#f8d7da;">
<h3>Declined</h3>
<p style="font-size:28px;font-weight:bold;"><?php echo $declined; ?></p>
</div>

</div>

<div style="margin-bottom:20px;display:flex;gap:10px;flex-wrap:wrap;">

<a href="dashboard.php" class="btn btn-primary">All</a>
<a href="?status=pending" class="btn btn-warning">Pending</a>
<a href="?status=accepted" class="btn btn-success">Accepted</a>
<a href="?status=declined" class="btn btn-danger">Declined</a>

</div>

<div class="card" style="padding:20px;overflow-x:auto;">

<?php if (!empty($children)): ?>

<table style="width:100%;border-collapse:collapse;">

<thead>

<tr style="background:#0a1f44;color:white;">

<th style="padding:10px;">Child Name</th>
<th>Parent Name</th>
<th>Grade</th>
<th>Status</th>
<th>Documents</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php foreach ($children as $child): ?>

<tr style="border-bottom:1px solid #ddd;">

<td style="padding:10px;"><?php echo htmlspecialchars($child['full_name']); ?></td>

<td>
<?php
$parentName = "Unknown";
foreach ($parents as $parent) {
    if ($parent['id'] === $child['parent_id']) {
        $parentName = $parent['full_name'];
        break;
    }
}
echo htmlspecialchars($parentName);
?>
</td>

<td><?php echo htmlspecialchars($child['grade']); ?></td>

<td>
<span style="padding:5px 12px;border-radius:5px;color:white;background:
<?php
echo $child['status'] === 'pending'
? '#f39c12'
: ($child['status'] === 'accepted'
? '#27ae60'
: '#c0392b');
?>;">
<?php echo ucfirst($child['status']); ?>
</span>
</td>

<td>

<?php if (!empty($child['documents'])): ?>

<button onclick="openDoc('<?php echo $child['documents']['birth_certificate']; ?>')">Birth</button>
<button onclick="openDoc('<?php echo $child['documents']['parent_id']; ?>')">Parent ID</button>
<button onclick="openDoc('<?php echo $child['documents']['clinical_report']; ?>')">Clinical</button>

<?php else: ?>

<span>No files</span>

<?php endif; ?>

</td>

<td>

<?php if ($child['status'] === 'pending'): ?>

<a href="?action=accept&id=<?php echo $child['id']; ?>" onclick="return confirm('Accept this application?')">Accept</a>

<a href="?action=decline&id=<?php echo $child['id']; ?>" onclick="return confirm('Decline this application?')">Decline</a>

<?php else: ?>

Completed

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<?php else: ?>

<p>No applications found.</p>

<?php endif; ?>

</div>

</div>

<div id="docModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);justify-content:center;align-items:center;">

<div style="width:80%;height:80%;background:white;position:relative;">

<button onclick="closeDoc()" style="position:absolute;top:10px;right:10px;">Close</button>

<iframe id="docFrame" style="width:100%;height:100%;border:none;"></iframe>

</div>

</div>

<script>

function openDoc(url){
document.getElementById("docFrame").src = url;
document.getElementById("docModal").style.display = "flex";
}

function closeDoc(){
document.getElementById("docModal").style.display = "none";
document.getElementById("docFrame").src = "";
}

</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>


