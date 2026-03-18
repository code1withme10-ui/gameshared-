<?php
require_once __DIR__ . '/../app/middleware/auth.php';
requireRole('headmaster');
require_once __DIR__ . '/../app/services/JsonStorage.php';

$childId = $_GET['id'] ?? null;
if (!$childId) {
    die("Child ID is required.");
}

$childrenStorage = new JsonStorage(__DIR__ . '/../storage/children.json');
$children = $childrenStorage->read() ?? [];

$parentsStorage = new JsonStorage(__DIR__ . '/../storage/parents.json');
$parents = $parentsStorage->read() ?? [];

$child = null;
foreach ($children as $c) {
    if ($c['id'] === $childId) {
        $child = $c;
        break;
    }
}

if (!$child) {
    die("Child not found.");
}

$parent = null;
foreach ($parents as $p) {
    if ($p['id'] === $child['parent_id']) {
        $parent = $p;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile - <?php echo htmlspecialchars($child['full_name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            line-height: 1.6;
            margin: 0;
            padding: 40px;
            background: #f8fafc;
        }
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-family: 'Outfit', sans-serif;
            color: #0f172a;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .header p {
            margin: 0;
            color: #64748b;
            font-size: 16px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            color: #f59e0b;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .field {
            margin-bottom: 10px;
        }
        .field-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .field-value {
            font-size: 16px;
            font-weight: 500;
            color: #0f172a;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge.accepted { background: #dcfce7; color: #166534; }
        .badge.pending { background: #fef3c7; color: #92400e; }
        .badge.declined { background: #fee2e2; color: #991b1b; }
        
        .print-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            padding: 12px;
            background: #0f172a;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            cursor: pointer;
            border: none;
        }
        
        @media print {
            body { background: #fff; padding: 0; }
            .profile-container { box-shadow: none; padding: 0; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨️ Print Profile</button>

<div class="profile-container">
    <div class="header">
        <h1>Mudzunga Community Creche</h1>
        <p>Student Admission Profile</p>
    </div>

    <div class="section">
        <div class="section-title">Child Information</div>
        <div class="grid">
            <div class="field">
                <div class="field-label">Full Name</div>
                <div class="field-value"><?php echo htmlspecialchars($child['full_name']); ?></div>
            </div>
            <div class="field">
                <div class="field-label">Date of Birth</div>
                <div class="field-value"><?php echo htmlspecialchars($child['dob']); ?></div>
            </div>
            <div class="field">
                <div class="field-label">Gender</div>
                <div class="field-value"><?php echo htmlspecialchars(ucfirst($child['gender'] ?? 'N/A')); ?></div>
            </div>
            <div class="field">
                <div class="field-label">Category / Grade</div>
                <div class="field-value"><?php echo htmlspecialchars($child['category'] ?? $child['grade'] ?? 'N/A'); ?></div>
            </div>
            <div class="field" style="grid-column: span 2;">
                <div class="field-label">Residential Address</div>
                <div class="field-value"><?php echo htmlspecialchars($child['address']); ?></div>
            </div>
            <div class="field" style="grid-column: span 2;">
                <div class="field-label">Status</div>
                <div class="field-value">
                    <span class="badge <?php echo $child['status']; ?>"><?php echo htmlspecialchars($child['status']); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Medical Details</div>
        <div class="grid">
            <div class="field" style="grid-column: span 2;">
                <div class="field-label">Known Allergies / Medical Conditions</div>
                <div class="field-value">
                    <?php echo !empty($child['allergies']) ? htmlspecialchars($child['allergies']) : '<em>None reported</em>'; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Primary Parent / Guardian</div>
        <div class="grid">
            <div class="field">
                <div class="field-label">Full Name</div>
                <div class="field-value"><?php echo $parent ? htmlspecialchars($parent['full_name']) : 'N/A'; ?></div>
            </div>
            <div class="field">
                <div class="field-label">Relationship</div>
                <div class="field-value"><?php echo $parent ? htmlspecialchars(ucfirst($parent['role_to_child'] ?? 'Parent')) : 'N/A'; ?></div>
            </div>
            <div class="field">
                <div class="field-label">Phone Number</div>
                <div class="field-value"><?php echo $parent ? htmlspecialchars($parent['phone']) : 'N/A'; ?></div>
            </div>
            <div class="field">
                <div class="field-label">Email Address</div>
                <div class="field-value"><?php echo (!empty($parent['email'])) ? htmlspecialchars($parent['email']) : 'N/A'; ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Secondary Emergency Contact</div>
        <div class="grid">
            <?php if (!empty($child['emergency_contact']) && !empty($child['emergency_contact']['name'])): ?>
                <div class="field">
                    <div class="field-label">Contact Name</div>
                    <div class="field-value"><?php echo htmlspecialchars($child['emergency_contact']['name']); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Relationship</div>
                    <div class="field-value"><?php echo htmlspecialchars($child['emergency_contact']['relation']); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Phone Number</div>
                    <div class="field-value"><?php echo htmlspecialchars($child['emergency_contact']['phone']); ?></div>
                </div>
            <?php else: ?>
                <div class="field" style="grid-column: span 2;">
                    <div class="field-value" style="color:#94a3b8;"><em>No secondary emergency contact provided.</em></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div style="margin-top: 50px; font-size: 12px; color: #94a3b8; text-align: center;">
        Generated by Mudzunga Community Creche System on <?php echo date('Y-m-d H:i'); ?>
    </div>

</div>

</body>
</html>
