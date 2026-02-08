<h1><?= e($module['title']) ?></h1>
<form method="POST" class="card" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
    <?php if ($moduleKey === 'media'): ?>
        <label>Datei</label>
        <div class="dropzone" id="dropzone">Datei hier ablegen oder klicken</div>
        <input type="file" name="file" id="fileInput">
        <label>Tags (CSV)</label>
        <input type="text" name="tags" id="tagsInput" value="<?= e((string) ($record['tags'] ?? '')) ?>">
    <?php endif; ?>
<?php foreach ($module['fields'] as $field => $meta): ?>
    <label><?= e($meta['label']) ?></label>
    <?php if ($meta['type'] === 'roles'): ?>
        <div class="card">
            <?php foreach ($roles as $role): ?>
                <label>
                    <input type="checkbox" name="roles[]" value="<?= e((string) $role['id']) ?>" <?= in_array((int) $role['id'], $userRoles ?? [], true) ? 'checked' : '' ?>>
                    <?= e($role['name']) ?> (<?= e($role['slug']) ?>)
                </label>
            <?php endforeach; ?>
        </div>
        <?php continue; ?>
    <?php endif; ?>

    <?php if ($field === 'theme_slug' && $moduleKey === 'users'): ?>
        <select name="theme_slug">
            <option value="">Standard</option>
            <?php foreach ($themes as $theme): ?>
                <option value="<?= e($theme['slug']) ?>" <?= ($record['theme_slug'] ?? '') === $theme['slug'] ? 'selected' : '' ?>>
                    <?= e($theme['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php continue; ?>
    <?php endif; ?>

    <?php if ($meta['type'] === 'textarea'): ?>
        <textarea name="<?= e($field) ?>"><?= e((string) ($record[$field] ?? '')) ?></textarea>
    <?php elseif ($meta['type'] === 'select'): ?>
        <select name="<?= e($field) ?>">
            <?php foreach ($meta['options'] as $option): ?>
                <option value="<?= e($option) ?>" <?= ($record[$field] ?? '') === $option ? 'selected' : '' ?>><?= e($option) ?></option>
            <?php endforeach; ?>
        </select>
    <?php elseif ($meta['type'] === 'checkbox'): ?>
        <input type="checkbox" name="<?= e($field) ?>" value="1" <?= ! empty($record[$field]) ? 'checked' : '' ?>>
    <?php elseif ($meta['type'] === 'password'): ?>
        <input type="password" name="<?= e($field) ?>" value="">
    <?php else: ?>
        <input type="<?= e($meta['type']) ?>" name="<?= e($field) ?>" value="<?= e((string) ($record[$field] ?? '')) ?>">
    <?php endif; ?>
<?php endforeach; ?>
    <button type="submit">Speichern</button>
</form>
<?php if ($moduleKey === 'media'): ?>
<script>
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('fileInput');
if (dropzone && fileInput) {
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.style.borderColor = '#2563eb';
    });
    dropzone.addEventListener('dragleave', () => {
        dropzone.style.borderColor = '';
    });
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        fileInput.files = e.dataTransfer.files;
        dropzone.style.borderColor = '';
    });
}
</script>
<?php endif; ?>
