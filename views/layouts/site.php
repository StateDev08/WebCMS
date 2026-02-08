<!DOCTYPE html>
<html lang="<?= e($app['locale']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($app['name']) ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<?php $userTheme = current_user()['theme_slug'] ?? null; ?>
<?php $activeTheme = $userTheme ?: ($_SESSION['theme'] ?? $app['theme_default']); ?>
<body data-theme="<?= e($activeTheme) ?>">
    <header class="navbar">
        <div class="container">
            <a class="logo" href="<?= e(url()) ?>"><?= e($app['name']) ?></a>
            <nav class="nav">
                <a href="<?= e(url('posts')) ?>">Beiträge</a>
                <a href="<?= e(url('media')) ?>">Medien</a>
                <a href="<?= e(url('forums')) ?>">Forum</a>
                <a href="<?= e(url('forms')) ?>">Formulare</a>
                <a href="<?= e(url('serverstatus')) ?>">Server</a>
                <a href="<?= e(url('game/stats')) ?>">Game</a>
                <a href="<?= e(url('profiles')) ?>">Profile</a>
                <a href="<?= e(url('groups')) ?>">Gruppen</a>
                <a href="<?= e(url('themes')) ?>">Themes</a>
                <a href="<?= e(url('plugins')) ?>">Plugins</a>
                <a href="<?= e(url('api-docs')) ?>">API</a>
                <a href="<?= e(url('search')) ?>">Suche</a>
                <?php if (current_user()): ?>
                    <a href="<?= e(url('profile')) ?>">Profil</a>
                    <a href="<?= e(url('admin')) ?>">Dashboard</a>
                    <form method="POST" action="<?= e(url('logout')) ?>" class="inline">
                        <button type="submit">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="<?= e(url('login')) ?>">Login</a>
                    <a href="<?= e(url('register')) ?>">Registrieren</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container">
        <?= $content ?>
    </main>
</body>
</html>
