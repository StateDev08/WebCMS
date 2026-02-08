<h1><?= e($event['name']) ?></h1>
<div class="card">
    <div><?= e($event['description'] ?? '') ?></div>
    <div>Start: <?= e($event['starts_at'] ?? '') ?></div>
    <div>Ende: <?= e($event['ends_at'] ?? '') ?></div>
    <div>Ort: <?= e($event['location'] ?? '') ?></div>
</div>
