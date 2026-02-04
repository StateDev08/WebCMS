# Gaming CMS (Laravel 11)

Ein modulares CMS mit Forum-Frontend, oeffentlichem CMS-Frontend und Admin-Redaktion via Filament.
Schwerpunkt: klassische CMS-Funktionen (Pages/Posts/Blocks, Menues, Rollen) plus Community/Forum.

## Features
- CMS: Pages, Posts, Blocks (Builder) mit SEO-Feldern
- Menue-Builder (Drag & Drop im Admin)
- Forum-Frontend + Admin (Filament)
- Rollen/Rechte via Bouncer (u. a. `manage-pages`, `manage-posts`, `manage-menus`)
- Oeffentliches CMS-Frontend unter `/cms/...`

## Voraussetzungen
- PHP 8.2.x (getestet mit 8.2.12)
- MySQL 8+ / MariaDB 10.11+
- Composer 2.x
- Node.js 18+ (Assets; empfohlen 20+)

## Installation
### A) Plesk Laravel Toolkit (empfohlen)
1. Plesk → Websites & Domains → Laravel (Toolkit)
2. App hinzufügen / Projektordner wählen
3. Document Root = `public`
4. Composer Dependencies installieren (`--no-dev --optimize-autoloader`)
5. `.env` bearbeiten (APP_ENV=production, APP_DEBUG=false, DB-Daten)
6. APP_KEY generieren
7. Migrationen ausführen (`--force`)
8. Storage Link erstellen
9. Optional: `npm install` + `npm run build` (oder lokal bauen und `public/build/` hochladen)

### B) Plesk ohne Terminal
1. Lokal vorbereiten:
   - `composer install --no-dev --optimize-autoloader`
   - `npm install` + `npm run build`
2. Projekt komplett hochladen (inkl. `vendor/` und `public/build/`).
3. Document Root auf `public` setzen.
4. `.env` anlegen, `APP_KEY` lokal generieren und hochladen.
5. Migrationen lokal gegen die Server-DB ausführen (Remote DB Zugriff erforderlich).
6. Storage Link falls möglich; sonst `public/storage` manuell anlegen.

### C) Lokale Entwicklung
```
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=DemoDataSeeder
npm install
npm run dev
php artisan serve
```

## Demo-Login
- Admin: `admin@gaming-cms.local` / `password`

## Wichtige URLs
### Admin
- `http://localhost:8000/admin`

### CMS-Frontend
- Startseite (Demo): `http://localhost:8000/cms/pages/home`
- Beitraege: `http://localhost:8000/cms/posts`
- Beitrag: `http://localhost:8000/cms/posts/{slug}`

### Forum
- Start: `http://localhost:8000/`
- Thread: `http://localhost:8000/threads/{id}`

## Content-Builder (Blocks)
- `text`: einfacher Text
- `image`: Bild (URL + Alt)
- `button`: Button (Label, URL, Style)
- `gallery`: mehrere Bilder
- `columns`: 2-3 Textspalten

## Rollen & Rechte (Auszug)
- `manage-pages`, `manage-posts`, `manage-menus`
- Rolle `editor` fuer Redaktion

## Apache (vHost) Beispiel
```
<VirtualHost *:80>
    ServerName deine-domain.de
    DocumentRoot "D:/pfad/zum/projekt/public"

    <Directory "D:/pfad/zum/projekt/public">
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "logs/laravel_error.log"
    CustomLog "logs/laravel_access.log" combined
</VirtualHost>
```

## Apache Hinweise
- `mod_rewrite` aktivieren
- Document Root muss auf `public` zeigen
- In `.env`: `APP_URL` setzen

## Hinweise zu PHP 8.2
Falls Composer PHP 8.3+ fordert:
1. `composer clear-cache`
2. `composer update`
3. Falls noetig: `vendor/` loeschen und `composer install`

## Entwicklung
- Assets: `npm run dev` oder `npm run build`
- Cache leeren: `php artisan optimize:clear`

## Lizenz
Proprietaer (projekt-spezifisch)
