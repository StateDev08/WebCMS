<!DOCTYPE html>
<html lang="<?= e($app['locale']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= e($app['name']) ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body data-theme="<?= e($app['theme_default']) ?>">
    <header class="navbar">
        <div class="container">
            <a class="logo" href="<?= e(url('admin')) ?>">Admin</a>
            <nav class="nav">
                <a href="<?= e(url()) ?>">Frontend</a>
                <a href="<?= e(url('admin/users')) ?>">Benutzer</a>
                <a href="<?= e(url('admin/roles')) ?>">Rollen</a>
                <a href="<?= e(url('admin/permissions')) ?>">Permissions</a>
                <?php if (current_user()): ?>
                    <form method="POST" action="<?= e(url('admin/logout')) ?>" class="inline">
                        <button type="submit">Logout</button>
                    </form>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container">
        <?= $content ?>
    </main>
</body>
</html>
