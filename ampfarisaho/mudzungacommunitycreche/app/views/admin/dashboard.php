<?php

require_once __DIR__ . '/../../middleware/auth.php';
requireRole('headmaster');
require_once __DIR__ . '/../../services/JsonStorage.php';

/* ----------------------------
   HANDLE WALK-IN REGISTRATION
---------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['parent_full_name'], $_POST['child_full_name'])) {

    $parentsStorage = new JsonStorage(__DIR__ . '/../../../storage/parents.json');
    $childrenStorage = new JsonStorage(__DIR__ . '/../../../storage/children.json');

    $parents = $parentsStorage->read() ?? [];
    $children = $childrenStorage->read() ?? [];

    // Handle Parent ID Card Upload
    $parentIdCardUrl = '';
    if (isset($_FILES['parent_id_card']) && $_FILES['parent_id_card']['error'] === 0) {
        $ext = pathinfo($_FILES['parent_id_card']['name'], PATHINFO_EXTENSION);
        $filename = 'parentID_' . bin2hex(random_bytes(5)) . '.' . $ext;
        $destination = __DIR__ . '/../../../storage/uploads/' . $filename;
        move_uploaded_file($_FILES['parent_id_card']['tmp_name'], $destination);
        $parentIdCardUrl = '/storage/uploads/' . $filename;
    }

    // Save Parent
    $parentId = 'parent_' . bin2hex(random_bytes(5));
    $parent = [
        'id' => $parentId,
        'full_name' => $_POST['parent_full_name'],
        'address' => $_POST['parent_address'],
        'email' => $_POST['parent_email'] ?? '',
        'phone' => $_POST['parent_phone'],
        'role_to_child' => $_POST['parent_role'],
        'password' => password_hash(bin2hex(random_bytes(4)), PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s'),
        'role' => 'parent',
        'documents' => [
            'parent_id_card' => $parentIdCardUrl
        ]
    ];
    $parents[] = $parent;
    $parentsStorage->write($parents);

    // Handle Child File Uploads
    $birthCertUrl = '';
    if (isset($_FILES['child_birth_certificate']) && $_FILES['child_birth_certificate']['error'] === 0) {
        $ext = pathinfo($_FILES['child_birth_certificate']['name'], PATHINFO_EXTENSION);
        $filename = 'birth_' . bin2hex(random_bytes(5)) . '.' . $ext;
        $destination = __DIR__ . '/../../../storage/uploads/' . $filename;
        move_uploaded_file($_FILES['child_birth_certificate']['tmp_name'], $destination);
        $birthCertUrl = '/storage/uploads/' . $filename;
    }

    $clinicalReportUrl = '';
    if (isset($_FILES['child_clinical_report']) && $_FILES['child_clinical_report']['error'] === 0) {
        $ext = pathinfo($_FILES['child_clinical_report']['name'], PATHINFO_EXTENSION);
        $filename = 'clinical_' . bin2hex(random_bytes(5)) . '.' . $ext;
        $destination = __DIR__ . '/../../../storage/uploads/' . $filename;
        move_uploaded_file($_FILES['child_clinical_report']['tmp_name'], $destination);
        $clinicalReportUrl = '/storage/uploads/' . $filename;
    }

    // Save Child
    $childId = 'child_' . bin2hex(random_bytes(5));
    $child = [
        'id' => $childId,
        'parent_id' => $parentId,
        'full_name' => $_POST['child_full_name'],
        'address' => $_POST['child_address'],
        'dob' => $_POST['child_dob'],
        'gender' => $_POST['child_gender'],
        'category' => $_POST['child_category'],
        'allergies' => $_POST['child_allergies'] ?? '',
        'status' => 'accepted',
        'submitted_at' => date('Y-m-d H:i:s'),
        'documents' => [
            'birth_certificate' => $birthCertUrl,
            'clinical_report' => $clinicalReportUrl
        ]
    ];
    $children[] = $child;
    $childrenStorage->write($children);

    // Refresh to show new walk-in
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* ----------------------------
   LOAD CHILDREN
---------------------------- */
$childrenStorage = new JsonStorage(__DIR__ . '/../../../storage/children.json');
$children = $childrenStorage->read() ?? [];

/* ----------------------------
   ACCEPT / DECLINE
---------------------------- */
if (isset($_GET['action']) && isset($_GET['id'])) {

    foreach ($children as &$child) {
        if ($child['id'] === $_GET['id']) {
            if ($_GET['action'] === 'accept') $child['status'] = 'accepted';
            if ($_GET['action'] === 'decline') $child['status'] = 'declined';
        }
    }
    $childrenStorage->write($children);
    header("Location: dashboard.php");
    exit;
}

/* ----------------------------
   LOAD PARENTS
---------------------------- */
$parentsStorage = new JsonStorage(__DIR__ . '/../../../storage/parents.json');
$parents = $parentsStorage->read() ?? [];

/* ----------------------------
   DASHBOARD STATS
---------------------------- */
$total = count($children);
$pending = count(array_filter($children, fn($c) => $c['status'] === 'pending'));
$accepted = count(array_filter($children, fn($c) => $c['status'] === 'accepted'));
$declined = count(array_filter($children, fn($c) => $c['status'] === 'declined'));

/* ----------------------------
   FILTER
---------------------------- */
$statusFilter = $_GET['status'] ?? '';
if ($statusFilter) $children = array_filter($children, fn($c) => $c['status'] === $statusFilter);

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
?>

<div class="container">

<h2 style="margin-bottom:30px;">Headmaster Dashboard</h2>

<div style="margin-bottom:20px; display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px;">
    <a href="#" class="btn btn-primary" onclick="openWalkInModal()">Walk-in Reg</a>
    <div>
        <a href="dashboard.php" class="btn btn-primary">All</a>
        <a href="?status=pending" class="btn btn-warning">Pending</a>
        <a href="?status=accepted" class="btn btn-success">Accepted</a>
        <a href="?status=declined" class="btn btn-danger">Declined</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:30px;">
    <div class="card" style="padding:20px;text-align:center;"><h3>Total Applications</h3><p style="font-size:28px;font-weight:bold;"><?php echo $total; ?></p></div>
    <div class="card" style="padding:20px;text-align:center;background:#fff3cd;"><h3>Pending</h3><p style="font-size:28px;font-weight:bold;"><?php echo $pending; ?></p></div>
    <div class="card" style="padding:20px;text-align:center;background:#d4edda;"><h3>Accepted</h3><p style="font-size:28px;font-weight:bold;"><?php echo $accepted; ?></p></div>
    <div class="card" style="padding:20px;text-align:center;background:#f8d7da;"><h3>Declined</h3><p style="font-size:28px;font-weight:bold;"><?php echo $declined; ?></p></div>
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
<td><?php echo htmlspecialchars($child['grade'] ?? $child['category'] ?? ''); ?></td>
<td>
<span style="padding:5px 12px;border-radius:5px;color:white;background:
<?php echo $child['status'] === 'pending' ? '#f39c12' : ($child['status'] === 'accepted' ? '#27ae60' : '#c0392b'); ?>;">
<?php echo ucfirst($child['status']); ?>
</span>
</td>
<td>
<?php if (!empty($child['documents'])): ?>
<?php if(!empty($child['documents']['birth_certificate'])): ?>
<button class="doc-btn" onclick="openDoc('<?php echo $child['documents']['birth_certificate']; ?>')">Birth</button>
<?php endif; ?>
<?php if(!empty($child['documents']['clinical_report'])): ?>
<button class="doc-btn" onclick="openDoc('<?php echo $child['documents']['clinical_report']; ?>')">Clinical</button>
<?php endif; ?>
<?php else: ?>
<span>No files</span>
<?php endif; ?>
</td>
<td>
<?php if ($child['status'] === 'pending'): ?>
<a href="?action=accept&id=<?php echo $child['id']; ?>" class="action-btn accept">Accept</a>
<a href="?action=decline&id=<?php echo $child['id']; ?>" class="action-btn decline">Decline</a>
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

<!-- MODAL: WALK-IN REGISTRATION -->
<div id="walkInModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);justify-content:center;align-items:center;">
    <div style="width:600px;background:white;padding:30px;border-radius:10px;position:relative;overflow-y:auto;max-height:90%;">
        <button onclick="closeWalkInModal()" style="position:absolute;top:10px;right:10px;">Close</button>
        <h3 style="margin-bottom:20px;">Walk-in Registration</h3>

        <form method="POST" enctype="multipart/form-data" id="walkInForm">
            <h4>Parent Info</h4>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="parent_full_name" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="parent_address" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="parent_phone" required>
            </div>
            <div class="form-group">
                <label>Email (optional)</label>
                <input type="email" name="parent_email">
            </div>
            <div class="form-group">
                <label>Role to Child</label>
                <select name="parent_role" required>
                    <option value="">Select role</option>
                    <option value="Father">Father</option>
                    <option value="Mother">Mother</option>
                    <option value="Guardian">Guardian</option>
                </select>
            </div>
            <div class="form-group">
                <label>Parent ID Card</label>
                <input type="file" name="parent_id_card" accept=".pdf,.jpg,.png" required>
            </div>

            <h4>Child Info</h4>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="child_full_name" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="child_address" required>
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="child_dob" required>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="child_gender" required>
                    <option value="">Select gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="child_category" required>
                    <option value="">Select category</option>
                    <option value="Infants">Infants</option>
                    <option value="Toddler">Toddler</option>
                    <option value="Preschool">Preschool</option>
                </select>
            </div>
            <div class="form-group">
                <label>Allergies (optional)</label>
                <input type="text" name="child_allergies">
            </div>
            <div class="form-group">
                <label>Child Birth Certificate</label>
                <input type="file" name="child_birth_certificate" accept=".pdf,.jpg,.png" required>
            </div>
            <div class="form-group">
                <label>Child Medical / Clinical Report (optional)</label>
                <input type="file" name="child_clinical_report" accept=".pdf,.jpg,.png">
            </div>

            <button type="submit" class="btn btn-success">Submit Walk-in</button>
        </form>
    </div>
</div>

<!-- DOCUMENT MODAL -->
<div id="docModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);justify-content:center;align-items:center;">
    <div style="width:80%;height:80%;background:white;position:relative;">
        <button onclick="closeDoc()" style="position:absolute;top:10px;right:10px;">Close</button>
        <iframe id="docFrame" style="width:100%;height:100%;border:none;"></iframe>
    </div>
</div>

<script>
function openWalkInModal(){ document.getElementById("walkInModal").style.display = "flex"; }
function closeWalkInModal(){ document.getElementById("walkInModal").style.display = "none"; }
function openDoc(url){ document.getElementById("docFrame").src = url; document.getElementById("docModal").style.display = "flex"; }
function closeDoc(){ document.getElementById("docModal").style.display = "none"; document.getElementById("docFrame").src = ""; }
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>


