<?php

return [
    'pages' => [
        'table' => 'pages',
        'title' => 'Seiten',
        'fields' => [
            'title' => ['label' => 'Titel', 'type' => 'text'],
            'slug' => ['label' => 'Slug', 'type' => 'text'],
            'content' => ['label' => 'Inhalt', 'type' => 'textarea'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['draft', 'published', 'scheduled']],
            'locale' => ['label' => 'Sprache', 'type' => 'text'],
            'is_homepage' => ['label' => 'Startseite', 'type' => 'checkbox'],
            'meta_title' => ['label' => 'Meta Titel', 'type' => 'text'],
            'meta_description' => ['label' => 'Meta Beschreibung', 'type' => 'textarea'],
        ],
    ],
    'posts' => [
        'table' => 'posts',
        'title' => 'Beiträge',
        'fields' => [
            'title' => ['label' => 'Titel', 'type' => 'text'],
            'slug' => ['label' => 'Slug', 'type' => 'text'],
            'excerpt' => ['label' => 'Kurztext', 'type' => 'textarea'],
            'content' => ['label' => 'Inhalt', 'type' => 'textarea'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['draft', 'published', 'scheduled']],
            'locale' => ['label' => 'Sprache', 'type' => 'text'],
            'is_ugc' => ['label' => 'UGC', 'type' => 'checkbox'],
            'meta_title' => ['label' => 'Meta Titel', 'type' => 'text'],
            'meta_description' => ['label' => 'Meta Beschreibung', 'type' => 'textarea'],
        ],
    ],
    'users' => [
        'table' => 'users',
        'title' => 'Benutzer',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'email' => ['label' => 'E-Mail', 'type' => 'text'],
            'password' => ['label' => 'Passwort', 'type' => 'password'],
            'theme_slug' => ['label' => 'Theme', 'type' => 'text'],
            'roles' => ['label' => 'Rollen', 'type' => 'roles'],
        ],
    ],
    'roles' => [
        'table' => 'roles',
        'title' => 'Rollen',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'slug' => ['label' => 'Slug', 'type' => 'text'],
        ],
    ],
    'permissions' => [
        'table' => 'permissions',
        'title' => 'Permissions',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'slug' => ['label' => 'Slug', 'type' => 'text'],
        ],
    ],
    'media' => [
        'table' => 'media',
        'title' => 'Medien',
        'fields' => [
            'original_name' => ['label' => 'Dateiname', 'type' => 'text'],
            'path' => ['label' => 'Pfad', 'type' => 'text'],
            'mime_type' => ['label' => 'MIME', 'type' => 'text'],
            'size' => ['label' => 'Größe', 'type' => 'number'],
            'folder' => ['label' => 'Ordner', 'type' => 'text'],
            'tags' => ['label' => 'Tags (CSV)', 'type' => 'text'],
            'alt' => ['label' => 'Alt', 'type' => 'text'],
        ],
    ],
    'forums' => [
        'table' => 'forums',
        'title' => 'Foren',
        'fields' => [
            'title' => ['label' => 'Titel', 'type' => 'text'],
            'description' => ['label' => 'Beschreibung', 'type' => 'textarea'],
            'sort_order' => ['label' => 'Sortierung', 'type' => 'number'],
        ],
    ],
    'forum_threads' => [
        'table' => 'forum_threads',
        'title' => 'Forum Threads',
        'fields' => [
            'forum_id' => ['label' => 'Forum ID', 'type' => 'number'],
            'user_id' => ['label' => 'User ID', 'type' => 'number'],
            'title' => ['label' => 'Titel', 'type' => 'text'],
            'is_locked' => ['label' => 'Gesperrt', 'type' => 'checkbox'],
        ],
    ],
    'forum_posts' => [
        'table' => 'forum_posts',
        'title' => 'Forum Posts',
        'fields' => [
            'forum_thread_id' => ['label' => 'Thread ID', 'type' => 'number'],
            'user_id' => ['label' => 'User ID', 'type' => 'number'],
            'content' => ['label' => 'Inhalt', 'type' => 'textarea'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['pending', 'approved', 'rejected']],
        ],
    ],
    'comments' => [
        'table' => 'comments',
        'title' => 'Kommentare',
        'fields' => [
            'commentable_type' => ['label' => 'Typ', 'type' => 'text'],
            'commentable_id' => ['label' => 'Typ ID', 'type' => 'number'],
            'user_id' => ['label' => 'User ID', 'type' => 'number'],
            'content' => ['label' => 'Inhalt', 'type' => 'textarea'],
            'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['pending', 'approved', 'rejected']],
        ],
    ],
    'groups' => [
        'table' => 'groups',
        'title' => 'Gruppen',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'description' => ['label' => 'Beschreibung', 'type' => 'textarea'],
        ],
    ],
    'user_profiles' => [
        'table' => 'user_profiles',
        'title' => 'Profile',
        'fields' => [
            'user_id' => ['label' => 'User ID', 'type' => 'number'],
            'bio' => ['label' => 'Bio', 'type' => 'textarea'],
            'website' => ['label' => 'Webseite', 'type' => 'text'],
            'location' => ['label' => 'Ort', 'type' => 'text'],
        ],
    ],
    'servers' => [
        'table' => 'servers',
        'title' => 'Server',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'type' => ['label' => 'Typ', 'type' => 'select', 'options' => ['web', 'game']],
            'host' => ['label' => 'Host', 'type' => 'text'],
            'port' => ['label' => 'Port', 'type' => 'number'],
            'status' => ['label' => 'Status', 'type' => 'text'],
        ],
    ],
    'player_stats' => [
        'table' => 'player_stats',
        'title' => 'Player Stats',
        'fields' => [
            'user_id' => ['label' => 'User ID', 'type' => 'number'],
            'player_name' => ['label' => 'Spieler', 'type' => 'text'],
            'external_id' => ['label' => 'Externe ID', 'type' => 'text'],
            'score' => ['label' => 'Score', 'type' => 'number'],
            'rank' => ['label' => 'Rank', 'type' => 'number'],
            'stats' => ['label' => 'Stats (JSON)', 'type' => 'textarea'],
        ],
    ],
    'guilds' => [
        'table' => 'guilds',
        'title' => 'Gilden',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'tag' => ['label' => 'Tag', 'type' => 'text'],
            'description' => ['label' => 'Beschreibung', 'type' => 'textarea'],
            'owner_id' => ['label' => 'Owner ID', 'type' => 'number'],
        ],
    ],
    'game_events' => [
        'table' => 'game_events',
        'title' => 'Events',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'description' => ['label' => 'Beschreibung', 'type' => 'textarea'],
            'starts_at' => ['label' => 'Start', 'type' => 'datetime'],
            'ends_at' => ['label' => 'Ende', 'type' => 'datetime'],
            'location' => ['label' => 'Ort', 'type' => 'text'],
        ],
    ],
    'market_items' => [
        'table' => 'market_items',
        'title' => 'Marktplatz',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'description' => ['label' => 'Beschreibung', 'type' => 'textarea'],
            'price' => ['label' => 'Preis', 'type' => 'number'],
            'currency' => ['label' => 'Währung', 'type' => 'text'],
            'is_available' => ['label' => 'Verfügbar', 'type' => 'checkbox'],
        ],
    ],
    'game_matches' => [
        'table' => 'game_matches',
        'title' => 'Matches',
        'fields' => [
            'map' => ['label' => 'Map', 'type' => 'text'],
            'started_at' => ['label' => 'Start', 'type' => 'datetime'],
            'ended_at' => ['label' => 'Ende', 'type' => 'datetime'],
            'result' => ['label' => 'Result (JSON)', 'type' => 'textarea'],
        ],
    ],
    'integrations' => [
        'table' => 'integrations',
        'title' => 'Integrationen',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'provider' => ['label' => 'Provider', 'type' => 'text'],
            'config' => ['label' => 'Config (JSON)', 'type' => 'textarea'],
            'is_active' => ['label' => 'Aktiv', 'type' => 'checkbox'],
        ],
    ],
    'forms' => [
        'table' => 'forms',
        'title' => 'Formulare',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'slug' => ['label' => 'Slug', 'type' => 'text'],
            'description' => ['label' => 'Beschreibung', 'type' => 'textarea'],
            'is_active' => ['label' => 'Aktiv', 'type' => 'checkbox'],
        ],
    ],
    'form_fields' => [
        'table' => 'form_fields',
        'title' => 'Formularfelder',
        'fields' => [
            'form_id' => ['label' => 'Form ID', 'type' => 'number'],
            'label' => ['label' => 'Label', 'type' => 'text'],
            'type' => ['label' => 'Typ', 'type' => 'text'],
            'is_required' => ['label' => 'Pflicht', 'type' => 'checkbox'],
            'options' => ['label' => 'Optionen (JSON/CSV)', 'type' => 'textarea'],
            'sort_order' => ['label' => 'Sortierung', 'type' => 'number'],
        ],
    ],
    'form_submissions' => [
        'table' => 'form_submissions',
        'title' => 'Formular-Eingänge',
        'fields' => [
            'form_id' => ['label' => 'Form ID', 'type' => 'number'],
            'user_id' => ['label' => 'User ID', 'type' => 'number'],
            'data' => ['label' => 'Daten (JSON)', 'type' => 'textarea'],
            'ip' => ['label' => 'IP', 'type' => 'text'],
            'user_agent' => ['label' => 'User Agent', 'type' => 'textarea'],
        ],
    ],
    'themes' => [
        'table' => 'themes',
        'title' => 'Themes',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'slug' => ['label' => 'Slug', 'type' => 'text'],
            'description' => ['label' => 'Beschreibung', 'type' => 'textarea'],
            'is_active' => ['label' => 'Aktiv', 'type' => 'checkbox'],
        ],
    ],
    'plugins' => [
        'table' => 'plugins',
        'title' => 'Plugins',
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text'],
            'slug' => ['label' => 'Slug', 'type' => 'text'],
            'description' => ['label' => 'Beschreibung', 'type' => 'textarea'],
            'is_active' => ['label' => 'Aktiv', 'type' => 'checkbox'],
        ],
    ],
    'activity_logs' => [
        'table' => 'activity_logs',
        'title' => 'Audit Log',
        'fields' => [
            'user_id' => ['label' => 'User ID', 'type' => 'number'],
            'action' => ['label' => 'Aktion', 'type' => 'text'],
            'meta' => ['label' => 'Meta (JSON)', 'type' => 'textarea'],
        ],
    ],
    'conversations' => [
        'table' => 'conversations',
        'title' => 'Konversationen',
        'fields' => [
            'subject' => ['label' => 'Betreff', 'type' => 'text'],
            'is_group' => ['label' => 'Gruppe', 'type' => 'checkbox'],
        ],
    ],
    'messages' => [
        'table' => 'messages',
        'title' => 'Nachrichten',
        'fields' => [
            'conversation_id' => ['label' => 'Konversation ID', 'type' => 'number'],
            'user_id' => ['label' => 'User ID', 'type' => 'number'],
            'content' => ['label' => 'Inhalt', 'type' => 'textarea'],
        ],
    ],
    'settings' => [
        'table' => 'settings',
        'title' => 'Einstellungen',
        'fields' => [
            'key' => ['label' => 'Key', 'type' => 'text'],
            'value' => ['label' => 'Value', 'type' => 'textarea'],
        ],
    ],
];
