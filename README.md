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

## Installation (lokal)
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

## Plesk/Webhost (ohne install.sh)
- Anleitung: `install.html`

## Plesk Installation (Kurzfassung)
1. Projekt hochladen und entpacken.
2. Document Root in Plesk auf `public` setzen.
3. `.env` anlegen und DB-Daten eintragen.
4. `composer install --no-dev --optimize-autoloader`
5. `php artisan key:generate --force`
6. `php artisan migrate --force`
7. `php artisan storage:link`
8. Caches: `php artisan config:cache` / `route:cache` / `view:cache`
9. Assets bauen oder `public/build/` hochladen.

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

## Composer Plattform (Lokal vs. Deploy)
Wenn lokal PHP 8.2.x und der Server PHP 8.4.x nutzt, setze die Composer-Platform
pro Umgebung per Variable. Anleitung: `DEPLOY.md`

## Plesk Task (Windows, Production)
Script: `deploy-plesk.ps1`

Beispiel (Task Command):
```
powershell -ExecutionPolicy Bypass -File deploy-plesk.ps1 -ProjectPath "C:\inetpub\vhosts\httpdocs"
```

## Plesk Task (Linux, Production)
Script: `deploy-plesk.sh`

Beispiel (Task Command):
```
bash deploy-plesk.sh /var/www/vhosts/example.com/httpdocs
```

## Installation ohne Terminal (Shared Hosting)
Wenn kein SSH/Terminal verfuegbar ist, geht es so:
1. Lokal vorbereiten:
   - `composer install --no-dev --optimize-autoloader`
   - `npm install` + `npm run build`
2. Projekt komplett hochladen (inkl. `vendor/` und `public/build/`).
3. Document Root auf `public` setzen.
4. `.env` anlegen und `APP_KEY` lokal generieren:
   - lokal `php artisan key:generate`, dann `.env` hochladen.
5. Migrationen:
   - lokal `php artisan migrate` mit DB-Zugang zur Server-DB (Remote DB Zugriff muss erlaubt sein).
   - Falls kein Zugriff moeglich ist: Hosting braucht ein Terminal/Tool (sonst geht es nicht).
6. Storage Link:
   - Falls Symlinks erlaubt: `php artisan storage:link` lokal gegen Serverpfad oder per Hosting-Tool.
   - Alternativ `public/storage` manuell erstellen (Einschraenkung).

## Plesk ohne Terminal (Windows/Linux)
1. Projekt hochladen und entpacken.
2. Document Root auf `public` setzen.
3. Falls Plesk "Composer" und "Node.js" GUI anbietet: dort `composer install` und `npm run build` ausfuehren.
4. Wenn keine GUI: lokal bauen und `vendor/` + `public/build/` hochladen.
5. `.env` setzen, `APP_KEY` lokal generieren und hochladen.
6. Migrationen lokal gegen die Server-DB ausfuehren (Remote DB Zugriff notwendig).

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
