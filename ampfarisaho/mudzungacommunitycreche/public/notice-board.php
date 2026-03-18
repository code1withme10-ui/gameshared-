<?php
session_start();

require_once __DIR__ . '/../app/services/JsonStorage.php';
require_once __DIR__ . '/../app/middleware/auth.php';

// Load notices
$noticesStorage = new JsonStorage(__DIR__ . '/../storage/notices.json');
$notices = $noticesStorage->read() ?? [];

// Check roles
$isHeadmaster = isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'headmaster';
$isParent = isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'parent';

// Handle new notice submission (headmaster only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isHeadmaster) {

    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $visibility = $_POST['visibility'] ?? 'public'; // NEW FIELD

    if ($title && $message) {

        $notices[] = [
            'id' => uniqid('notice_'),
            'title' => $title,
            'message' => $message,
            'visibility' => $visibility, // SAVE VISIBILITY
            'posted_by' => $_SESSION['user']['full_name'] ?? 'Headmaster',
            'date' => date('Y-m-d H:i:s')
        ];

        $noticesStorage->write($notices);

        header('Location: notice-board.php');
        exit;
    }
}

require_once __DIR__ . '/../app/views/partials/header.php';
require_once __DIR__ . '/../app/views/partials/navbar.php';
?>

<div class="container">

<!-- PAGE HEADER -->
<section style="margin-top:50px; text-align:center;">
    <h1>Notice Board</h1>
    <p>Stay updated with the latest announcements and important notices from the creche.</p>
</section>


<!-- ADD NOTICE FORM (HEADMASTER ONLY) -->
<?php if ($isHeadmaster): ?>
<section class="card" style="max-width:700px; margin:30px auto;">
    <h2>Add New Notice</h2>

    <form method="POST">

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" required>
        </div>

        <div class="form-group">
            <label>Message</label>
            <textarea name="message" rows="4" required></textarea>
        </div>

        <!-- NEW VISIBILITY OPTION -->
        <div class="form-group">
            <label>Notice Visibility</label>
            <select name="visibility">
                <option value="public">Public (Visible to everyone)</option>
                <option value="private">Private (Parents only)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Post Notice</button>

    </form>
</section>
<?php endif; ?>


<!-- DISPLAY NOTICES -->
<section style="margin-top:40px;">
<h2>All Notices</h2>

<?php if (!empty($notices)): ?>

<div class="notice-list" style="display:flex; flex-direction:column; gap:20px;">

<?php foreach (array_reverse($notices) as $notice): ?>

<?php

$visibility = $notice['visibility'] ?? 'public';

/*
RULES:
Public → everyone sees
Private → only logged in parents or headmaster
*/

if ($visibility === 'private' && !$isParent && !$isHeadmaster) {
    continue;
}

?>

<div class="card" style="padding:20px;">

<h3 style="color:#09223b;">
<?php echo htmlspecialchars($notice['title']); ?>

<?php if ($visibility === 'private'): ?>
<span style="
background:#09223b;
color:#fff;
padding:3px 8px;
font-size:12px;
border-radius:4px;
margin-left:8px;">
PRIVATE
</span>
<?php else: ?>
<span style="
background:#ffd700;
color:#09223b;
padding:3px 8px;
font-size:12px;
border-radius:4px;
margin-left:8px;">
PUBLIC
</span>
<?php endif; ?>

</h3>

<p><?php echo nl2br(htmlspecialchars($notice['message'])); ?></p>

<small style="color:#555;">
Posted by <?php echo htmlspecialchars($notice['posted_by']); ?>
on <?php echo htmlspecialchars($notice['date']); ?>
</small>

</div>

<?php endforeach; ?>

</div>

<?php else: ?>

<p style="text-align:center; margin-top:20px;">
No notices available.
</p>

<?php endif; ?>

</section>

</div>

<?php
require_once __DIR__ . '/../app/views/partials/footer.php';
?>

