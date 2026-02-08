<h1>Rollen für <?= e($user['name']) ?></h1>
<form method="POST" class="card">
    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
    <?php foreach ($roles as $role): ?>
        <label>
            <input type="checkbox" name="roles[]" value="<?= e((string) $role['id']) ?>" <?= in_array((int) $role['id'], $assigned, true) ? 'checked' : '' ?>>
            <?= e($role['name']) ?> (<?= e($role['slug']) ?>)
        </label>
    <?php endforeach; ?>
    <button type="submit">Speichern</button>
</form>
