<h1>Plugins</h1>
<?php foreach ($plugins as $plugin): ?>
    <div class="card">
        <div><?= e($plugin['name']) ?> (<?= e($plugin['slug']) ?>)</div>
        <div class="muted"><?= e($plugin['description'] ?? '') ?></div>
    </div>
<?php endforeach; ?>
