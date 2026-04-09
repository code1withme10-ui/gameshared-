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

    /* ----------------------------
       UPLOAD PARENT ID CARD
    ---------------------------- */
    $parentIdCardUrl = '';
    if (isset($_FILES['parent_id_card']) && $_FILES['parent_id_card']['error'] === 0) {
        $ext = pathinfo($_FILES['parent_id_card']['name'], PATHINFO_EXTENSION);
        $filename = 'parentID_' . bin2hex(random_bytes(5)) . '.' . $ext;
        $destination = __DIR__ . '/../../../storage/uploads/' . $filename;

        move_uploaded_file($_FILES['parent_id_card']['tmp_name'], $destination);
        $parentIdCardUrl = '/storage/uploads/' . $filename;
    }

    /* ----------------------------
       SAVE PARENT
    ---------------------------- */
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

    /* ----------------------------
       UPLOAD CHILD DOCUMENTS
    ---------------------------- */

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

    /* ----------------------------
       SAVE CHILD
    ---------------------------- */

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
        'emergency_contact' => [
            'name' => $_POST['child_emergency_contact_name'] ?? '',
            'phone' => $_POST['child_emergency_contact_phone'] ?? '',
            'relation' => $_POST['child_emergency_contact_relation'] ?? ''
        ],
        'status' => 'accepted',
        'application_source' => 'walk-in',
        'submitted_at' => date('Y-m-d H:i:s'),
        'documents' => [
            'birth_certificate' => $birthCertUrl,
            'clinical_report' => $clinicalReportUrl
        ]
    ];

    $children[] = $child;
    $childrenStorage->write($children);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* ----------------------------
   LOAD DATA
---------------------------- */

$childrenStorage = new JsonStorage(__DIR__ . '/../../../storage/children.json');
$children = $childrenStorage->read() ?? [];

$parentsStorage = new JsonStorage(__DIR__ . '/../../../storage/parents.json');
$parents = $parentsStorage->read() ?? [];

/* ----------------------------
   ACCEPT / DECLINE ACTION
---------------------------- */

if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] === 'accept') {

    foreach ($children as &$child) {
        if ($child['id'] === $_GET['id']) {
            $child['status'] = 'accepted';
        }
    }

    $childrenStorage->write($children);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'decline') {
    $declineId = $_POST['child_id'] ?? '';
    $declineReason = $_POST['decline_reason'] ?? '';

    foreach ($children as &$child) {
        if ($child['id'] === $declineId) {
            $child['status'] = 'declined';
            $child['rejection_reason'] = trim($declineReason);
        }
    }

    $childrenStorage->write($children);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

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

if ($statusFilter) {
    $children = array_filter($children, fn($c) => $c['status'] === $statusFilter);
}

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';

?>

<div class="container">

<h2 style="margin-bottom:30px;">Headmaster Dashboard</h2>

<div style="margin-bottom:20px; display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px;">
<a href="#" class="btn btn-primary" onclick="openWalkInModal()">Walk-in Reg</a>

<div>
<a href="/admin-dashboard.php" class="btn btn-primary">All</a>
<a href="?status=pending" class="btn btn-warning">Pending</a>
<a href="?status=accepted" class="btn btn-success">Accepted</a>
<a href="?status=declined" class="btn btn-danger">Declined</a>
</div>
</div>

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

<div class="card" style="padding:20px;overflow-x:auto;">

<?php if (!empty($children)): ?>

<table style="width:100%;border-collapse:collapse;">

<thead>
<tr style="background:#0a1f44;color:white;">
<th style="padding:10px;">Child Name</th>
<th>Parent Name</th>
<th>Category</th>
<th>Address</th>
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
$parentDoc = '';

foreach ($parents as $parent) {

if ($parent['id'] === $child['parent_id']) {

$parentName = $parent['full_name'];
$parentDoc = $parent['documents']['parent_id_card'] ?? '';
break;
}
}

if (empty($parentDoc) && !empty($child['documents']['parent_id'])) {
    $parentDoc = $child['documents']['parent_id'];
}

echo htmlspecialchars($parentName);
?>
</td>

<td><?php echo htmlspecialchars($child['category'] ?? $child['grade'] ?? ''); ?></td>
<td><?php echo htmlspecialchars($child['address'] ?? ''); ?></td>

<td>
<span style="padding:5px 12px;border-radius:5px;color:white;background:
<?php echo $child['status'] === 'pending' ? '#f39c12' : ($child['status'] === 'accepted' ? '#27ae60' : '#c0392b'); ?>;">
<?php echo ucfirst($child['status']); ?>
</span>
</td>

<td>

<?php if (!empty($child['documents']['birth_certificate'])): ?>
<button class="doc-btn" onclick="openDoc('<?php echo $child['documents']['birth_certificate']; ?>')">Birth</button>
<?php endif; ?>

<?php if (!empty($child['documents']['clinical_report'])): ?>
<button class="doc-btn" onclick="openDoc('<?php echo $child['documents']['clinical_report']; ?>')">Medical</button>
<?php endif; ?>

<?php if (!empty($parentDoc)): ?>
<button class="doc-btn" onclick="openDoc('<?php echo $parentDoc; ?>')">Parent ID</button>
<?php endif; ?>

</td>

<td>

<?php if ($child['status'] === 'pending'): ?>

<a href="?action=accept&id=<?php echo $child['id']; ?>" class="action-btn accept">Accept</a>
<button type="button" onclick="openDeclineModal('<?php echo $child['id']; ?>')" class="action-btn decline" style="border:none; cursor:pointer; font-family:inherit;">Decline</button>

<?php else: ?>

Completed

<?php endif; ?>

<a href="/print-profile.php?id=<?php echo $child['id']; ?>" target="_blank" class="action-btn" style="background:#0f172a; margin-top:5px; display:inline-block;">🖨️ Print</a>

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

<!-- WALK-IN REGISTRATION MODAL -->
<div id="walkInModal">
    <div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3>Walk-In Registration</h3>
            <button onclick="closeWalkInModal()">X</button>
        </div>
        <form id="walkInForm" method="POST" enctype="multipart/form-data">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Parent Full Name</label>
                    <input type="text" name="parent_full_name" required>
                </div>
                <div class="form-group">
                    <label>Parent Phone</label>
                    <input type="text" name="parent_phone" required>
                </div>
                <div class="form-group">
                    <label>Parent Email (Optional)</label>
                    <input type="email" name="parent_email">
                </div>
                <div class="form-group">
                    <label>Role to Child</label>
                    <select name="parent_role" required>
                        <option value="mother">Mother</option>
                        <option value="father">Father</option>
                        <option value="guardian">Guardian</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Parent Address</label>
                    <input type="text" name="parent_address" required>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Parent ID Card (PDF/Image, Optional)</label>
                    <input type="file" name="parent_id_card" accept=".pdf,image/*">
                </div>
            </div>

            <hr style="margin:20px 0; border: 1px solid #e2e8f0;">

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group" style="grid-column: span 2;">
                    <label>Child Full Name</label>
                    <input type="text" name="child_full_name" required>
                </div>
                <div class="form-group">
                    <label>Child DOB</label>
                    <input type="date" name="child_dob" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="child_gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Category (Age Group)</label>
                    <select name="child_category" required>
                        <option value="0-2">0 - 2 Years</option>
                        <option value="3-4">3 - 4 Years</option>
                        <option value="4-5">4 - 5 Years (Grade R)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Allergies</label>
                    <input type="text" name="child_allergies">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Child Address</label>
                    <input type="text" name="child_address" required>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <hr style="margin:10px 0; border: 1px solid #e2e8f0;">
                    <h4 style="margin-top:10px; font-family:'Outfit', sans-serif;">Emergency Contact (Secondary)</h4>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label>Emergency Contact Name</label>
                    <input type="text" name="child_emergency_contact_name" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="child_emergency_contact_phone" required>
                </div>
                <div class="form-group">
                    <label>Relation (e.g. Uncle)</label>
                    <input type="text" name="child_emergency_contact_relation" required>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <hr style="margin:10px 0; border: 1px solid #e2e8f0;">
                    <h4 style="margin-top:10px; font-family:'Outfit', sans-serif;">Documents</h4>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label>Child Birth Certificate (PDF/Image, Optional)</label>
                    <input type="file" name="child_birth_certificate" accept=".pdf,image/*">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Road to Health Clinical Report (PDF/Image, Optional)</label>
                    <input type="file" name="child_clinical_report" accept=".pdf,image/*">
                </div>
            </div>

            <div style="display:flex; gap:15px; margin-top:20px;">
                <button type="button" class="btn btn-danger" onclick="closeWalkInModal()" style="flex:1; padding:15px; font-size:1.1rem;">Cancel</button>
                <button type="submit" class="btn btn-success" style="flex:1; padding:15px; font-size:1.1rem; background-color: #28a745; color: white; border: none;">Register</button>
            </div>
        </form>
    </div>
</div>

<!-- DOCUMENT VIEWER MODAL -->
<div id="docModal">
    <div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h3>View Document</h3>
            <button onclick="closeDoc()">X</button>
        </div>
        <iframe id="docFrame" style="width:100%; height:60vh; border:1px solid #ddd; border-radius:5px;"></iframe>
    </div>
</div>

<!-- DECLINE MODAL -->
<div id="declineModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); justify-content:center; align-items:center; z-index:999;">
    <div style="background:#fff; border-radius:10px; padding:25px; width:90%; max-width:500px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h3>Decline Application</h3>
            <button onclick="closeDeclineModal()" style="background:#dc3545; color:#fff; border:none; padding:5px 10px; border-radius:5px; cursor:pointer;">X</button>
        </div>
        <form method="POST">
            <input type="hidden" name="action" value="decline">
            <input type="hidden" name="child_id" id="declineChildId">
            <div class="form-group">
                <label>Reason for Declining</label>
                <textarea name="decline_reason" required rows="4" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc; font-family:inherit;"></textarea>
            </div>
            <div style="display:flex; gap:15px; margin-top:20px;">
                <button type="button" class="btn btn-secondary" onclick="closeDeclineModal()" style="flex:1; padding:10px;">Cancel</button>
                <button type="submit" class="btn btn-danger" style="flex:1; padding:10px;">Confirm Decline</button>
            </div>
        </form>
    </div>
</div>

<script>

function openWalkInModal(){
document.getElementById("walkInModal").style.display = "flex";
}

function closeWalkInModal(){
document.getElementById("walkInForm").reset();
document.getElementById("walkInModal").style.display = "none";
}

function openDoc(url){
document.getElementById("docFrame").src = url;
document.getElementById("docModal").style.display = "flex";
}

function closeDoc(){
document.getElementById("docModal").style.display = "none";
document.getElementById("docFrame").src = "";
}

function openDeclineModal(childId){
    document.getElementById("declineChildId").value = childId;
    document.getElementById("declineModal").style.display = "flex";
}

function closeDeclineModal(){
    document.getElementById("declineModal").style.display = "none";
}

</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>


