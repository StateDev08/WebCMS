<h1>Events</h1>
<?php foreach ($events as $event): ?>
    <div class="card">
        <a href="<?= e(url('game/events/'.(string) $event['id'])) ?>"><?= e($event['name']) ?></a>
        <div class="muted"><?= e($event['starts_at'] ?? '') ?></div>
    </div>
<?php endforeach; ?>
