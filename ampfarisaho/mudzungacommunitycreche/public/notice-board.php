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

<div class="container" style="margin-top:50px; margin-bottom:60px;">
    <h2>Notice Board</h2>

    <?php if ($isHeadmaster): ?>
        <h4>Add New Notice</h4>
        <form method="POST">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:10px;">
                Post Notice
            </button>
        </form>
        <hr>
    <?php endif; ?>

    <h4>All Notices</h4>

    <?php if (!empty($notices)): ?>
        <ul class="list-group">
            <?php foreach (array_reverse($notices) as $notice): ?>
                <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($notice['title']); ?></strong>
                    <p><?php echo nl2br(htmlspecialchars($notice['message'])); ?></p>
                    <small>
                        Posted by <?php echo htmlspecialchars($notice['posted_by']); ?>
                        on <?php echo htmlspecialchars($notice['date']); ?>
                    </small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No notices available.</p>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../app/views/partials/footer.php';
?>

