<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// 2. Load Data
$jsonFile = 'data/applications.json';
$inquiryFile = 'data/inquiries.json';
$applications = []; 
$inquiries = [];

if (file_exists($jsonFile)) {
    $fileData = file_get_contents($jsonFile);
    $decodedData = json_decode($fileData, true);
    if (is_array($decodedData)) $applications = $decodedData;
}

if (file_exists($inquiryFile)) {
    $inquiryData = json_decode(file_get_contents($inquiryFile), true);
    if (is_array($inquiryData)) $inquiries = $inquiryData;
}

// 3. Handle Status Updates (Applications)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    foreach ($applications as &$app) {
        if (isset($app['application_id']) && $app['application_id'] === $id) {
            if (strtolower($app['status'] ?? 'pending') === 'pending') {
                $app['status'] = ($action === 'approve') ? 'Approved' : 'Rejected';
            }
            break;
        }
    }
    file_put_contents($jsonFile, json_encode($applications, JSON_PRETTY_PRINT));
    header("Location: headmaster_portal.php?msg=updated");
    exit();
}

// 4. Handle Deleting Guest Inquiries
if (isset($_GET['delete_inquiry'])) {
    $indexToDelete = $_GET['delete_inquiry'];
    if (isset($inquiries[$indexToDelete])) {
        unset($inquiries[$indexToDelete]);
        file_put_contents($inquiryFile, json_encode(array_values($inquiries), JSON_PRETTY_PRINT));
        header("Location: headmaster_portal.php?msg=inquiry_deleted");
        exit();
    }
}

// 5. Calculate Stats
$total = count($applications);
$pending = count(array_filter($applications, function($a) {
    return isset($a['status']) && strtolower($a['status']) === 'pending';
}));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Headmaster Administration | Govern Psy</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --navy: #003366; --red: #C41E3A; --gold: #FFD700; }
        .admin-page { background: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .admin-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); min-width: 150px; text-align: center; }
        .stat-card strong { font-size: 1.5rem; color: var(--navy); display: block; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
        .status-badge.pending { background: #fff3cd; color: #856404; }
        .status-badge.approved { background: #d4edda; color: #155724; }
        .status-badge.rejected { background: #f8d7da; color: #721c24; }
        .doc-link { color: var(--navy); margin: 0 5px; transition: 0.2s; }
        .doc-link:hover { color: var(--red); }
        .msg-btn { color: var(--navy); margin-left: 10px; transition: 0.3s; }
        .msg-btn:hover { color: var(--gold); }
        .inquiry-item { background: #fff; border-left: 5px solid var(--red); padding: 15px; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); position: relative; }
        .delete-inquiry { position: absolute; top: 10px; right: 10px; color: #ccc; transition: 0.3s; }
        .delete-inquiry:hover { color: var(--red); }
    </style>
</head>
<body class="admin-page">

    <?php include 'includes/navbar.php'; ?>

    <header class="page-banner" style="background: var(--navy); color: white; padding: 30px 0; text-align: center; border-bottom: 5px solid var(--red);">
        <h1 style="margin:0; text-transform: uppercase; font-weight: 900;">Headmaster Dashboard</h1>
        <p style="letter-spacing: 2px;">Official Enrollment Management System 2026</p>
    </header>

    <main class="container" style="padding-top: 40px; max-width: 1300px; margin: auto;">

        <section style="margin-bottom: 40px;">
            <h2 style="color: var(--navy);"><i class="fas fa-envelope-open-text"></i> Guest Inquiries</h2>
            <?php if (empty($inquiries)): ?>
                <p style="color: #999; background: #fff; padding: 20px; border-radius: 10px; text-align: center;">No guest messages yet.</p>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; margin-top: 15px;">
                    <?php foreach ($inquiries as $index => $iq): ?>
                        <div class="inquiry-item">
                            <a href="headmaster_portal.php?delete_inquiry=<?php echo $index; ?>" class="delete-inquiry" onclick="return confirm('Delete this inquiry?')" title="Delete Inquiry">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                            <small style="color: #aaa;"><?php echo htmlspecialchars($iq['date'] ?? 'N/A'); ?></small>
                            <h4 style="margin: 5px 0; color: var(--navy);"><?php echo htmlspecialchars($iq['name'] ?? 'Unknown'); ?></h4>
                            <p style="margin: 5px 0; font-size: 0.85rem; color: var(--red);"><strong><i class="fas fa-phone"></i> <?php echo htmlspecialchars($iq['phone'] ?? 'N/A'); ?></strong></p>
                            <p style="margin-top: 10px; font-size: 0.9rem; line-height: 1.4; color: #444;">"<?php echo htmlspecialchars($iq['message'] ?? ''); ?>"</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <hr style="border: 0; border-top: 1px solid #ddd; margin-bottom: 40px;">

        <div style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; border-left: 5px solid var(--gold); box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="margin-top:0; color:var(--navy);"><i class="fas fa-bullhorn"></i> Send Announcement</h3>
            <form action="actions/send_announcement.php" method="POST" style="display: flex; gap: 10px;">
                <input type="text" name="title" placeholder="Title" required style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <input type="text" name="message" placeholder="Message to parents..." required style="flex: 2; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <button type="submit" style="background: var(--navy); color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">Send</button>
            </form>
        </div>

        <div class="admin-header-flex">
            <div style="display: flex; gap: 20px;">
                <div class="stat-card">Total Applications <strong><?php echo $total; ?></strong></div>
                <div class="stat-card" style="border-bottom: 4px solid var(--gold);">Pending Review <strong><?php echo $pending; ?></strong></div>
            </div>

            <div style="display: flex; gap: 15px; align-items: center;">
                <select id="gradeFilter" onchange="filterTable()" style="padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                    <option value="">All Grades</option>
                    <option value="Toddlers">Toddlers</option>
                    <option value="Playgroup">Playgroup</option>
                    <option value="Pre-School">Pre-School</option>
                    <option value="Foundation">Foundation</option>
                </select>
                <div style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 12px; color: #aaa;"></i>
                    <input type="text" id="adminSearch" onkeyup="filterTable()" placeholder="Search student name..." style="padding: 10px 10px 10px 35px; border-radius: 5px; border: 1px solid #ddd; width: 250px;">
                </div>
                <a href="admin_add_child.php" class="btn btn-primary" style="background: #28a745; border: none; padding: 10px 20px; border-radius: 5px; color: white; text-decoration: none; font-weight: bold;">
                    <i class="fas fa-plus-circle"></i> New Walk-in
                </a>
            </div>
        </div>

        <div class="table-container" style="background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow-x: auto;">
            <table id="appsTable" style="width: 100%; border-collapse: collapse; min-width: 1000px;">
                <thead>
                    <tr style="background: #f8f9fa; text-align: left; border-bottom: 2px solid #eee;">
                        <th style="padding: 20px;">Date</th>
                        <th style="padding: 20px;">Student</th>
                        <th style="padding: 20px;">Grade</th>
                        <th style="padding: 20px;">Medical Info</th>
                        <th style="padding: 20px;">Parent Contact</th>
                        <th style="padding: 20px; text-align: center;">Docs</th>
                        <th style="padding: 20px;">Status</th>
                        <th style="padding: 20px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($applications)): ?>
                        <tr><td colspan="8" style="text-align:center; padding: 50px; color: #999;">No applications registered yet.</td></tr>
                    <?php else: ?>
                        <?php 
                        $sorted_apps = array_reverse($applications);
                        foreach($sorted_apps as $app): 
                            $status = isset($app['status']) ? strtolower($app['status']) : 'pending';
                            
                            // SAFE DATA HANDLING
                            $childName = $app['child_name'] ?? 'Incomplete Application';
                            $grade = $app['grade'] ?? 'N/A';
                            $gender = $app['gender'] ?? 'N/A';
                            $appId = $app['application_id'] ?? null;
                        ?>
                        <tr style="border-bottom: 1px solid #f1f1f1;">
                            <td style="padding: 15px 20px; font-size: 0.85rem; color: #666;">
                                <?php echo isset($app['submission_date']) ? date("d M Y", strtotime($app['submission_date'])) : '---'; ?>
                            </td>
                            <td class="child-column" style="padding: 15px 20px;">
                                <strong><?php echo htmlspecialchars((string)$childName); ?></strong><br>
                                <small style="color: #888;"><?php echo htmlspecialchars((string)$gender); ?></small>
                            </td>
                            <td class="grade-column" style="padding: 15px 20px;"><?php echo htmlspecialchars((string)$grade); ?></td>
                            <td style="padding: 15px 20px; font-size: 0.85rem; max-width: 150px;">
                                <?php 
                                    $med = $app['medical_info'] ?? 'None';
                                    echo (strlen($med) > 30) ? htmlspecialchars(substr($med, 0, 27)) . "..." : htmlspecialchars($med);
                                ?>
                            </td>
                            <td style="padding: 15px 20px; font-size: 0.85rem;">
                                <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($app['email'] ?? 'No Email'); ?><br>
                                <i class="fas fa-phone"></i> <?php echo htmlspecialchars($app['parent_phone'] ?? 'No Phone'); ?>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                <?php if(!empty($app['documents']['doc_birth'])): ?>
                                    <a href="<?php echo $app['documents']['doc_birth']; ?>" target="_blank" class="doc-link" title="Birth Certificate"><i class="fas fa-baby fa-lg"></i></a>
                                <?php endif; ?>
                                <?php if(!empty($app['documents']['doc_parent_id'])): ?>
                                    <a href="<?php echo $app['documents']['doc_parent_id']; ?>" target="_blank" class="doc-link" title="Parent ID"><i class="fas fa-id-card fa-lg"></i></a>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 15px 20px;">
                                <span class="status-badge <?php echo $status; ?>"><?php echo strtoupper($status); ?></span>
                            </td>
                            <td style="padding: 15px 20px; text-align: right; display: flex; justify-content: flex-end; align-items: center;">
                                <?php if ($status === 'pending' && $appId): ?>
                                    <a href="headmaster_portal.php?action=approve&id=<?php echo $appId; ?>" style="color: #28a745; margin-right: 15px;" onclick="return confirm('Approve this student?')"><i class="fas fa-check"></i></a>
                                    <a href="headmaster_portal.php?action=reject&id=<?php echo $appId; ?>" style="color: var(--red);" onclick="return confirm('Reject this student?')"><i class="fas fa-times"></i></a>
                                <?php else: ?>
                                    <span style="color: #bbb; font-size: 0.8rem; margin-right: 15px;"><i class="fas fa-lock"></i> Finalized</span>
                                <?php endif; ?>

                                <?php if($appId): ?>
                                    <a href="actions/send_direct_message.php?id=<?php echo $appId; ?>" class="msg-btn">
                                        <i class="fas fa-comment-dots fa-lg"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
    function filterTable() {
        let nameInput = document.getElementById("adminSearch").value.toUpperCase();
        let gradeInput = document.getElementById("gradeFilter").value.toUpperCase();
        let tr = document.getElementById("appsTable").getElementsByTagName("tr");
        for (let i = 1; i < tr.length; i++) {
            let nameTd = tr[i].getElementsByClassName("child-column")[0];
            let gradeTd = tr[i].getElementsByClassName("grade-column")[0];
            if (nameTd && gradeTd) {
                let nameMatch = nameTd.innerText.toUpperCase().indexOf(nameInput) > -1;
                let gradeMatch = gradeInput === "" || gradeTd.innerText.toUpperCase().indexOf(gradeInput) > -1;
                tr[i].style.display = (nameMatch && gradeMatch) ? "" : "none";
            }
        }
    }
    </script>
</body>
</html>