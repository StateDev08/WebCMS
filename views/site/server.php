<h1><?= e($server['name']) ?></h1>
<div class="card">
    <div>Typ: <?= e($server['type']) ?></div>
    <div>Status: <?= e($server['status'] ?? '') ?></div>
    <div>Host: <?= e($server['host']) ?>:<?= e((string) $server['port']) ?></div>
</div>
