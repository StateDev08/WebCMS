<?php

namespace App\Core;

use PDO;
use PDOException;

class Db
{
    private static ?PDO $pdo = null;

    public static function init(array $config): void
    {
        if (self::$pdo) {
            return;
        }

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset'] ?? 'utf8mb4'
        );

        try {
            self::$pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo 'Database connection failed.';
            exit;
        }
    }

    public static function pdo(): PDO
    {
        if (! self::$pdo) {
            throw new \RuntimeException('DB not initialized.');
        }
        return self::$pdo;
    }
}
