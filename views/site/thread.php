<h1><?= e($thread['title']) ?></h1>
<?php foreach ($posts as $post): ?>
    <div class="card">
        <div class="muted"><?= e($post['created_at'] ?? '') ?></div>
        <div><?= e($post['content']) ?></div>
    </div>
<?php endforeach; ?>

<?php if (current_user()): ?>
    <form method="POST" action="<?= e(url('forums/threads/'.(string) $thread['id'].'/posts')) ?>" class="card">
        <input type="hidden" name="_csrf" value="<?= e($csrf) ?>">
        <label>Antwort</label>
        <textarea name="content" required></textarea>
        <button type="submit">Senden</button>
    </form>
<?php else: ?>
    <div class="card">Bitte einloggen, um zu antworten.</div>
<?php endif; ?>
