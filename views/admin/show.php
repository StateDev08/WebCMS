<h1><?= e($module['title']) ?> #<?= e((string) $record['id']) ?></h1>
<div class="card">
    <?php foreach ($module['fields'] as $field => $meta): ?>
        <div class="row">
            <span class="muted"><?= e($meta['label']) ?></span>
            <span><?= e((string) ($record[$field] ?? '')) ?></span>
        </div>
    <?php endforeach; ?>
</div>
<div class="toolbar">
    <a href="/admin/<?= e($moduleKey) ?>/<?= e((string) $record['id']) ?>/edit">Bearbeiten</a>
    <a href="/admin/<?= e($moduleKey) ?>">Zurück</a>
</div>
