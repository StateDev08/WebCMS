<h1><?= e($guild['name']) ?></h1>
<div class="card">
    <div>Tag: <?= e($guild['tag'] ?? '') ?></div>
    <div><?= e($guild['description'] ?? '') ?></div>
    <div>Owner ID: <?= e((string) ($guild['owner_id'] ?? '')) ?></div>
</div>
