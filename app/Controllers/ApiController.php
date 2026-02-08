<?php

namespace App\Controllers;

use App\Core\Db;

class ApiController
{
    public function pages(): void
    {
        $stmt = Db::pdo()->query("SELECT id, title, slug, content, status, locale, created_at, updated_at FROM pages WHERE status = 'published'");
        $this->json($stmt->fetchAll());
    }

    public function page(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT id, title, slug, content, status, locale, created_at, updated_at FROM pages WHERE slug = :slug AND status = 'published' LIMIT 1");
        $stmt->execute(['slug' => $params['slug']]);
        $this->json($stmt->fetch());
    }

    public function posts(): void
    {
        $stmt = Db::pdo()->query("SELECT id, title, slug, excerpt, content, status, locale, created_at, updated_at FROM posts WHERE status = 'published'");
        $this->json($stmt->fetchAll());
    }

    public function post(array $params): void
    {
        $stmt = Db::pdo()->prepare("SELECT id, title, slug, excerpt, content, status, locale, created_at, updated_at FROM posts WHERE slug = :slug AND status = 'published' LIMIT 1");
        $stmt->execute(['slug' => $params['slug']]);
        $this->json($stmt->fetch());
    }

    private function json(mixed $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
