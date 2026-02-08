<h1>Formulare</h1>
<?php foreach ($forms as $form): ?>
    <div class="card">
        <a href="<?= e(url('forms/'.(string) $form['id'])) ?>"><?= e($form['name']) ?></a>
        <p><?= e($form['description'] ?? '') ?></p>
    </div>
<?php endforeach; ?>
