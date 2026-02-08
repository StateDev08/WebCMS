<h1>Profile</h1>
<?php foreach ($profiles as $profile): ?>
    <div class="card">
        <a href="/profiles/<?= e((string) $profile['id']) ?>">Profil #<?= e((string) $profile['id']) ?></a>
        <div class="muted"><?= e($profile['bio'] ?? '') ?></div>
    </div>
<?php endforeach; ?>
