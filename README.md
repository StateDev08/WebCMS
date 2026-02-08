# Laravel CMS

## Setup (lokal)
- `composer install`
- `.env` erstellen (`.env.example` kopieren) und `APP_KEY` setzen (`php artisan key:generate`)
- Datenbank konfigurieren (SQLite/SQL) und `php artisan migrate --seed`
- `php artisan storage:link`
- `npm install && npm run build`
- Start: `php artisan serve`

## Demo-Daten
- Seeder für Inhalte/Module: `php artisan db:seed --class=DemoContentSeeder`
- Enthält Beispiel-Seiten, Posts, Forum, Formulare, Game-Daten, Serverstatus, Profile, Gruppen und Kommentare.

## Standard-Zugang
- Benutzer: `admin@example.com`
- Passwort: `password`
- Rolle: Administrator
- API-Token wird beim Seeding erzeugt (siehe `users.api_token`)

## Deployment (Plesk / IIS / Apache / Nginx)
Allgemein:
- Webroot muss auf `public/` zeigen.
- `.env` auf Produktionswerte setzen (DB, APP_URL, APP_KEY).
- `php artisan migrate --force`
- `php artisan storage:link`
- Cache warmmachen: `php artisan config:cache && php artisan route:cache`

### Plesk
- Domain/Dokumentenstamm auf `public/` setzen.
- PHP 8.2+ aktivieren.
- Aufgaben: `php artisan migrate --force`, `php artisan storage:link`.

### IIS (Windows)
- Datei `public/web.config` ist enthalten.
- URL Rewrite Module installieren.
- Webroot auf `public/` setzen.
- `APP_KEY` und Datenbank korrekt setzen.

### Apache
- `.htaccess` in `public/` vorhanden.
- `AllowOverride All` für die vHost/Directory-Regel aktivieren.

### Nginx (Beispiel)
```
server {
    listen 80;
    server_name example.com;
    root /var/www/cms/public;

    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```

## Headless API
- Endpunkte: `GET /api/pages`, `GET /api/pages/{slug}`, `GET /api/posts`, `GET /api/posts/{slug}`
- Auth: `Authorization: Bearer <api_token>` oder Query `?api_token=...`

## Frontend-Routen (Auszug)
- Startseite: `/`
- Seiten: `/pages/{slug}`
- Beiträge: `/posts`, `/posts/{slug}`
- Forum: `/forums`, `/forums/{forum}`, `/forums/{forum}/threads/{thread}`
- Medien: `/media`
- Formulare: `/forms`, `/forms/{slug}`
- Suche: `/search`
- Serverstatus: `/serverstatus`, `/serverstatus/{server}`
- Game: `/game/stats`, `/game/guilds`, `/game/events`, `/game/market`, `/game/matches`
- Profile: `/profiles`, `/profiles/{profile}`
- Gruppen: `/groups`, `/groups/{group}`
- API Docs: `/api-docs`
- Themes/Plugins: `/themes`, `/plugins`

## Module (Backend)
- Core: Seiten, Beiträge, Medien, Rollen/Rechte, Benutzer
- Community: Forum (Threads/Posts), Kommentare, Gruppen, Profile, Nachrichten
- Game: Serverstatus, Player-Stats, Gilden, Events, Marktplatz, Matches
- System: Themes, Plugins, Integrationen, Aktivitätslog
- Form Builder: Formulare, Felder, Submissions

## Themes & Darkmode
- Alle Themes (inkl. Default) sind Darkmode.
- Gaming-Themes: `neon`, `dark`, `fantasy`, `scifi`, `retro`
- Aktiviertes Theme wird serverseitig geladen und im Frontend per `data-theme` gesetzt.

## Analytics
- `CMS_ANALYTICS_PROVIDER=ga4` und `CMS_ANALYTICS_ID=G-XXXX` für GA4
- `CMS_ANALYTICS_PROVIDER=matomo` und `CMS_ANALYTICS_ID=https://matomo.example.com` für Matomo

## Social Login
- Google: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`
- GitHub: `GITHUB_CLIENT_ID`, `GITHUB_CLIENT_SECRET`, `GITHUB_REDIRECT_URI`

## Troubleshooting
- Bilder fehlen: `php artisan storage:link` ausführen.
- Migrationen fehlen: `php artisan migrate --seed`
- Theme/CSS nicht sichtbar: `npm run build` und Cache leeren (`php artisan config:clear`)
#
