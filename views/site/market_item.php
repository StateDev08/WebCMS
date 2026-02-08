<h1><?= e($item['name']) ?></h1>
<div class="card">
    <div><?= e($item['description'] ?? '') ?></div>
    <div>Preis: <?= e((string) ($item['price'] ?? '')) ?> <?= e($item['currency'] ?? '') ?></div>
    <div>Verfügbar: <?= ! empty($item['is_available']) ? 'Ja' : 'Nein' ?></div>
</div>
