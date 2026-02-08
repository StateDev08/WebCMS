<h1>Foren</h1>
<?php foreach ($forums as $forum): ?>
    <div class="card">
        <h2><a href="<?= e(url('forums/'.(string) $forum['id'])) ?>"><?= e($forum['title']) ?></a></h2>
        <p><?= e($forum['description'] ?? '') ?></p>
    </div>
<?php endforeach; ?>
