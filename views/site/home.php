<?php if ($page): ?>
    <article class="card">
        <h1><?= e($page['title']) ?></h1>
        <div class="content"><?= $page['content'] ?></div>
    </article>
<?php else: ?>
    <div class="card">
        <h1>Willkommen</h1>
        <p>Lege im Admin-Bereich eine Seite an und markiere sie als Startseite.</p>
    </div>
<?php endif; ?>
