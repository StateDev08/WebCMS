<h1>Gilden</h1>
<?php foreach ($guilds as $guild): ?>
    <div class="card">
        <a href="<?= e(url('game/guilds/'.(string) $guild['id'])) ?>"><?= e($guild['name']) ?></a>
        <div class="muted"><?= e($guild['tag'] ?? '') ?></div>
    </div>
<?php endforeach; ?>
