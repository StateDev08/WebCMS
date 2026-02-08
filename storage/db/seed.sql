INSERT INTO roles (name, slug, created_at) VALUES
('Administrator', 'admin', NOW()),
('Editor', 'editor', NOW()),
('Moderator', 'moderator', NOW());

INSERT INTO permissions (name, slug, created_at) VALUES
('pages.manage', 'pages.manage', NOW()),
('posts.manage', 'posts.manage', NOW()),
('media.manage', 'media.manage', NOW()),
('forums.manage', 'forums.manage', NOW()),
('comments.moderate', 'comments.moderate', NOW()),
('game.manage', 'game.manage', NOW()),
('plugins.manage', 'plugins.manage', NOW()),
('themes.manage', 'themes.manage', NOW()),
('users.manage', 'users.manage', NOW()),
('roles.manage', 'roles.manage', NOW()),
('permissions.manage', 'permissions.manage', NOW()),
('settings.manage', 'settings.manage', NOW()),
('forms.manage', 'forms.manage', NOW()),
('integrations.manage', 'integrations.manage', NOW());

INSERT INTO permission_role (permission_id, role_id)
SELECT id, 1 FROM permissions;

INSERT INTO users (name, email, password, created_at) VALUES
('Admin', 'admin@example.com', '$2y$10$UrBt7WAylaZkeTxqmKDCO.k3nVrFhf.z694.nE3w057uxDtROxknK', NOW());

INSERT INTO role_user (role_id, user_id) VALUES (1, 1);

INSERT INTO themes (name, slug, description, is_active, created_at) VALUES
('Default', 'default', 'Standard Dark Theme', 1, NOW()),
('Neon', 'neon', 'Neon Dark Theme', 0, NOW()),
('Fantasy', 'fantasy', 'Fantasy Dark Theme', 0, NOW()),
('Sci-Fi', 'scifi', 'Sci-Fi Dark Theme', 0, NOW()),
('Retro', 'retro', 'Retro Dark Theme', 0, NOW());

INSERT INTO pages (title, slug, content, status, locale, is_homepage, published_at, created_at) VALUES
('Startseite', 'startseite', '<p>Willkommen im PHP CMS.</p>', 'published', 'de', 1, NOW(), NOW());
