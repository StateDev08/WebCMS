<h1>Serverstatus</h1>
<?php foreach ($servers as $server): ?>
    <div class="card">
        <a href="<?= e(url('serverstatus/'.(string) $server['id'])) ?>"><?= e($server['name']) ?></a>
        <div class="muted"><?= e($server['type']) ?> - <?= e($server['status'] ?? '') ?></div>
    </div>
<?php endforeach; ?>
