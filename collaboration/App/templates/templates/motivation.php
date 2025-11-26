<div class="content-card motivation" style="
    padding:25px; 
    background:linear-gradient(135deg, #6a11cb, #2575fc); 
    color:white; 
    border-radius:14px; 
    margin:10px 0;
">
    <h3>🌟 Motivation</h3>
    <p style="font-size:1.2em;"><?php echo nl2br(htmlspecialchars($content["content"])); ?></p>

    <?php if (!empty($content["tone"])): ?>
        <div style="margin-top:10px; opacity:0.9;">
            <strong>Tone:</strong> <?php echo htmlspecialchars($content["tone"]); ?>
        </div>
    <?php endif; ?>
</div>

