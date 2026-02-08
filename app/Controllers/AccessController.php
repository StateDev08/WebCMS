<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Db;
use App\Core\View;

class AccessController
{
    private function requireAuth(): void
    {
        if (! Auth::check()) {
            redirect('/admin/login');
        }
    }

    public function userRoles(array $params): void
    {
        $this->requireAuth();
        $userId = (int) $params['id'];
        $user = $this->find('users', $userId);
        $roles = Db::pdo()->query('SELECT * FROM roles ORDER BY name ASC')->fetchAll();
        $assigned = Db::pdo()->prepare('SELECT role_id FROM role_user WHERE user_id = :id');
        $assigned->execute(['id' => $userId]);
        $assigned = array_map(fn ($r) => (int) $r['role_id'], $assigned->fetchAll());

        View::render('admin/user_roles', [
            'user' => $user,
            'roles' => $roles,
            'assigned' => $assigned,
            'csrf' => Csrf::token(),
        ], 'layouts/admin');
    }

    public function updateUserRoles(array $params): void
    {
        $this->requireAuth();
        if (! Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }
        $userId = (int) $params['id'];
        $roleIds = array_map('intval', $_POST['roles'] ?? []);
        Db::pdo()->prepare('DELETE FROM role_user WHERE user_id = :id')->execute(['id' => $userId]);
        $stmt = Db::pdo()->prepare('INSERT INTO role_user (role_id, user_id) VALUES (:role_id, :user_id)');
        foreach ($roleIds as $roleId) {
            $stmt->execute(['role_id' => $roleId, 'user_id' => $userId]);
        }
        redirect('/admin/users/'.$userId.'/roles');
    }

    public function rolePermissions(array $params): void
    {
        $this->requireAuth();
        $roleId = (int) $params['id'];
        $role = $this->find('roles', $roleId);
        $permissions = Db::pdo()->query('SELECT * FROM permissions ORDER BY name ASC')->fetchAll();
        $assigned = Db::pdo()->prepare('SELECT permission_id FROM permission_role WHERE role_id = :id');
        $assigned->execute(['id' => $roleId]);
        $assigned = array_map(fn ($r) => (int) $r['permission_id'], $assigned->fetchAll());

        View::render('admin/role_permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'assigned' => $assigned,
            'csrf' => Csrf::token(),
        ], 'layouts/admin');
    }

    public function updateRolePermissions(array $params): void
    {
        $this->requireAuth();
        if (! Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }
        $roleId = (int) $params['id'];
        $permissionIds = array_map('intval', $_POST['permissions'] ?? []);
        Db::pdo()->prepare('DELETE FROM permission_role WHERE role_id = :id')->execute(['id' => $roleId]);
        $stmt = Db::pdo()->prepare('INSERT INTO permission_role (permission_id, role_id) VALUES (:permission_id, :role_id)');
        foreach ($permissionIds as $permissionId) {
            $stmt->execute(['permission_id' => $permissionId, 'role_id' => $roleId]);
        }
        redirect('/admin/roles/'.$roleId.'/permissions');
    }

    private function find(string $table, int $id): array
    {
        $stmt = Db::pdo()->prepare('SELECT * FROM '.$table.' WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (! $row) {
            http_response_code(404);
            echo 'Not found';
            exit;
        }
        return $row;
    }
}
