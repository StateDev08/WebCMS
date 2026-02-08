<h1>Gruppen</h1>
<?php foreach ($groups as $group): ?>
    <div class="card">
        <a href="<?= e(url('groups/'.(string) $group['id'])) ?>"><?= e($group['name']) ?></a>
        <div class="muted"><?= e($group['description'] ?? '') ?></div>
    </div>
<?php endforeach; ?>
