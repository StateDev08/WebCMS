<?php

return [
    'app' => [
        'name' => getenv('APP_NAME') ?: 'PHP CMS',
        'env' => getenv('APP_ENV') ?: 'production',
        'debug' => filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN),
        'url' => getenv('APP_URL') ?: '',
        'locale' => getenv('APP_LOCALE') ?: 'de',
        'fallback_locale' => getenv('APP_FALLBACK_LOCALE') ?: 'en',
        'timezone' => getenv('APP_TIMEZONE') ?: 'Europe/Berlin',
        'key' => getenv('APP_KEY') ?: '',
        'theme_default' => getenv('THEME_DEFAULT') ?: 'default',
        'upload_path' => getenv('UPLOAD_PATH') ?: __DIR__.'/../storage/media',
    ],
    'db' => [
        'driver' => getenv('DB_CONNECTION') ?: 'mysql',
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'database' => getenv('DB_DATABASE') ?: 'cms',
        'username' => getenv('DB_USERNAME') ?: 'cms_user',
        'password' => getenv('DB_PASSWORD') ?: '',
        'charset' => 'utf8mb4',
    ],
];
