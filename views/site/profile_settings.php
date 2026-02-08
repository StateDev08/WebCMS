<h1>Profil</h1>
<form method="POST" class="card">
    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
    <label>Theme</label>
    <select name="theme_slug">
        <option value="">Standard</option>
        <?php foreach ($themes as $theme): ?>
            <option value="<?= e($theme['slug']) ?>" <?= (current_user()['theme_slug'] ?? '') === $theme['slug'] ? 'selected' : '' ?>>
                <?= e($theme['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Speichern</button>
</form>
