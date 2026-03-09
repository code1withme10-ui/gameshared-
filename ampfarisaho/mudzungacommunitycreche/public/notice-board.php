<?php
session_start();

require_once __DIR__ . '/../app/services/JsonStorage.php';
require_once __DIR__ . '/../app/middleware/auth.php';

// Load notices
$noticesStorage = new JsonStorage(__DIR__ . '/../storage/notices.json');
$notices = $noticesStorage->read();

// Check role safely
$isHeadmaster = isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'headmaster';

// Handle new notice submission (headmaster only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isHeadmaster) {
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($title && $message) {
        $notices[] = [
            'id' => uniqid('notice_'),
            'title' => $title,
            'message' => $message,
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

    <!-- Page Header -->
    <section style="margin-top:50px; text-align:center;">
        <h1>Notice Board</h1>
        <p>Stay updated with the latest announcements and important notices from the creche.</p>
    </section>

    <!-- Add Notice Form (Headmaster Only) -->
    <?php if ($isHeadmaster): ?>
        <section class="card" style="max-width:700px; margin:30px auto;">
            <h2>Add New Notice</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Post Notice</button>
            </form>
        </section>
    <?php endif; ?>

    <!-- Display Notices -->
    <section style="margin-top:40px;">
        <h2>All Notices</h2>

        <?php if (!empty($notices)): ?>
            <div class="notice-list" style="display:flex; flex-direction:column; gap:20px;">
                <?php foreach (array_reverse($notices) as $notice): ?>
                    <div class="card" style="padding:20px;">
                        <h3 style="color:#09223b;"><?php echo htmlspecialchars($notice['title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($notice['message'])); ?></p>
                        <small style="color:#555;">
                            Posted by <?php echo htmlspecialchars($notice['posted_by']); ?>
                            on <?php echo htmlspecialchars($notice['date']); ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align:center; margin-top:20px;">No notices available.</p>
        <?php endif; ?>
    </section>

</div>

<?php
require_once __DIR__ . '/../app/views/partials/footer.php';
?>

