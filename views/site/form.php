<h1><?= e($form['name']) ?></h1>
<p><?= e($form['description'] ?? '') ?></p>
<form method="POST" class="card" action="<?= e(url('forms/'.(string) $form['id'])) ?>">
    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
    <?php foreach ($fields as $field): ?>
        <label><?= e($field['label']) ?></label>
        <?php if ($field['type'] === 'textarea'): ?>
            <textarea name="field_<?= e((string) $field['id']) ?>"></textarea>
        <?php else: ?>
            <input type="<?= e($field['type']) ?>" name="field_<?= e((string) $field['id']) ?>">
        <?php endif; ?>
    <?php endforeach; ?>
    <button type="submit">Senden</button>
</form>
