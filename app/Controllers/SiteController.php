<?php

namespace App\Controllers;

use App\Core\Db;
use App\Core\View;
use App\Core\Csrf;

class SiteController
{
    public function home(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM pages WHERE is_homepage = 1 AND status = 'published' LIMIT 1");
        $page = $stmt->fetch();
        View::render('site/home', ['page' => $page], 'layouts/site');
    }

    public function page(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM pages WHERE slug = :slug AND status = 'published' LIMIT 1");
        $stmt->execute(['slug' => $params['slug']]);
        $page = $stmt->fetch();
        if (! $page) {
            http_response_code(404);
            echo 'Seite nicht gefunden';
            return;
        }
        $comments = $this->loadComments('page', (int) $page['id']);
        View::render('site/page', ['page' => $page, 'comments' => $comments, 'csrf' => Csrf::token()], 'layouts/site');
    }

    public function posts(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM posts WHERE status = 'published' ORDER BY published_at DESC");
        $posts = $stmt->fetchAll();
        View::render('site/posts', ['posts' => $posts], 'layouts/site');
    }

    public function post(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM posts WHERE slug = :slug AND status = 'published' LIMIT 1");
        $stmt->execute(['slug' => $params['slug']]);
        $post = $stmt->fetch();
        if (! $post) {
            http_response_code(404);
            echo 'Beitrag nicht gefunden';
            return;
        }
        $comments = $this->loadComments('post', (int) $post['id']);
        View::render('site/post', ['post' => $post, 'comments' => $comments, 'csrf' => Csrf::token()], 'layouts/site');
    }

    public function search(): void
    {
        $q = trim($_GET['q'] ?? '');
        $like = '%'.$q.'%';
        $pages = [];
        $posts = [];
        if ($q !== '') {
            $stmt = Db::pdo()->prepare("SELECT * FROM pages WHERE status = 'published' AND (title LIKE :q OR content LIKE :q)");
            $stmt->execute(['q' => $like]);
            $pages = $stmt->fetchAll();

            $stmt = Db::pdo()->prepare("SELECT * FROM posts WHERE status = 'published' AND (title LIKE :q OR content LIKE :q)");
            $stmt->execute(['q' => $like]);
            $posts = $stmt->fetchAll();
        }

        View::render('site/search', [
            'query' => $q,
            'pages' => $pages,
            'posts' => $posts,
        ], 'layouts/site');
    }

    public function media(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM media ORDER BY id DESC");
        $media = $stmt->fetchAll();
        View::render('site/media', ['media' => $media], 'layouts/site');
    }

    public function forums(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM forums ORDER BY sort_order ASC");
        View::render('site/forums', ['forums' => $stmt->fetchAll()], 'layouts/site');
    }

    public function forum(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM forums WHERE id = :id");
        $stmt->execute(['id' => (int) $params['id']]);
        $forum = $stmt->fetch();
        if (! $forum) {
            http_response_code(404);
            echo 'Forum nicht gefunden';
            return;
        }
        $stmt = Db::pdo()->prepare("SELECT * FROM forum_threads WHERE forum_id = :id ORDER BY created_at DESC");
        $stmt->execute(['id' => (int) $params['id']]);
        View::render('site/forum', ['forum' => $forum, 'threads' => $stmt->fetchAll()], 'layouts/site');
    }

    public function thread(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM forum_threads WHERE id = :id");
        $stmt->execute(['id' => (int) $params['id']]);
        $thread = $stmt->fetch();
        if (! $thread) {
            http_response_code(404);
            echo 'Thread nicht gefunden';
            return;
        }
        $stmt = Db::pdo()->prepare("SELECT * FROM forum_posts WHERE forum_thread_id = :id AND status = 'approved' ORDER BY created_at ASC");
        $stmt->execute(['id' => (int) $params['id']]);
        View::render('site/thread', ['thread' => $thread, 'posts' => $stmt->fetchAll(), 'csrf' => Csrf::token()], 'layouts/site');
    }

    public function forms(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM forms WHERE is_active = 1 ORDER BY name ASC");
        View::render('site/forms', ['forms' => $stmt->fetchAll()], 'layouts/site');
    }

    public function form(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM forms WHERE id = :id AND is_active = 1");
        $stmt->execute(['id' => (int) $params['id']]);
        $form = $stmt->fetch();
        if (! $form) {
            http_response_code(404);
            echo 'Formular nicht gefunden';
            return;
        }
        $fields = Db::pdo()->prepare("SELECT * FROM form_fields WHERE form_id = :id ORDER BY sort_order ASC");
        $fields->execute(['id' => (int) $params['id']]);
        View::render('site/form', ['form' => $form, 'fields' => $fields->fetchAll(), 'csrf' => Csrf::token()], 'layouts/site');
    }

    public function submitForm(array $params): void
    {
        if (! Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        $stmt = Db::pdo()->prepare("SELECT * FROM forms WHERE id = :id AND is_active = 1");
        $stmt->execute(['id' => (int) $params['id']]);
        $form = $stmt->fetch();
        if (! $form) {
            http_response_code(404);
            echo 'Formular nicht gefunden';
            return;
        }

        $fields = Db::pdo()->prepare("SELECT * FROM form_fields WHERE form_id = :id ORDER BY sort_order ASC");
        $fields->execute(['id' => (int) $params['id']]);
        $fields = $fields->fetchAll();
        $data = [];
        foreach ($fields as $field) {
            $key = 'field_'.$field['id'];
            $data[$field['label']] = $_POST[$key] ?? null;
        }

        $stmt = Db::pdo()->prepare('INSERT INTO form_submissions (form_id, user_id, data, ip, user_agent, created_at) VALUES (:form_id, :user_id, :data, :ip, :ua, NOW())');
        $stmt->execute([
            'form_id' => (int) $params['id'],
            'user_id' => current_user()['id'] ?? null,
            'data' => json_encode($data),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'ua' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);

        redirect('/forms/'.$params['id']);
    }

    public function serverStatus(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM servers ORDER BY name ASC");
        View::render('site/servers', ['servers' => $stmt->fetchAll()], 'layouts/site');
    }

    public function server(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM servers WHERE id = :id");
        $stmt->execute(['id' => (int) $params['id']]);
        $server = $stmt->fetch();
        if (! $server) {
            http_response_code(404);
            echo 'Server nicht gefunden';
            return;
        }
        View::render('site/server', ['server' => $server], 'layouts/site');
    }

    public function gameStats(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM player_stats ORDER BY score DESC");
        View::render('site/game_stats', ['stats' => $stmt->fetchAll()], 'layouts/site');
    }

    public function gameStat(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM player_stats WHERE id = :id");
        $stmt->execute(['id' => (int) $params['id']]);
        $stat = $stmt->fetch();
        if (! $stat) {
            http_response_code(404);
            echo 'Stat nicht gefunden';
            return;
        }
        View::render('site/game_stat', ['stat' => $stat], 'layouts/site');
    }

    public function guilds(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM guilds ORDER BY name ASC");
        View::render('site/guilds', ['guilds' => $stmt->fetchAll()], 'layouts/site');
    }

    public function guild(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM guilds WHERE id = :id");
        $stmt->execute(['id' => (int) $params['id']]);
        $guild = $stmt->fetch();
        if (! $guild) {
            http_response_code(404);
            echo 'Gilde nicht gefunden';
            return;
        }
        View::render('site/guild', ['guild' => $guild], 'layouts/site');
    }

    public function events(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM game_events ORDER BY starts_at DESC");
        View::render('site/events', ['events' => $stmt->fetchAll()], 'layouts/site');
    }

    public function event(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM game_events WHERE id = :id");
        $stmt->execute(['id' => (int) $params['id']]);
        $event = $stmt->fetch();
        if (! $event) {
            http_response_code(404);
            echo 'Event nicht gefunden';
            return;
        }
        View::render('site/event', ['event' => $event], 'layouts/site');
    }

    public function market(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM market_items ORDER BY name ASC");
        View::render('site/market', ['items' => $stmt->fetchAll()], 'layouts/site');
    }

    public function marketItem(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM market_items WHERE id = :id");
        $stmt->execute(['id' => (int) $params['id']]);
        $item = $stmt->fetch();
        if (! $item) {
            http_response_code(404);
            echo 'Item nicht gefunden';
            return;
        }
        View::render('site/market_item', ['item' => $item], 'layouts/site');
    }

    public function matches(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM game_matches ORDER BY started_at DESC");
        View::render('site/matches', ['matches' => $stmt->fetchAll()], 'layouts/site');
    }

    public function match(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM game_matches WHERE id = :id");
        $stmt->execute(['id' => (int) $params['id']]);
        $match = $stmt->fetch();
        if (! $match) {
            http_response_code(404);
            echo 'Match nicht gefunden';
            return;
        }
        View::render('site/match', ['match' => $match], 'layouts/site');
    }

    public function profiles(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM user_profiles ORDER BY id DESC");
        View::render('site/profiles', ['profiles' => $stmt->fetchAll()], 'layouts/site');
    }

    public function profile(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM user_profiles WHERE id = :id");
        $stmt->execute(['id' => (int) $params['id']]);
        $profile = $stmt->fetch();
        if (! $profile) {
            http_response_code(404);
            echo 'Profil nicht gefunden';
            return;
        }
        View::render('site/profile', ['profile' => $profile], 'layouts/site');
    }

    public function profileSettings(): void
    {
        if (! current_user()) {
            redirect('/login');
        }
        $themes = Db::pdo()->query("SELECT * FROM themes ORDER BY name ASC")->fetchAll();
        View::render('site/profile_settings', [
            'themes' => $themes,
            'csrf' => Csrf::token(),
        ], 'layouts/site');
    }

    public function updateProfileSettings(): void
    {
        if (! current_user()) {
            redirect('/login');
        }
        if (! Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }
        $theme = trim($_POST['theme_slug'] ?? '');
        $stmt = Db::pdo()->prepare('UPDATE users SET theme_slug = :theme WHERE id = :id');
        $stmt->execute([
            'theme' => $theme !== '' ? $theme : null,
            'id' => current_user()['id'],
        ]);
        \App\Core\Auth::refresh();
        redirect('/profile');
    }

    public function groups(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM groups ORDER BY name ASC");
        View::render('site/groups', ['groups' => $stmt->fetchAll()], 'layouts/site');
    }

    public function group(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM groups WHERE id = :id");
        $stmt->execute(['id' => (int) $params['id']]);
        $group = $stmt->fetch();
        if (! $group) {
            http_response_code(404);
            echo 'Gruppe nicht gefunden';
            return;
        }
        View::render('site/group', ['group' => $group], 'layouts/site');
    }

    public function themes(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM themes ORDER BY name ASC");
        View::render('site/themes', ['themes' => $stmt->fetchAll()], 'layouts/site');
    }

    public function activateTheme(array $params): void
    {
        $slug = $params['slug'] ?? null;
        $_SESSION['theme'] = $slug;
        if (current_user() && $slug) {
            try {
                $stmt = Db::pdo()->prepare('UPDATE users SET theme_slug = :slug WHERE id = :id');
                $stmt->execute([
                    'slug' => $slug,
                    'id' => current_user()['id'],
                ]);
            } catch (\Throwable $e) {
                // Ignore if column missing
            }
        }
        redirect('/themes');
    }

    public function plugins(): void
    {
        $stmt = Db::pdo()->query("SELECT * FROM plugins ORDER BY name ASC");
        View::render('site/plugins', ['plugins' => $stmt->fetchAll()], 'layouts/site');
    }

    public function apiDocs(): void
    {
        View::render('site/api_docs', [], 'layouts/site');
    }

    public function sitemap(): void
    {
        $pages = Db::pdo()->query("SELECT slug FROM pages WHERE status = 'published'")->fetchAll();
        $posts = Db::pdo()->query("SELECT slug FROM posts WHERE status = 'published'")->fetchAll();
        $baseUrl = $_ENV['APP_URL'] ?? '';
        View::render('site/sitemap', [
            'pages' => $pages,
            'posts' => $posts,
            'baseUrl' => $baseUrl,
        ], null);
    }

    public function submitComment(): void
    {
        if (! Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        $type = $_POST['type'] ?? '';
        $id = (int) ($_POST['id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        if (! in_array($type, ['page', 'post'], true) || $id === 0 || $content === '') {
            redirect('/');
        }

        $stmt = Db::pdo()->prepare('INSERT INTO comments (commentable_type, commentable_id, user_id, content, status, created_at) VALUES (:type, :id, :user_id, :content, :status, NOW())');
        $stmt->execute([
            'type' => $type,
            'id' => $id,
            'user_id' => current_user()['id'] ?? null,
            'content' => $content,
            'status' => 'pending',
        ]);

        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    public function submitThread(array $params): void
    {
        if (! Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }
        if (! current_user()) {
            redirect('/login');
        }
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if ($title === '' || $content === '') {
            redirect('/forums/'.$params['id']);
        }
        $stmt = Db::pdo()->prepare('INSERT INTO forum_threads (forum_id, user_id, title, created_at) VALUES (:forum_id, :user_id, :title, NOW())');
        $stmt->execute([
            'forum_id' => (int) $params['id'],
            'user_id' => current_user()['id'],
            'title' => $title,
        ]);
        $threadId = (int) Db::pdo()->lastInsertId();
        $stmt = Db::pdo()->prepare('INSERT INTO forum_posts (forum_thread_id, user_id, content, status, created_at) VALUES (:thread_id, :user_id, :content, :status, NOW())');
        $stmt->execute([
            'thread_id' => $threadId,
            'user_id' => current_user()['id'],
            'content' => $content,
            'status' => 'pending',
        ]);
        redirect('/forums/threads/'.$threadId);
    }

    public function submitPost(array $params): void
    {
        if (! Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }
        if (! current_user()) {
            redirect('/login');
        }
        $content = trim($_POST['content'] ?? '');
        if ($content === '') {
            redirect('/forums/threads/'.$params['id']);
        }
        $stmt = Db::pdo()->prepare('INSERT INTO forum_posts (forum_thread_id, user_id, content, status, created_at) VALUES (:thread_id, :user_id, :content, :status, NOW())');
        $stmt->execute([
            'thread_id' => (int) $params['id'],
            'user_id' => current_user()['id'],
            'content' => $content,
            'status' => 'pending',
        ]);
        redirect('/forums/threads/'.$params['id']);
    }

    private function loadComments(string $type, int $id): array
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM comments WHERE commentable_type = :type AND commentable_id = :id AND status = 'approved' ORDER BY created_at DESC");
        $stmt->execute(['type' => $type, 'id' => $id]);
        return $stmt->fetchAll();
    }
}
