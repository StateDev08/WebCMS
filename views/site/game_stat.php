<h1><?= e($stat['player_name']) ?></h1>
<div class="card">
    <div>Score: <?= e((string) ($stat['score'] ?? '')) ?></div>
    <div>Rank: <?= e((string) ($stat['rank'] ?? '')) ?></div>
    <div>Stats: <?= e($stat['stats'] ?? '') ?></div>
</div>
