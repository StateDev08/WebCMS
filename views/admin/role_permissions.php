<h1>Permissions für <?= e($role['name']) ?></h1>
<form method="POST" class="card">
    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
    <?php foreach ($permissions as $permission): ?>
        <label>
            <input type="checkbox" name="permissions[]" value="<?= e((string) $permission['id']) ?>" <?= in_array((int) $permission['id'], $assigned, true) ? 'checked' : '' ?>>
            <?= e($permission['name']) ?> (<?= e($permission['slug']) ?>)
        </label>
    <?php endforeach; ?>
    <button type="submit">Speichern</button>
</form>
