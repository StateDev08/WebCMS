<h1>Suche</h1>
<form method="GET" class="card">
    <input type="text" name="q" value="<?= e($query) ?>" placeholder="Suchbegriff...">
    <button type="submit">Suchen</button>
</form>

<?php if ($query !== ''): ?>
    <h2>Seiten</h2>
    <?php foreach ($pages as $page): ?>
        <div class="card"><a href="/pages/<?= e($page['slug']) ?>"><?= e($page['title']) ?></a></div>
    <?php endforeach; ?>

    <h2>Beiträge</h2>
    <?php foreach ($posts as $post): ?>
        <div class="card"><a href="/posts/<?= e($post['slug']) ?>"><?= e($post['title']) ?></a></div>
    <?php endforeach; ?>
<?php endif; ?>
