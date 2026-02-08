<h1>Leaderboard</h1>
<?php foreach ($stats as $stat): ?>
    <div class="card">
        <a href="<?= e(url('game/stats/'.(string) $stat['id'])) ?>"><?= e($stat['player_name']) ?></a>
        <div class="muted">Score: <?= e((string) ($stat['score'] ?? '')) ?> | Rank: <?= e((string) ($stat['rank'] ?? '')) ?></div>
    </div>
<?php endforeach; ?>
