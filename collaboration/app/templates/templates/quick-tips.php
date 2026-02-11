
<div class="content-card quick-tips" style="
    padding:20px;
    background:#e0f7fa;
    border-radius:14px;
    margin:10px 0;
    border-left:5px solid #00838f;
">
    <h3>⚡ Quick Tip</h3>

    <p><?php echo nl2br(htmlspecialchars($content["content"] ?? "Missing content…")); ?></p>

    <?php if (!empty($content["tip_level"])): ?>
        <small style="opacity:0.7;">Level: <?php echo htmlspecialchars($content["tip_level"]); ?></small>
    <?php endif; ?>
</div>
