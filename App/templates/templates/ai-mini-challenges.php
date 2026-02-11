<div class="content-card ai-mini-challenge" style="
    padding:20px;
    background:linear-gradient(135deg,#fff0f6,#ffd6e7);
    border-radius:14px;
    border-left:6px solid #d81b60;
    margin:10px 0;
">
    <h3>🧠🤖 AI Mini Challenge</h3>
    <p><?= nl2br(htmlspecialchars($content["content"] ?? "")); ?></p>

    <?php if (!empty($content["hint"])): ?>
        <details style="margin-top:8px;">
            <summary>Hint</summary>
            <p><?= htmlspecialchars($content["hint"]); ?></p>
        </details>
    <?php endif; ?>
</div>

