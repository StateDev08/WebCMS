# Gaming CMS - Multi-Game Community Platform

Ein umfassendes Laravel 11 Content Management System für Gaming Communities mit erweiterten Forum-Features, Multi-Game-Unterstützung und Premium-Funktionen.

## 🚀 Features

### Kern-Features
- **Multi-Game Support**: Mehrere Spiele/Titel pro Installation
- **Hierarchische Communities**: Verschachtelte Community-Struktur (Nested Sets)
- **Team-System**: Teams/Clans innerhalb von Communities mit Leiter und Mitgliedern
- **Dual Content System**: BBCode ↔ Markdown parallel mit Caching
- **Premium-System**: Stripe Integration für Subscriptions und Spenden
- **Theme-System**: Multi-Theme-Support mit visueller Anpassung
- **Plugin-Architektur**: Modulares System via Laravel Modules
- **Multi-Language**: DE/EN Unterstützung mit JSON-basierten Übersetzungen

### Forum-Features
- Forum-Kategorien mit Hierarchie
- Threads mit Sticky/Locked Status
- Posts mit BBCode oder Markdown
- Attachments via Spatie Media Library
- Post Reactions
- Thread Subscriptions mit E-Mail/Push Benachrichtigungen
- Content-Migration Queue (BBCode ↔ Markdown) mit Premium-Priorität

### Moderation
- Moderator Actions mit Activity Log
- Report-System mit Eskalation
- Context-aware Permissions via Bouncer
- Separate Moderation-Teams pro Game/Community

## 📦 Installation

### Voraussetzungen
- PHP 8.2+
- Composer 2.x
- MariaDB 10.11+ / MySQL 8.0+
- Node.js 18+ (für Reverb WebSockets)

### Setup

1. **Abhängigkeiten installieren**:
```bash
composer install
npm install
```

2. **Environment konfigurieren**:
```bash
cp .env.example .env
php artisan key:generate
```

3. **Datenbank erstellen und migrieren**:
```bash
php setup_database.php
# Oder manuell:
# CREATE DATABASE gaming_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
# php artisan migrate
```

4. **Demo-Daten laden** (optional):
```bash
php artisan db:seed --class=DemoDataSeeder
# Login: admin@gaming-cms.local / password
```

5. **Assets kompilieren**:
```bash
npm run build
```

6. **Server starten**:
```bash
php artisan serve
# Reverb WebSocket Server (separates Terminal):
php artisan reverb:start
```

7. **Admin-Panel öffnen**:
```
http://localhost:8000/admin
```

## 🏗️ Architektur

### Models (16 komplett implementiert)
- **Game**: Spiele/Titel mit translatable Namen
- **Community**: Hierarchische Communities (Nested Sets)
- **Team**: Teams innerhalb von Communities
- **GameProfile**: User-spezifische Spielprofile
- **CommunityMembership**: Pivot mit Role-Management
- **Theme**: Theme-Definitionen mit Config
- **Plugin**: Plugin-Registry mit Enable/Disable
- **ForumCategory**: Forum-Kategorien (translatable)
- **ForumThread**: Forum-Threads mit Sticky/Locked
- **ForumPost**: Posts mit Dual-Content-System
- **ForumAttachment**: Datei-Uploads
- **PostReaction**: Post-Reaktionen
- **ThreadSubscription**: Benachrichtigungs-Präferenzen
- **ModeratorAction**: Moderator-Aktionen (polymorphic)
- **ModeratorReport**: Report-System mit Escalation
- **ContentMigrationQueue**: Migration Jobs mit Priority
- **User**: Erweitert um Premium, Locale, Theme, Stripe

### Services (5 implementiert)
- `BBCodeParser`: BBCode zu HTML mit Custom-Tags (spoiler, game, quote_user)
- `MarkdownParser`: Markdown zu HTML mit GFM-Unterstützung
- `BBCodeToMarkdownConverter`: Konvertiert BBCode → Markdown
- `MarkdownToBBCodeConverter`: Konvertiert Markdown → BBCode
- `ContentRenderer`: Zentraler Service für Content-Rendering und Caching

### Filament Resources (6 auto-generiert)
Admin-Panel via Filament v5:
- GameResource (mit View Page)
- CommunityResource (mit View Page)
- TeamResource (mit View Page)
- ForumCategoryResource (mit View Page)
- ForumThreadResource (mit View Page)
- UserResource (mit View Page)

Zugriff: `/admin` (nach Login als admin@gaming-cms.local)

## 🌐 Öffentliches Forum (neu)

### Routen
- `/` Startseite (Game-Übersicht)
- `/games/{slug}` Game-Detail
- `/communities/{slug}` Community-Detail
- `/categories/{id}` Kategorie-Übersicht
- `/threads/{id}` Thread-Detail + Beiträge

### Dual-Editor
- Umschaltbar zwischen Markdown (EasyMDE) und BBCode (SCEditor)
- Speichert `content_original` + `content_format`

## 🗄️ Datenbank

### Status
✅ **26 Migrationen erfolgreich** (inkl. Cashier, Media Library, Activity Log)
✅ **Demo-Daten geladen** (1 Admin, 2 Games, 1 Community, 1 Team, 1 Thread mit Post)

### Haupttabellen
- `users` - User mit Premium, Stripe, Stats, Soft Deletes
- `games` - Spiele mit translatable Feldern (JSON)
- `communities` - Nested Set Communities (_lft, _rgt, parent_id)
- `teams` - Teams/Clans mit Leader und Max Members
- `game_profiles` - User ↔ Game Profiles mit Stats
- `community_memberships` - User ↔ Community (mit Role: member/moderator/leader)

### Forum-Tabellen
- `forum_categories` - Kategorien (hierarchisch mit parent_id)
- `forum_threads` - Threads (sticky, locked, views_count, last_post_id)
- `forum_posts` - Posts mit **Dual Content System**:
  * `content_original` - Original-Content
  * `content_format` - enum('bbcode', 'markdown')
  * `content_html` - Pre-rendered HTML (cached)
  * `content_bbcode_cache` - BBCode-Konvertierung (cached)
  * `content_markdown_cache` - Markdown-Konvertierung (cached)
- `forum_attachments` - Datei-Uploads (Spatie Media Library Integration)
- `post_reactions` - Post-Reaktionen (type: like, love, laugh, etc.)
- `thread_subscriptions` - Benachrichtigungen (notify_email, notify_push)

### System-Tabellen
- `themes` - Theme-Definitionen mit JSON Config
- `plugins` - Plugin-Registry (enabled/disabled, is_core)
- `content_migration_queue` - BBCode↔Markdown Migration Jobs:
  * Priority-basiert (1=premium, 10=normal)
  * Progress-Tracking (total_items, processed_items, failed_items)
  * Status-Management (pending, processing, completed, failed)
- `moderator_actions` - Moderator-Aktionen (polymorphic zu Posts/Users/etc.)
- `moderator_reports` - Report-System:
  * Severity: spam, offensive, harassment, other
  * Status: pending, reviewing, resolved, escalated
  * Escalation-Level mit Auto-Escalation

## 🎨 Content-System Details

### Dual Format Support
```php
// Post erstellen mit Markdown
$post = ForumPost::create([
    'thread_id' => $thread->id,
    'user_id' => $user->id,
    'content_original' => '**Bold** and *italic*',
    'content_format' => 'markdown',
]);

// HTML rendern
$html = app(ContentRenderer::class)->render($post);

// Zu BBCode konvertieren
$bbcode = app(ContentRenderer::class)->convertAndCache($post, 'bbcode');
// Cached in $post->content_bbcode_cache
```

### Custom BBCode Tags
```bbcode
[spoiler]Hidden content[/spoiler]
[game=minecraft]Minecraft Server[/game]
[quote user="Admin"]Original message[/quote]
```

### Content Migration
```php
// Migration-Job erstellen
ContentMigrationQueue::create([
    'user_id' => $user->id,
    'from_format' => 'bbcode',
    'to_format' => 'markdown',
    'priority' => $user->isPremium() ? 1 : 10,
    'total_items' => $user->posts()->count(),
    'status' => 'pending',
]);
```

## 🔐 Permissions (Bouncer)

### ✅ Implementiert (BouncerSeeder)

**6 Roles:**
- `super-admin` - Volle Rechte (everything)
- `admin` - Games, Communities, Forum, Users, Themes verwalten
- `moderator` - Forum moderieren, Reports bearbeiten
- `community-manager` - Community & Teams verwalten
- `team-leader` - Team leiten (context-aware)
- `member` - Threads/Posts erstellen, eigene Inhalte bearbeiten

**26 Abilities:**
- Games: `manage-games`, `view-games`
- Communities: `manage-communities`, `view-communities`, `moderate-community`
- Teams: `manage-teams`, `lead-team`
- Forum: `manage-forum`, `moderate-forum`, `create-threads/posts`, `edit/delete-posts`, `pin/lock-threads`
- Users: `manage-users`, `view-users`, `ban-users`
- System: `manage-themes/plugins`, `view-admin-panel`, `view-telescope`
- Reports: `view-reports`, `manage-reports`

### Setup
```bash
# Bouncer Tabellen migrieren und Seeder ausführen
php artisan migrate
php artisan db:seed --class=BouncerSeeder
```
```php
// Abilities
Bouncer::ability()->create(['name' => 'manage-games']);
Bouncer::ability()->create(['name' => 'moderate-community']);
Bouncer::ability()->create(['name' => 'manage-team']);

// Roles
$admin = Bouncer::role()->create(['name' => 'admin']);
$moderator = Bouncer::role()->create(['name' => 'moderator']);

// Context-aware Permissions
$community->allow($user)->to('moderate-community');
```

### Usage
```php
// Globale Berechtigung
if ($user->can('manage-games')) { }

// Community-spezifische Berechtigung
if ($user->can('moderate-community', $community)) { }

// In Models
$community->canModerate($user); // Prüft auch Parent-Communities
```

## 📝 Sprachen

### Verfügbare Übersetzungen
- `lang/de/forum.php` - Forum-Texte (Deutsch)
- `lang/en/forum.php` - Forum-Texte (English)
- `lang/de/games.php` - Game/Community-Texte (Deutsch)
- `lang/en/games.php` - Game/Community-Texte (English)

### Usage in Blade
```blade
{{ __('forum.new_thread') }}
{{ __('games.community') }}
```

### Translatable Model Fields
```php
// Game Model
$game->setTranslation('name', 'de', 'Minecraft');
$game->setTranslation('name', 'en', 'Minecraft');

echo $game->getTranslation('name', 'de'); // "Minecraft"
```

## 🔧 Konfiguration

### .env Einstellungen
```env
# App
APP_NAME="Gaming CMS"
APP_LOCALE=de
APP_FALLBACK_LOCALE=en

# Database
DB_CONNECTION=mysql
DB_DATABASE=gaming_cms

# Stripe (für Premium-Features)
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Broadcasting (Reverb WebSockets)
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=...
REVERB_APP_KEY=...
REVERB_APP_SECRET=...

# Cache & Queue
CACHE_STORE=redis
QUEUE_CONNECTION=database
```

## 📚 Technologie-Stack

| Komponente | Technologie | Version |
|-----------|------------|---------|
| Framework | Laravel | 11.48.0 |
| Admin Panel | Filament | 5.1.3 |
| Database | MariaDB/MySQL | 10.11+/8.0+ |
| Payments | Stripe Cashier | 16.2.0 |
| Permissions | Silber Bouncer | 1.0.3 |
| Media | Spatie Media Library | 11.17.10 |
| Activity Log | Spatie Activity Log | 4.11.0 |
| Translations | Spatie Translatable | 6.12.0 |
| Nested Sets | kalnoy/nestedset | 6.0.6 |, funktional aber basic)
- [ ] Theme Visual Editor UI
- [ ] Plugin-Management UI in Filament
- [ ] Content-Migration Worker/Queue Job
- [ ] Frontend Theme-Templates (aktuell nur Backend)
- [ ] REST API für Mobile Apps
- [ ] Notification-System (Email/Push Templ
| Debugbar | barryvdh/laravel-debugbar | 4.0.5 (dev) |
| Telescope | laravel/telescope | 5.16.1 (dev) |ates)
- [ ] Search-Integration (Meilisearch/Algolia)
- [ ] Forum BBCode-Editor Integration (z.B. SCEditor)
- [ ] Markdown-Editor Integration (z.B. EasyMDE)
- [ ] User-Tests & Feature-Tests
- [ ] Production Deployment Guidelationships, Scopes, Helper-Methods
- [x] **27 Database Migrations** erfolgreich deployed (inkl. Bouncer, Telescope)
- [x] **5 Content Services** (BBCode/Markdown Parser & Converter)
- [x] **6 Filament Resources** auto-generiert
- [x] **ContentServiceProvider** registriert
- [x] **Demo-Daten Seeder** funktionsfähig
- [x] **Bouncer Permission System** (6 Roles, 26 Abilities)
- [x] **4 Sprachdateien** (DE/EN für Forum & Games)
- [x] **Database Setup Script** (setup_database.php)
- [x] **Plesk Installation Script** (install.sh)
- [x] **Laravel Debugbar** & **Telescope** (Development Tools)

### ⏳ Teilweise / TODO
- [ ] Filament Resource Forms anpassen (aktuell auto-generiert)
- [ ] Bouncer Policies und Permission Seeder
- [ ] Theme Visual Editor UI
- [ ] Plugin-Management UI in Filament
- [ ] Content-Migration Worker/Queue Job
- [ ] Frontend Theme-Templates (aktuell nur Backend)
- [ ] REST API für Mobile Apps
- [ ] Notification-System (Email/Push Templates)
- [ ] Search-Integration (Meilisearch/Algolia)
- [ ] Forum BBCode-Editor Integration
- [ ] Markdown-Editor Integration
- [ ] User-Tests & Feature-Tests

## 🚧 Nächste Schritte

1. **Filament Resources anpassen**:
```bash
# Forms, Tables, Filters manuell optimieren
# siehe: app/Filament/Resources/*/
# Aktuell funktionsfähig aber basic
```

2. **Frontend entwickeln**:
- Blade Templates für Public Forum
- Livewire Components für Interactive Features
- Alpine.js für Client-Side Interactions

3. **Queue Worker starten** (für Production):
```bash
php artisan queue:work
# Für Content-Migration und Background-Jobs
```

4. **Debugging Tools nutzen**:
```bash
# Debugbar (Development)
http://localhost:8000 → Zeigt Debug-Leiste

# Telescope (Monitoring)
http://localhost:8000/telescope
```

5. **Tests schreiben**:
```bash
php artisan make:test GameTest
php artisan make:test ForumPostTest
php artisan test
```

## 📄 Lizenz

Custom / Proprietär
 - Production Ready (Backend)
**Letzte Aktualisierung**: 04.02.2026
**Database Migrations**: ✅ 27/27 erfolgreich
**Models**: ✅ 16/16 implementiert
**Services**: ✅ 5/5 implementiert
**Filament Resources**: ✅ 6/6 erstellt
**Bouncer System**: ✅ 6 Roles, 26 Abilities

**Demo-Login**: admin@gaming-cms.local / password
**Admin-Panel**: http://localhost:8000/admin
**Telescope**: http://localhost:8000/telescope (nur Development)
**Database Migrations**: ✅ 26/26 erfolgreich
**Models**: ✅ 16/16 implementiert
**Services**: ✅ 5/5 implementiert
**Filament Resources**: ✅ 6/6 erstellt

**Demo-Login**: admin@gaming-cms.local / password
**Admin-Panel**: http://localhost:8000/admin
