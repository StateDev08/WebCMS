<h1><?= e($forum['title']) ?></h1>
<p><?= e($forum['description'] ?? '') ?></p>
<?php foreach ($threads as $thread): ?>
    <div class="card">
        <a href="<?= e(url('forums/threads/'.(string) $thread['id'])) ?>"><?= e($thread['title']) ?></a>
    </div>
<?php endforeach; ?>

<?php if (current_user()): ?>
    <form method="POST" action="<?= e(url('forums/'.(string) $forum['id'].'/threads')) ?>" class="card">
        <input type="hidden" name="_csrf" value="<?= e(\App\Core\Csrf::token()) ?>">
        <label>Titel</label>
        <input type="text" name="title" required>
        <label>Inhalt</label>
        <textarea name="content" required></textarea>
        <button type="submit">Thread erstellen</button>
    </form>
<?php else: ?>
    <div class="card">Bitte einloggen, um einen Thread zu erstellen.</div>
<?php endif; ?>
