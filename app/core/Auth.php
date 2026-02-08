<?php

namespace App\Core;

use App\Core\Db;

class Auth
{
    private static ?array $user = null;

    public static function init(): void
    {
        if (! empty($_SESSION['user_id'])) {
            self::$user = self::findUser((int) $_SESSION['user_id']);
        }
    }

    public static function attempt(string $email, string $password): bool
    {
        $stmt = Db::pdo()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if (! $user || ! password_verify($password, $user['password'])) {
            return false;
        }
        $_SESSION['user_id'] = $user['id'];
        self::$user = $user;
        return true;
    }

    public static function register(array $data): int
    {
        $stmt = Db::pdo()->prepare('INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, NOW())');
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
        return (int) Db::pdo()->lastInsertId();
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        self::$user = null;
    }

    public static function user(): ?array
    {
        return self::$user;
    }

    public static function check(): bool
    {
        return self::$user !== null;
    }

    public static function hasPermission(string $permission): bool
    {
        if (! self::$user) {
            return false;
        }

        $stmt = Db::pdo()->prepare('
            SELECT p.slug
            FROM permissions p
            INNER JOIN permission_role pr ON pr.permission_id = p.id
            INNER JOIN role_user ru ON ru.role_id = pr.role_id
            WHERE ru.user_id = :user_id AND p.slug = :slug
            LIMIT 1
        ');
        $stmt->execute([
            'user_id' => self::$user['id'],
            'slug' => $permission,
        ]);
        return (bool) $stmt->fetch();
    }

    public static function hasRole(string $roleSlug): bool
    {
        if (! self::$user) {
            return false;
        }

        $stmt = Db::pdo()->prepare('
            SELECT r.slug
            FROM roles r
            INNER JOIN role_user ru ON ru.role_id = r.id
            WHERE ru.user_id = :user_id AND r.slug = :slug
            LIMIT 1
        ');
        $stmt->execute([
            'user_id' => self::$user['id'],
            'slug' => $roleSlug,
        ]);
        return (bool) $stmt->fetch();
    }

    public static function refresh(): void
    {
        if (! empty($_SESSION['user_id'])) {
            self::$user = self::findUser((int) $_SESSION['user_id']);
        }
    }

    private static function findUser(int $id): ?array
    {
        $stmt = Db::pdo()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }
}
