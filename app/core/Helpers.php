<?php

use App\Core\Auth;

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function base_path(): string
{
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $base = str_replace('\\', '/', dirname($script));
    if ($base === '/' || $base === '.') {
        return '';
    }
    return rtrim($base, '/');
}

function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    $base = rtrim($_ENV['APP_URL'] ?? '', '/');
    if ($base === '') {
        $base = base_path();
    }
    if ($path === '') {
        return $base === '' ? '/' : $base.'/';
    }
    return ($base === '' ? '' : $base).'/'.$path;
}

function redirect(string $path, int $code = 302): void
{
    header('Location: '.$path, true, $code);
    exit;
}

function current_user(): ?array
{
    return Auth::user();
}

function has_permission(string $permission): bool
{
    return Auth::hasPermission($permission);
}
