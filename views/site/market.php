<h1>Marktplatz</h1>
<?php foreach ($items as $item): ?>
    <div class="card">
        <a href="<?= e(url('game/market/'.(string) $item['id'])) ?>"><?= e($item['name']) ?></a>
        <div class="muted"><?= e((string) ($item['price'] ?? '')) ?> <?= e($item['currency'] ?? '') ?></div>
    </div>
<?php endforeach; ?>
