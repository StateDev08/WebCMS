<?php

require __DIR__.'/app/bootstrap.php';

$action = $argv[1] ?? null;
if (! $action) {
    echo "Usage: php cli.php migrate|seed\n";
    exit(1);
}

$pdo = App\Core\Db::pdo();

if ($action === 'migrate') {
    $sql = file_get_contents(__DIR__.'/storage/db/schema.sql');
    $pdo->exec($sql);
    echo "Migrations applied.\n";
    exit(0);
}

if ($action === 'seed') {
    $sql = file_get_contents(__DIR__.'/storage/db/seed.sql');
    $pdo->exec($sql);
    echo "Seed data applied.\n";
    exit(0);
}

echo "Unknown action.\n";
exit(1);
