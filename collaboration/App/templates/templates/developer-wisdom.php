<div class="content-card wisdom" style="
    padding:20px;
    background:#fdf6e3;
    border:1px solid #deb887;
    border-radius:8px;
    margin:10px 0;
">
    <h3>👨‍🏫 Developer Wisdom</h3>
    <p><?php echo nl2br(htmlspecialchars($content["content"])); ?></p>

    <?php if (!empty($content["source"])): ?>
        <div style="margin-top:10px;font-size:0.9em;opacity:0.7;">
            <strong>Source:</strong> <?php echo htmlspecialchars($content["source"]); ?>
        </div>
    <?php endif; ?>
</div>

