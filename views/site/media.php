<h1>Medien</h1>
<div class="grid">
    <?php foreach ($media as $item): ?>
        <div class="card">
            <?php if (! empty($item['mime_type']) && str_starts_with($item['mime_type'], 'image/')): ?>
                <img src="/<?= e($item['path']) ?>" alt="" style="max-width:100%;height:auto;">
            <?php endif; ?>
            <div><?= e($item['original_name'] ?? $item['path']) ?></div>
            <div class="muted"><?= e($item['mime_type'] ?? '') ?></div>
        </div>
    <?php endforeach; ?>
</div>
