<div class="content-card ai-tips" style="
    padding:20px;
    background:linear-gradient(135deg,#e6f7ff,#d0edff);
    border-radius:14px;
    border-left:6px solid #0288d1;
    margin:10px 0;
">
    <h3>💡🤖 AICoding Tip</h3>
    <p><?= nl2br(htmlspecialchars($content["content"] ?? "")); ?></p>

    <?php if (!empty($content["lesson"])): ?>
        <small style="opacity:0.7; display:block; margin-top:6px;">
            Lesson: <?= htmlspecialchars($content["lesson"]); ?>
        </small>
    <?php endif; ?>
</div>
