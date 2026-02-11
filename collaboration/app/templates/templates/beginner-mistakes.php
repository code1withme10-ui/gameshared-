<div class="content-card beginner" style="
    padding:20px; 
    background:#ffe7db; 
    border-left:6px solid #ff6b3d; 
    border-radius:10px; 
    margin:10px 0;
">
    <h3>🗯️ Beginner Mistake</h3>
    <p><?php echo nl2br(htmlspecialchars($content["content"])); ?></p>

    <?php if (!empty($content["lesson"])): ?>
        <div style="margin-top:10px; font-style:italic;">
            <strong>Lesson:</strong> <?php echo htmlspecialchars($content["lesson"]); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($content["severity"])): ?>
        <div style="margin-top:5px; opacity:0.8;">
            <strong>Severity:</strong> <?php echo htmlspecialchars($content["severity"]); ?>
        </div>
    <?php endif; ?>
</div>
