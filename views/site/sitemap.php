<?php header('Content-Type: application/xml; charset=UTF-8'); ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc><?= e($baseUrl) ?>/</loc></url>
    <?php foreach ($pages as $page): ?>
        <url><loc><?= e($baseUrl) ?>/pages/<?= e($page['slug']) ?></loc></url>
    <?php endforeach; ?>
    <?php foreach ($posts as $post): ?>
        <url><loc><?= e($baseUrl) ?>/posts/<?= e($post['slug']) ?></loc></url>
    <?php endforeach; ?>
</urlset>
