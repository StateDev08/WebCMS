<div class="toolbar">
    <h1><?= e($module['title']) ?></h1>
    <a class="btn" href="/admin/<?= e($moduleKey) ?>/create">Neu</a>
</div>

<?php if ($moduleKey === 'media'): ?>
    <form method="GET" class="card">
        <label>Suche</label>
        <input type="text" name="q" value="<?= e($_GET['q'] ?? '') ?>">
        <label>Ordner</label>
        <select name="folder">
            <option value="">Alle</option>
            <?php foreach (($filters['folders'] ?? []) as $folder): ?>
                <option value="<?= e($folder) ?>" <?= ($folder === ($_GET['folder'] ?? '')) ? 'selected' : '' ?>><?= e($folder) ?></option>
            <?php endforeach; ?>
        </select>
        <label>Tag</label>
        <select name="tag">
            <option value="">Alle</option>
            <?php foreach (($filters['tags'] ?? []) as $tag): ?>
                <option value="<?= e($tag) ?>" <?= ($tag === ($_GET['tag'] ?? '')) ? 'selected' : '' ?>><?= e($tag) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtern</button>
    </form>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <?php foreach (array_keys($module['fields']) as $field): ?>
                <?php if (($module['fields'][$field]['type'] ?? '') === 'password'): ?>
                    <?php continue; ?>
                <?php endif; ?>
                <th><?= e($module['fields'][$field]['label']) ?></th>
            <?php endforeach; ?>
            <th>Aktionen</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= e((string) $row['id']) ?></td>
                <?php foreach (array_keys($module['fields']) as $field): ?>
                    <?php if (($module['fields'][$field]['type'] ?? '') === 'password'): ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <?php if ($moduleKey === 'media' && $field === 'path'): ?>
                        <td>
                            <?php if (! empty($row['mime_type']) && str_starts_with($row['mime_type'], 'image/')): ?>
                                <img src="/<?= e($row['path']) ?>" alt="" style="max-width:60px;max-height:60px;">
                            <?php endif; ?>
                            <div class="muted"><?= e((string) ($row[$field] ?? '')) ?></div>
                        </td>
                    <?php elseif ($moduleKey === 'users' && $field === 'theme_slug'): ?>
                        <td>
                            <?= e((string) ($row[$field] ?? '')) ?>
                            <div class="muted"><a href="/admin/users/<?= e((string) $row['id']) ?>/roles">Rollen</a></div>
                        </td>
                    <?php else: ?>
                        <td><?= e((string) ($row[$field] ?? '')) ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td>
                    <a href="/admin/<?= e($moduleKey) ?>/<?= e((string) $row['id']) ?>">Ansehen</a>
                    <a href="/admin/<?= e($moduleKey) ?>/<?= e((string) $row['id']) ?>/edit">Bearbeiten</a>
                    <form method="POST" action="/admin/<?= e($moduleKey) ?>/<?= e((string) $row['id']) ?>/delete" class="inline">
                        <input type="hidden" name="_csrf" value="<?= e(\App\Core\Csrf::token()) ?>">
                        <button type="submit">Löschen</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
