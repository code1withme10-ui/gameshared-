<div class="content-card challenge" style="
    padding:20px;
    background:#e1f5fe;
    border-left:6px solid #03a9f4;
    border-radius:10px;
    margin:10px 0;
">
    <h3>🧩 Mini Challenge</h3>
    <p><?php echo nl2br(htmlspecialchars($content["content"])); ?></p>

    <?php if (!empty($content["effort"])): ?>
        <div style="margin-top:8px; opacity:0.8;">
            <strong>Effort:</strong> <?php echo htmlspecialchars($content["effort"]); ?>
        </div>
    <?php endif; ?>
</div>

