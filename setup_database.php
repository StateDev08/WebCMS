<?php

/**
 * Database Setup Script
 * Erstellt die gaming_cms Datenbank und führt alle Migrationen aus
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Gaming CMS Database Setup ===\n\n";

// Hole DB Config
$host = env('DB_HOST', '127.0.0.1');
$port = env('DB_PORT', '3306');
$username = env('DB_USERNAME', 'root');
$password = env('DB_PASSWORD', '');
$database = env('DB_DATABASE', 'gaming_cms');

echo "Verbinde zu MySQL Server...\n";

try {
    // Verbinde ohne Datenbank
    $pdo = new PDO(
        "mysql:host={$host};port={$port}",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "✓ Verbindung erfolgreich\n\n";

    // Erstelle Datenbank
    echo "Erstelle Datenbank '{$database}'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Datenbank erstellt/existiert\n\n";

    // Führe Migrationen aus
    echo "Führe Migrationen aus...\n";
    echo str_repeat('-', 50) . "\n";
    
    $exitCode = Artisan::call('migrate:fresh', ['--force' => true]);
    echo Artisan::output();
    
    if ($exitCode === 0) {
        echo "\n✓ Migrationen erfolgreich\n";
    } else {
        echo "\n✗ Migrationen fehlgeschlagen\n";
        exit(1);
    }

    echo "\n=== Setup abgeschlossen ===\n";

} catch (PDOException $e) {
    echo "\n✗ Datenbankfehler: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "\n✗ Fehler: " . $e->getMessage() . "\n";
    exit(1);
}
