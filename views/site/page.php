<article class="card">
    <h1><?= e($page['title']) ?></h1>
    <div class="content"><?= $page['content'] ?></div>
</article>

<h2>Kommentare</h2>
<?php foreach ($comments as $comment): ?>
    <div class="card">
        <div class="muted"><?= e($comment['created_at'] ?? '') ?></div>
        <div><?= e($comment['content']) ?></div>
    </div>
<?php endforeach; ?>

<form method="POST" action="<?= e(url('comments')) ?>" class="card">
    <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
    <input type="hidden" name="type" value="page">
    <input type="hidden" name="id" value="<?= e((string) $page['id']) ?>">
    <label>Kommentar</label>
    <textarea name="content"></textarea>
    <button type="submit">Senden</button>
</form>
