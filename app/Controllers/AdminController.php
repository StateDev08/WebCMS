<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Db;
use App\Core\View;
use App\Core\Csrf;

class AdminController
{
    private array $modules;

    public function __construct()
    {
        $this->modules = require __DIR__.'/../modules.php';
    }

    public function dashboard(): void
    {
        $this->requireAuth();
        $counts = [];
        foreach ($this->modules as $key => $module) {
            $stmt = Db::pdo()->query('SELECT COUNT(*) as cnt FROM '.$module['table']);
            $counts[$key] = (int) $stmt->fetch()['cnt'];
        }

        View::render('admin/dashboard', ['modules' => $this->modules, 'counts' => $counts], 'layouts/admin');
    }

    public function index(array $params): void
    {
        $this->requireAuth();
        $moduleKey = $params['module'];
        $module = $this->module($moduleKey);
        $this->authorizeModule($moduleKey);

        $rows = [];
        $filters = [];

        if ($moduleKey === 'media') {
            $sql = 'SELECT * FROM media WHERE 1=1';
            $params = [];
            if (! empty($_GET['folder'])) {
                $sql .= ' AND folder = :folder';
                $params['folder'] = $_GET['folder'];
            }
            if (! empty($_GET['tag'])) {
                $sql .= ' AND tags LIKE :tag';
                $params['tag'] = '%'.$_GET['tag'].'%';
            }
            if (! empty($_GET['q'])) {
                $sql .= ' AND (original_name LIKE :q OR path LIKE :q)';
                $params['q'] = '%'.$_GET['q'].'%';
            }
            $sql .= ' ORDER BY id DESC';
            $stmt = Db::pdo()->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll();

            $folders = Db::pdo()->query('SELECT DISTINCT folder FROM media WHERE folder IS NOT NULL AND folder <> "" ORDER BY folder ASC')->fetchAll();
            $tagsRows = Db::pdo()->query('SELECT tags FROM media WHERE tags IS NOT NULL AND tags <> ""')->fetchAll();
            $tags = [];
            foreach ($tagsRows as $row) {
                $decoded = json_decode($row['tags'], true);
                if (is_array($decoded)) {
                    foreach ($decoded as $tag) {
                        $tags[] = $tag;
                    }
                } else {
                    foreach (array_map('trim', explode(',', (string) $row['tags'])) as $tag) {
                        if ($tag !== '') {
                            $tags[] = $tag;
                        }
                    }
                }
            }
            $filters = [
                'folders' => array_values(array_unique(array_filter(array_map(fn ($r) => $r['folder'], $folders)))),
                'tags' => array_values(array_unique($tags)),
            ];
        } else {
            $stmt = Db::pdo()->query('SELECT * FROM '.$module['table'].' ORDER BY id DESC');
            $rows = $stmt->fetchAll();
        }

        View::render('admin/index', [
            'moduleKey' => $moduleKey,
            'module' => $module,
            'rows' => $rows,
            'filters' => $filters,
        ], 'layouts/admin');
    }

    public function create(array $params): void
    {
        $this->requireAuth();
        $moduleKey = $params['module'];
        $module = $this->module($moduleKey);
        $this->authorizeModule($moduleKey);

        View::render('admin/form', [
            'moduleKey' => $moduleKey,
            'module' => $module,
            'record' => [],
            'csrf' => Csrf::token(),
            'roles' => $this->rolesList(),
            'themes' => $this->themesList(),
        ], 'layouts/admin');
    }

    public function store(array $params): void
    {
        $this->requireAuth();
        $moduleKey = $params['module'];
        $module = $this->module($moduleKey);
        $this->authorizeModule($moduleKey);
        $this->assertCsrf();

        $data = $this->collectFields($moduleKey, $module);
        if ($moduleKey === 'media' && ! empty($_FILES['file']['tmp_name'])) {
            $uploadDir = getenv('UPLOAD_PATH') ?: __DIR__.'/../../storage/media';
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $filename = basename($_FILES['file']['name']);
            $target = rtrim($uploadDir, '/\\').DIRECTORY_SEPARATOR.$filename;
            move_uploaded_file($_FILES['file']['tmp_name'], $target);
            $data['original_name'] = $filename;
            $data['path'] = 'storage/media/'.$filename;
            $data['mime_type'] = $_FILES['file']['type'] ?? null;
            $data['size'] = $_FILES['file']['size'] ?? null;
        }
        $columns = array_keys($data);
        $placeholders = array_map(fn ($c) => ':'.$c, $columns);

        $sql = 'INSERT INTO '.$module['table'].' ('.implode(',', $columns).', created_at, updated_at) VALUES ('.implode(',', $placeholders).', NOW(), NOW())';
        $stmt = Db::pdo()->prepare($sql);
        $stmt->execute($data);
        $newId = (int) Db::pdo()->lastInsertId();
        if ($moduleKey === 'users') {
            $this->syncUserRoles($newId, $_POST['roles'] ?? []);
        }
        $this->logAction('create_'.$moduleKey, ['data' => $data]);

        redirect('/admin/'.$moduleKey);
    }

    public function show(array $params): void
    {
        $this->requireAuth();
        $moduleKey = $params['module'];
        $module = $this->module($moduleKey);
        $this->authorizeModule($moduleKey);
        $record = $this->findRecord($module['table'], (int) $params['id']);

        View::render('admin/show', [
            'moduleKey' => $moduleKey,
            'module' => $module,
            'record' => $record,
        ], 'layouts/admin');
    }

    public function edit(array $params): void
    {
        $this->requireAuth();
        $moduleKey = $params['module'];
        $module = $this->module($moduleKey);
        $this->authorizeModule($moduleKey);
        $record = $this->findRecord($module['table'], (int) $params['id']);

        View::render('admin/form', [
            'moduleKey' => $moduleKey,
            'module' => $module,
            'record' => $record,
            'csrf' => Csrf::token(),
            'roles' => $this->rolesList(),
            'themes' => $this->themesList(),
            'userRoles' => $moduleKey === 'users' ? $this->userRoleIds((int) $params['id']) : [],
        ], 'layouts/admin');
    }

    public function update(array $params): void
    {
        $this->requireAuth();
        $moduleKey = $params['module'];
        $module = $this->module($moduleKey);
        $this->authorizeModule($moduleKey);
        $this->assertCsrf();

        $data = $this->collectFields($moduleKey, $module);
        $assignments = array_map(fn ($c) => $c.' = :'.$c, array_keys($data));
        $data['id'] = (int) $params['id'];

        $sql = 'UPDATE '.$module['table'].' SET '.implode(',', $assignments).', updated_at = NOW() WHERE id = :id';
        $stmt = Db::pdo()->prepare($sql);
        $stmt->execute($data);
        if ($moduleKey === 'users') {
            $this->syncUserRoles((int) $params['id'], $_POST['roles'] ?? []);
        }
        $this->logAction('update_'.$moduleKey, ['id' => (int) $params['id'], 'data' => $data]);

        redirect('/admin/'.$moduleKey);
    }

    public function destroy(array $params): void
    {
        $this->requireAuth();
        $moduleKey = $params['module'];
        $module = $this->module($moduleKey);
        $this->authorizeModule($moduleKey);
        $this->assertCsrf();

        $stmt = Db::pdo()->prepare('DELETE FROM '.$module['table'].' WHERE id = :id');
        $stmt->execute(['id' => (int) $params['id']]);
        $this->logAction('delete_'.$moduleKey, ['id' => (int) $params['id']]);

        redirect('/admin/'.$moduleKey);
    }

    private function module(string $key): array
    {
        if (! isset($this->modules[$key])) {
            http_response_code(404);
            echo 'Module not found';
            exit;
        }
        return $this->modules[$key];
    }

    private function findRecord(string $table, int $id): array
    {
        $stmt = Db::pdo()->prepare('SELECT * FROM '.$table.' WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $record = $stmt->fetch();
        if (! $record) {
            http_response_code(404);
            echo 'Record not found';
            exit;
        }
        return $record;
    }

    private function collectFields(string $moduleKey, array $module): array
    {
        $data = [];
        foreach ($module['fields'] as $field => $meta) {
            if ($meta['type'] === 'roles') {
                continue;
            }
            $value = $_POST[$field] ?? null;
            if ($meta['type'] === 'checkbox') {
                $value = isset($_POST[$field]) ? 1 : 0;
            }
            if ($moduleKey === 'users' && $field === 'password') {
                if ($value === null || $value === '') {
                    continue;
                }
                $value = password_hash((string) $value, PASSWORD_DEFAULT);
            }
            if (in_array($field, ['tags', 'stats', 'config', 'result'], true)) {
                $value = $value ? json_encode($this->parseJsonOrCsv($value)) : null;
            }
            $data[$field] = $value;
        }
        return $data;
    }

    private function parseJsonOrCsv(string $value): mixed
    {
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    private function assertCsrf(): void
    {
        if (! Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            exit;
        }
    }

    private function requireAuth(): void
    {
        if (! Auth::check()) {
            redirect('/admin/login');
        }
    }

    private function authorizeModule(string $moduleKey): void
    {
        if (Auth::hasRole('admin')) {
            return;
        }

        $map = [
            'pages' => 'pages.manage',
            'posts' => 'posts.manage',
            'media' => 'media.manage',
            'forums' => 'forums.manage',
            'forum_threads' => 'forums.manage',
            'forum_posts' => 'forums.manage',
            'comments' => 'comments.moderate',
            'servers' => 'game.manage',
            'player_stats' => 'game.manage',
            'guilds' => 'game.manage',
            'game_events' => 'game.manage',
            'market_items' => 'game.manage',
            'game_matches' => 'game.manage',
            'integrations' => 'integrations.manage',
            'themes' => 'themes.manage',
            'plugins' => 'plugins.manage',
            'users' => 'users.manage',
            'roles' => 'roles.manage',
            'permissions' => 'permissions.manage',
            'settings' => 'settings.manage',
            'forms' => 'forms.manage',
            'form_fields' => 'forms.manage',
            'form_submissions' => 'forms.manage',
            'activity_logs' => 'settings.manage',
            'conversations' => 'users.manage',
            'messages' => 'users.manage',
            'groups' => 'users.manage',
            'user_profiles' => 'users.manage',
        ];

        $permission = $map[$moduleKey] ?? null;
        if ($permission && Auth::hasPermission($permission)) {
            return;
        }

        http_response_code(403);
        echo 'Forbidden';
        exit;
    }

    private function logAction(string $action, array $meta = []): void
    {
        $stmt = Db::pdo()->prepare('INSERT INTO activity_logs (user_id, action, meta, created_at) VALUES (:user_id, :action, :meta, NOW())');
        $stmt->execute([
            'user_id' => current_user()['id'] ?? null,
            'action' => $action,
            'meta' => json_encode($meta),
        ]);
    }

    private function rolesList(): array
    {
        return Db::pdo()->query('SELECT * FROM roles ORDER BY name ASC')->fetchAll();
    }

    private function themesList(): array
    {
        return Db::pdo()->query('SELECT * FROM themes ORDER BY name ASC')->fetchAll();
    }

    private function userRoleIds(int $userId): array
    {
        $stmt = Db::pdo()->prepare('SELECT role_id FROM role_user WHERE user_id = :id');
        $stmt->execute(['id' => $userId]);
        return array_map(fn ($r) => (int) $r['role_id'], $stmt->fetchAll());
    }

    private function syncUserRoles(int $userId, array $roleIds): void
    {
        $roleIds = array_map('intval', $roleIds);
        Db::pdo()->prepare('DELETE FROM role_user WHERE user_id = :id')->execute(['id' => $userId]);
        $stmt = Db::pdo()->prepare('INSERT INTO role_user (role_id, user_id) VALUES (:role_id, :user_id)');
        foreach ($roleIds as $roleId) {
            $stmt->execute(['role_id' => $roleId, 'user_id' => $userId]);
        }
    }
}
