<?php

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = trim($_POST['db_host'] ?? '127.0.0.1');
    $dbPort = trim($_POST['db_port'] ?? '3306');
    $dbName = trim($_POST['db_name'] ?? '');
    $dbUser = trim($_POST['db_user'] ?? '');
    $dbPass = (string) ($_POST['db_pass'] ?? '');
    $appUrl = trim($_POST['app_url'] ?? '');
    $adminEmail = trim($_POST['admin_email'] ?? 'admin@example.com');
    $adminPass = (string) ($_POST['admin_pass'] ?? 'password');

    if ($dbName === '' || $dbUser === '') {
        $errors[] = 'Bitte DB-Name und DB-User angeben.';
    } else {
        try {
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $dbHost, $dbPort, $dbName);
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            $schemaPath = __DIR__.'/storage/db/schema.sql';
            $seedPath = __DIR__.'/storage/db/seed.sql';

            if (! file_exists($schemaPath) || ! file_exists($seedPath)) {
                throw new RuntimeException('Schema/Seed-Dateien fehlen.');
            }

            $pdo->exec(file_get_contents($schemaPath));
            $pdo->exec(file_get_contents($seedPath));

            $hash = password_hash($adminPass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE users SET email = :email, password = :password WHERE email = :admin');
            $stmt->execute([
                'email' => $adminEmail,
                'password' => $hash,
                'admin' => 'admin@example.com',
            ]);

            $envPath = __DIR__.'/.env';
            $appUrl = $appUrl !== '' ? $appUrl : ((isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.($_SERVER['HTTP_HOST'] ?? ''));
            if (file_exists($envPath)) {
                $envPath = __DIR__.'/.env.local';
            }

            $envContent = [
                'APP_NAME="PHP CMS"',
                'APP_ENV=production',
                'APP_DEBUG=false',
                'APP_URL='.$appUrl,
                'APP_LOCALE=de',
                'APP_FALLBACK_LOCALE=en',
                'APP_TIMEZONE=Europe/Berlin',
                'DB_CONNECTION=mysql',
                'DB_HOST='.$dbHost,
                'DB_PORT='.$dbPort,
                'DB_DATABASE='.$dbName,
                'DB_USERNAME='.$dbUser,
                'DB_PASSWORD='.$dbPass,
                'THEME_DEFAULT=default',
            ];
            file_put_contents($envPath, implode(PHP_EOL, $envContent));

            $sessionsPath = __DIR__.'/storage/sessions';
            if (! is_dir($sessionsPath)) {
                mkdir($sessionsPath, 0777, true);
            }
            $mediaPath = __DIR__.'/storage/media';
            if (! is_dir($mediaPath)) {
                mkdir($mediaPath, 0777, true);
            }

            $success = true;
        } catch (Throwable $e) {
            $errors[] = 'Installation fehlgeschlagen: '.$e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Installer</title>
    <style>
        body{font-family:Arial,sans-serif;background:#0b0f1a;color:#e5e7eb;margin:0;padding:24px}
        .card{background:#111827;border:1px solid #1f2937;border-radius:8px;padding:16px;margin-bottom:16px}
        label{display:block;margin:8px 0 4px}
        input{width:100%;padding:8px;border-radius:6px;border:1px solid #1f2937;background:#0f172a;color:#e5e7eb}
        button{background:#2563eb;color:#fff;border:0;padding:10px 14px;border-radius:6px;cursor:pointer;margin-top:12px}
        .alert{background:#3f1d1d;border:1px solid #7f1d1d;padding:10px;border-radius:6px}
        .ok{background:#0f2a1a;border:1px solid #14532d;padding:10px;border-radius:6px}
    </style>
</head>
<body>
    <h1>CMS Installation</h1>

    <?php if ($success): ?>
        <div class="ok card">
            Installation erfolgreich. Du kannst dich jetzt einloggen.
        </div>
    <?php endif; ?>

    <?php foreach ($errors as $error): ?>
        <div class="alert card"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endforeach; ?>

    <form method="POST" class="card">
        <h2>MariaDB Verbindung</h2>
        <label>Host</label>
        <input name="db_host" value="<?= htmlspecialchars($_POST['db_host'] ?? '127.0.0.1', ENT_QUOTES, 'UTF-8') ?>">
        <label>Port</label>
        <input name="db_port" value="<?= htmlspecialchars($_POST['db_port'] ?? '3306', ENT_QUOTES, 'UTF-8') ?>">
        <label>Datenbank</label>
        <input name="db_name" value="<?= htmlspecialchars($_POST['db_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        <label>Benutzer</label>
        <input name="db_user" value="<?= htmlspecialchars($_POST['db_user'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        <label>Passwort</label>
        <input type="password" name="db_pass">

        <h2>Admin Zugang</h2>
        <label>E‑Mail</label>
        <input name="admin_email" value="<?= htmlspecialchars($_POST['admin_email'] ?? 'admin@example.com', ENT_QUOTES, 'UTF-8') ?>">
        <label>Passwort</label>
        <input type="password" name="admin_pass" value="">

        <h2>App URL (optional)</h2>
        <label>APP_URL</label>
        <input name="app_url" value="<?= htmlspecialchars($_POST['app_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <button type="submit">Installieren</button>
    </form>
</body>
</html>
