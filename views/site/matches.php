<h1>Matches</h1>
<?php foreach ($matches as $match): ?>
    <div class="card">
        <a href="<?= e(url('game/matches/'.(string) $match['id'])) ?>">Match <?= e((string) $match['id']) ?></a>
        <div class="muted"><?= e($match['map'] ?? '') ?></div>
    </div>
<?php endforeach; ?>
