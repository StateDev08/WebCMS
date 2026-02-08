<h1>Admin Dashboard</h1>
<div class="grid">
    <?php foreach ($modules as $key => $module): ?>
        <div class="card">
            <div class="muted"><?= e($module['title']) ?></div>
            <div class="big"><?= (int) ($counts[$key] ?? 0) ?></div>
            <a href="/admin/<?= e($key) ?>">Verwalten</a>
        </div>
    <?php endforeach; ?>
</div>
