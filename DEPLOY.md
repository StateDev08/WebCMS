## Deployment (separate Composer platform)

Die lokale Entwicklungsumgebung nutzt PHP 8.2.x, der Server laeuft mit PHP 8.4.x.
Um Konflikte zu vermeiden, setze die Composer-Platform je Umgebung per Umgebungsvariable.

### Lokal (PHP 8.2.x)
```
set COMPOSER_PLATFORM_PHP=8.2.12
composer install
```

### Server (PHP 8.4.x)
```
set COMPOSER_PLATFORM_PHP=8.4.17
composer install --no-dev --optimize-autoloader
```

Wenn der Lockfile-Stand nicht passt, einmalig:
```
set COMPOSER_PLATFORM_PHP=8.4.17
composer update
```

### Hinweis
Der Wert muss zur realen PHP-Version passen. Ansonsten kann Composer ungueltige
Pakete installieren oder abbrechen.
