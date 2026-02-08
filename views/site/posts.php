<h1>Beiträge</h1>
<?php foreach ($posts as $post): ?>
    <article class="card">
        <h2><a href="<?= e(url('posts/'.($post['slug'] ?? ''))) ?>"><?= e($post['title']) ?></a></h2>
        <p><?= e($post['excerpt'] ?? '') ?></p>
    </article>
<?php endforeach; ?>
