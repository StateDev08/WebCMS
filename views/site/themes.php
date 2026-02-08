<h1>Themes</h1>
<?php foreach ($themes as $theme): ?>
    <div class="card">
        <div><?= e($theme['name']) ?> (<?= e($theme['slug']) ?>)</div>
        <div class="muted"><?= e($theme['description'] ?? '') ?></div>
        <a href="<?= e(url('themes/activate/'.$theme['slug'])) ?>">Aktivieren</a>
    </div>
<?php endforeach; ?>
