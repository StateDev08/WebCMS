<article class="card">
    <h1><?= e($post['title']) ?></h1>
    <div class="content"><?= $post['content'] ?></div>
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
    <input type="hidden" name="type" value="post">
    <input type="hidden" name="id" value="<?= e((string) $post['id']) ?>">
    <label>Kommentar</label>
    <textarea name="content"></textarea>
    <button type="submit">Senden</button>
</form>
